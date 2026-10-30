<?php

namespace App\Livewire;

use Livewire\Attributes\Locked;
use Livewire\Component;

class PelaksanaWeeklyForm extends Component
{
    #[Locked] public array $context = [];
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
            $finished = (float) $item['previous'] >= 100;
            $this->increments[$item['id']] = $finished ? '0' : '';
            // Keep the SRS zero-progress reason rule, including completed items.
            $this->reasons[$item['id']] = $finished ? 'Pekerjaan sudah mencapai 100% pada laporan sebelumnya.' : '';
        }
    }

    public function editable(): bool
    {
        return !empty($this->context['items']) && in_array($this->context['status'] ?? '', ['DRAFT', 'REJECTED'], true);
    }

    public function updated(): void
    {
        $this->notice = '';
        $this->resetValidation();
    }

    public function previewAction(string $action): void
    {
        if (!$this->editable() || !in_array($action, ['draft', 'submit'], true)) {
            $this->notice = 'Laporan ini hanya dapat dibaca. Tidak ada perubahan yang disimpan.';
            return;
        }

        $rules = [
            'week' => 'required|integer|min:1',
            'start' => 'required|date_format:Y-m-d',
            'end' => 'required|date_format:Y-m-d|after_or_equal:start',
        ];
        foreach ($this->context['items'] as $item) {
            $id = $item['id'];
            $max = max(0, 100 - $item['previous']);
            $rules["increments.$id"] = ($action === 'submit' ? 'required' : 'nullable')."|numeric|min:0|max:$max";
            $isZero = isset($this->increments[$id]) && is_numeric($this->increments[$id]) && (float) $this->increments[$id] === 0.0;
            $rules["reasons.$id"] = ($action === 'submit' && $isZero ? 'required' : 'nullable').'|string|max:1000';
        }
        $this->validate($rules, [
            'required' => 'Kolom ini wajib diisi. Progres 0% harus disertai alasan.',
            'numeric' => 'Masukkan angka yang valid.',
            'min' => 'Nilai tidak boleh kurang dari :min.',
            'max' => 'Nilai melebihi batas :max; kumulatif item maksimal 100%.',
            'date_format' => 'Gunakan tanggal yang valid.',
            'after_or_equal' => 'Tanggal akhir harus sama atau setelah tanggal mulai.',
        ]);

        $this->notice = $action === 'draft'
            ? 'Pratinjau draft valid. Belum disimpan: layanan penyimpanan belum terhubung. Isian hanya tersedia selama halaman ini aktif.'
            : 'Pratinjau siap diajukan. Belum dikirim: layanan penyimpanan dan persetujuan Admin Proyek belum terhubung.';
    }

    public function render()
    {
        $rows = [];
        $total = 0.0;
        $plan = 0.0;
        $complete = true;
        foreach ($this->context['items'] ?? [] as $item) {
            $input = $this->increments[$item['id']] ?? '';
            $valid = is_numeric($input) && (float) $input >= 0 && (float) $input + $item['previous'] <= 100;
            $cumulative = $valid ? $item['previous'] + (float) $input : null;
            $rows[] = array_merge($item, ['finished' => (float) $item['previous'] >= 100, 'cumulative' => $cumulative, 'deviation' => $cumulative === null ? null : $cumulative - $item['target']]);
            $complete = $complete && $valid;
            $total += $item['weight'] * ($cumulative ?? 0) / 100;
            $plan += $item['weight'] * $item['target'] / 100;
        }

        return view('livewire.pelaksana-weekly-form', [
            'rows' => $rows, 'complete' => $complete && count($rows) > 0,
            'total' => $total, 'plan' => $plan, 'editable' => $this->editable(),
        ]);
    }
}
