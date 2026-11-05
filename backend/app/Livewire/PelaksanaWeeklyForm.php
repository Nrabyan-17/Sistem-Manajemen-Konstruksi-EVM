<?php

namespace App\Livewire;

use App\Support\WeeklyProgressCalculator;
use App\Support\WeeklyProgressDecimal;
use App\Support\WeeklyProgressInputAdapter;
use App\Support\WeeklyProgressValidationException;
use App\Support\WeeklyProgressValidator;
use App\Support\WeeklyProgressWorkflow;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;
use Livewire\Attributes\Locked;
use Livewire\Component;

class PelaksanaWeeklyForm extends Component
{
    // Caller-prepared context only. Real authorization/source loading is deferred.
    #[Locked] public array $context = [];
    // Keep source validation failures across hydration: JSON may serialize 20.0
    // as 20. A later request must not silently accept that legacy float.
    #[Locked] public array $sourceErrors = [];
    public array $increments = [];
    public array $reasons = [];
    public int $week = 1;
    public string $start = '';
    public string $end = '';
    public string $notice = '';

    public function mount(array $context = []): void
    {
        $this->context = $context;
        $this->week = $context['week'] ?? 1;
        $this->start = $context['start'] ?? '';
        $this->end = $context['end'] ?? '';
        foreach ($context['items'] ?? [] as $item) {
            $id = $item['id'];
            $this->increments[$id] = '';
            $this->reasons[$id] = '';
            try {
                $baseline = $this->baseline($item);
                if ($baseline['calculation']['previously_finished']) {
                    $this->increments[$id] = '0';
                    $normalized = WeeklyProgressValidator::validateItem($this->itemPayload($item));
                    $this->reasons[$id] = $normalized['reason'];
                }
            } catch (WeeklyProgressValidationException $exception) {
                $this->sourceErrors[$id] = $exception->errors();
                $this->mapErrors($exception);
            }
        }
    }

    public function editable(): bool
    {
        // A supplied category describes preview rules, never proves authorization.
        return !empty($this->context['items']) && WeeklyProgressWorkflow::canEdit(
            $this->context['status'] ?? '', WeeklyProgressWorkflow::PELAKSANA,
        );
    }

    public function updated(): void
    {
        $this->notice = '';
        $this->resetValidation();
        if ($this->editable()) {
            $this->validatePreview(draft: true);
        }
    }

    public function previewAction(string $action): void
    {
        $this->notice = '';
        if (!$this->editable() || !in_array($action, ['draft', 'submit'], true)) {
            $this->notice = ($this->context['status'] ?? '') === WeeklyProgressWorkflow::REJECTED
                ? 'Laporan ditolak hanya dapat dibaca sampai revisi baru tersedia. Tidak ada perubahan yang disimpan.'
                : 'Laporan ini hanya dapat dibaca. Tidak ada perubahan yang disimpan.';
            return;
        }
        $this->resetValidation();
        if ($this->validatePreview(draft: $action === 'draft')) {
            $this->notice = 'Validasi berhasil. Penyimpanan laporan belum tersedia pada mode pratinjau.';
        }
    }

    private function itemPayload(array $item): array
    {
        return [
            'id' => $item['id'],
            'previous_approved_progress' => $item['previous'] ?? null,
            'current_week_additional' => $this->increments[$item['id']] ?? null,
            'planned_cumulative' => $item['target'] ?? null,
            'item_weight_percent' => $item['weight'] ?? null,
            'reason' => $this->reasons[$item['id']] ?? '',
        ];
    }

    private function validatePreview(bool $draft): bool
    {
        // Week numbering has no backend contract yet; retain the basic UI check.
        $weekValidator = Validator::make(['week' => $this->week], ['week' => 'required|integer|min:1'], [
            'week.required' => 'Nomor minggu wajib diisi.',
            'week.integer' => 'Nomor minggu harus berupa bilangan bulat.',
            'week.min' => 'Nomor minggu minimal 1.',
        ]);
        foreach ($weekValidator->errors()->messages() as $key => $messages) {
            foreach ($messages as $message) {
                $this->addError($key, $message);
            }
        }
        if ($this->sourceErrors !== []) {
            foreach ($this->sourceErrors as $errors) {
                $this->mapErrors(new WeeklyProgressValidationException($errors));
            }

            return false;
        }
        try {
            // Validator internally uses InputAdapter and Reason. Never take
            // targets, weights or accumulated results from public form state.
            WeeklyProgressValidator::validateReport([
                'period_start' => $this->start,
                'period_end' => $this->end,
                'items' => array_map(fn ($item) => $this->itemPayload($item), $this->context['items'] ?? []),
            ], $draft);
        } catch (WeeklyProgressValidationException $exception) {
            $this->mapErrors($exception);
        }

        return $this->getErrorBag()->isEmpty();
    }

    private function mapErrors(WeeklyProgressValidationException $exception): void
    {
        $items = $this->context['items'] ?? [];
        foreach ($exception->errors() as $error) {
            $key = match ($error['field']) {
                'period_start' => 'start',
                'period_end' => 'end',
                default => 'context',
            };
            $id = $error['item_id'];
            if ($id === null && preg_match('/^items\.(\d+)\./', $error['path'], $match)) {
                $id = $items[(int) $match[1]]['id'] ?? null;
            }
            if ($id !== null && in_array((string) $id, array_map(fn ($item) => (string) $item['id'], $items), true)) {
                $key = match ($error['field']) {
                    'current_week_additional' => 'increments.'.$id,
                    'reason' => 'reasons.'.$id,
                    default => 'context',
                };
            }
            $this->addError($key, $error['message']);
        }
    }

    private function baseline(array $item): array
    {
        $previous = WeeklyProgressInputAdapter::normalizePercent($item['previous'] ?? null, field: 'previous_approved_progress');
        $target = WeeklyProgressInputAdapter::normalizePercent($item['target'] ?? null, field: 'planned_cumulative');
        $weight = WeeklyProgressInputAdapter::normalizePercent($item['weight'] ?? null, field: 'item_weight_percent');

        return [
            'previous' => $previous, 'target' => $target, 'weight' => $weight,
            'calculation' => WeeklyProgressCalculator::calculate($previous, '0', $target, $weight),
            'planned_contribution' => WeeklyProgressCalculator::calculate('0', $target, $target, $weight)['weighted_contribution'],
        ];
    }

    /** Exact locale formatting only; never rounds or feeds back into calculations. */
    private function displayPercent(?string $value): string
    {
        if ($value === null) {
            return '—';
        }
        [$whole, $fraction] = explode('.', $value);

        return $whole.','.str_pad(rtrim($fraction, '0'), 2, '0');
    }

    public function render()
    {
        $rows = [];
        $total = 0;
        $plan = 0;
        $complete = true;
        $summaryValid = true;
        foreach ($this->context['items'] ?? [] as $item) {
            $row = array_merge($item, [
                'previous' => null, 'target' => null, 'weight' => null,
                'max_additional' => null, 'cumulative' => null, 'deviation' => null,
                'previously_finished' => false, 'completed_this_week' => false,
                'source_error' => '',
            ]);
            try {
                if (isset($this->sourceErrors[$item['id']])) {
                    throw new WeeklyProgressValidationException($this->sourceErrors[$item['id']]);
                }
                $baseline = $this->baseline($item);
                $row = array_replace($row, array_intersect_key($baseline, array_flip(['previous', 'target', 'weight'])));
                $row['previously_finished'] = $baseline['calculation']['previously_finished'];
                $row['max_additional'] = $baseline['calculation']['max_additional'];
                // These are exact ten-place outputs of the existing calculator;
                // only sum them for display, without duplicating item formulas.
                $plan += (int) str_replace('.', '', $baseline['planned_contribution']);
                try {
                    $normalized = WeeklyProgressValidator::validateItem($this->itemPayload($item), draft: true);
                    $calculation = $normalized['calculation'];
                    if ($calculation !== null) {
                        $row['cumulative'] = $calculation['cumulative_progress'];
                        $row['deviation'] = $calculation['deviation'];
                        $row['completed_this_week'] = $calculation['completed_this_week'];
                        $total += (int) str_replace('.', '', $calculation['weighted_contribution']);
                    }
                } catch (WeeklyProgressValidationException) {
                    // Field errors are set by updated()/previewAction(); keep input.
                }
            } catch (WeeklyProgressValidationException $exception) {
                $row['source_error'] = 'Data sumber item belum valid. '.$exception->getMessage();
                $summaryValid = false;
            }
            $complete = $complete && $row['cumulative'] !== null;
            foreach (['previous', 'target', 'weight', 'max_additional', 'cumulative', 'deviation'] as $field) {
                $row['display'][$field] = $this->displayPercent($row[$field]);
            }
            $rows[] = $row;
        }
        $complete = $complete && count($rows) > 0;
        $summaryError = '';
        $totalDisplay = $planDisplay = $deviationDisplay = '—';
        if ($summaryValid) {
            try {
                $planDisplay = $this->displayPercent(WeeklyProgressDecimal::format($plan, 10));
                if ($complete) {
                    $totalDisplay = $this->displayPercent(WeeklyProgressDecimal::format($total, 10));
                    $deviationDisplay = $this->displayPercent(WeeklyProgressDecimal::format($total - $plan, 10));
                }
            } catch (InvalidArgumentException) {
                $summaryError = 'Ringkasan di luar batas persentase. Periksa bobot dan target dari sumber data.';
                $complete = false;
                $planDisplay = $totalDisplay = $deviationDisplay = '—';
            }
        }

        return view('livewire.pelaksana-weekly-form', [
            'rows' => $rows, 'complete' => $complete, 'editable' => $this->editable(),
            'planDisplay' => $planDisplay, 'totalDisplay' => $totalDisplay,
            'deviationDisplay' => $deviationDisplay, 'summaryError' => $summaryError,
        ]);
    }
}
