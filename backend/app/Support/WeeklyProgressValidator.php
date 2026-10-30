<?php

namespace App\Support;

use DateTimeImmutable;
use DateTimeZone;

/**
 * Pure validation of caller-prepared data. Item validation is fail-fast;
 * report validation aggregates errors across period and all supplied items.
 * Throws WeeklyProgressValidationException (an InvalidArgumentException subtype)
 * with errors(): list<{path, field, item_id, message}>. Public arguments and
 * existing success keys are preserved; error messages are Indonesian.
 * Does not establish membership, role, baseline validity or data authenticity.
 * The caller must supply authoritative approved progress during integration.
 */
final class WeeklyProgressValidator
{
    // TEMPORARY BUSINESS DECISION: confirm before operational backend activation.
    public const COMPLETED_REASON = 'Pekerjaan sudah mencapai 100% pada laporan sebelumnya.';

    /**
     * Input keys: id, previous_approved_progress, current_week_additional,
     * planned_cumulative, item_weight_percent, optional reason.
     * Draft allows null/blank additional for unfinished items only; it remains
     * null and has no calculated result. Supplied values must still be valid.
     * Added completion fields: previously_finished is based on approved history;
     * completed_this_week/cumulative_progress are null for an incomplete draft.
     * Valid user notes are returned unchanged, including surrounding whitespace.
     */
    public static function validateItem(array $item, bool $draft = false): array
    {
        try {
            return self::validateItemData($item, $draft);
        } catch (WeeklyProgressValidationException $exception) {
            $id = $item['id'] ?? null;
            throw $exception->withContext(itemId: is_string($id) || is_int($id) ? (string) $id : null);
        }
    }

    private static function validateItemData(array $item, bool $draft): array
    {
        $required = ['id', 'previous_approved_progress', 'current_week_additional', 'planned_cumulative', 'item_weight_percent'];
        self::shape($item, $required, [...$required, 'reason'], 'item');
        if ((!is_string($item['id']) && !is_int($item['id']))
            || !preg_match('/\A[A-Za-z0-9_-]{1,128}\z/', (string) $item['id'])) {
            throw WeeklyProgressValidationException::forField('id', 'ID item wajib berisi 1–128 huruf, angka, garis bawah, atau tanda hubung.');
        }
        $previous = WeeklyProgressDecimal::percent($item['previous_approved_progress'], 'previous_approved_progress');
        $planned = WeeklyProgressDecimal::percent($item['planned_cumulative'], 'planned_cumulative');
        $weight = WeeklyProgressDecimal::percent($item['item_weight_percent'], 'item_weight_percent');
        $finished = $previous === WeeklyProgressDecimal::HUNDRED;
        $reason = $item['reason'] ?? '';
        if (!is_string($reason) || mb_strlen($reason) > 1000) {
            throw WeeklyProgressValidationException::forField('reason', 'Keterangan harus berupa teks maksimal 1000 karakter.');
        }
        $hasReason = WeeklyProgressReason::hasMeaningfulText($reason);
        if (!$hasReason && $reason !== '' && !$finished) {
            throw WeeklyProgressValidationException::forField('reason', 'Keterangan harus berisi teks bermakna, bukan hanya spasi atau karakter tak terlihat.');
        }
        $normalizedAdditional = WeeklyProgressInputAdapter::normalizePercent(
            $item['current_week_additional'], $draft && !$finished, 'current_week_additional',
        );
        $additional = $normalizedAdditional === null ? null : WeeklyProgressDecimal::percent($normalizedAdditional, 'current_week_additional');
        if ($additional === 0 && !$finished && !$draft && !$hasReason) {
            throw WeeklyProgressValidationException::forField('reason', 'Keterangan wajib diisi ketika tambahan progres 0% pada pekerjaan yang belum selesai.');
        }
        $calculation = $additional === null ? null : WeeklyProgressCalculator::calculate(
            WeeklyProgressDecimal::format($previous),
            WeeklyProgressDecimal::format($additional),
            WeeklyProgressDecimal::format($planned),
            WeeklyProgressDecimal::format($weight),
        );

        return [
            'id' => (string) $item['id'],
            'previous_approved_progress' => WeeklyProgressDecimal::format($previous),
            'current_week_additional' => $additional === null ? null : WeeklyProgressDecimal::format($additional),
            'planned_cumulative' => WeeklyProgressDecimal::format($planned),
            'item_weight_percent' => WeeklyProgressDecimal::format($weight),
            'reason' => $hasReason ? $reason : ($finished ? self::COMPLETED_REASON : ''),
            'reason_source' => $hasReason ? 'USER' : ($finished ? 'SYSTEM_COMPLETED' : null),
            'calculation' => $calculation,
            'previously_finished' => $finished,
            'completed_this_week' => $calculation['completed_this_week'] ?? null,
            'cumulative_progress' => $calculation['cumulative_progress'] ?? null,
        ];
    }

    /** Strict ISO dates; no automatic rollover of invalid calendar dates. */
    public static function validatePeriod(mixed $start, mixed $end): array
    {
        $validationErrors = [];
        foreach (['period_start' => $start, 'period_end' => $end] as $field => $value) {
            if (!is_string($value) || !preg_match('/\A[0-9]{4}-[0-9]{2}-[0-9]{2}\z/', $value) || substr($value, 0, 4) === '0000') {
                $validationErrors[] = WeeklyProgressValidationException::forField($field, 'Tanggal wajib memakai format YYYY-MM-DD.')->errors()[0];
                continue;
            }
            $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value, new DateTimeZone('UTC'));
            $errors = DateTimeImmutable::getLastErrors();
            if (!$date || ($errors !== false && ($errors['warning_count'] || $errors['error_count']))
                || $date->format('Y-m-d') !== $value) {
                $validationErrors[] = WeeklyProgressValidationException::forField($field, 'Tanggal kalender tidak valid.')->errors()[0];
            }
        }
        if ($validationErrors) {
            throw new WeeklyProgressValidationException($validationErrors);
        }
        if ($start > $end) {
            throw WeeklyProgressValidationException::forField('period_start', 'Tanggal mulai tidak boleh setelah tanggal akhir.');
        }

        return ['period_start' => $start, 'period_end' => $end];
    }

    /** Report shape only; no week mapping, assignment or database baseline checks. */
    public static function validateReport(array $report, bool $draft = false): array
    {
        self::shape($report, ['period_start', 'period_end', 'items'], ['period_start', 'period_end', 'items'], 'report');
        $errors = [];
        $period = [];
        try {
            $period = self::validatePeriod($report['period_start'], $report['period_end']);
        } catch (WeeklyProgressValidationException $exception) {
            $errors = $exception->errors();
        }
        if (!is_array($report['items']) || !array_is_list($report['items']) || $report['items'] === []) {
            $errors = [...$errors, ...WeeklyProgressValidationException::forField('items', 'Detail laporan harus berupa daftar item yang tidak kosong.')->errors()];
            throw new WeeklyProgressValidationException($errors);
        }
        $items = [];
        $seen = [];
        foreach ($report['items'] as $index => $item) {
            if (!is_array($item)) {
                $error = WeeklyProgressValidationException::forField('items', 'Detail pekerjaan harus berupa objek item.')->errors()[0];
                $error['path'] = 'items.'.$index;
                $errors[] = $error;
                continue;
            }
            try {
                $normalized = self::validateItem($item, $draft);
            } catch (WeeklyProgressValidationException $exception) {
                $id = $item['id'] ?? null;
                $errors = [...$errors, ...$exception->withContext('items.'.$index, is_string($id) || is_int($id) ? (string) $id : null)->errors()];
                continue;
            }
            if (isset($seen[$normalized['id']])) {
                $errors = [...$errors, ...WeeklyProgressValidationException::forField('id', 'ID item tercantum lebih dari satu kali.')->withContext('items.'.$index, $normalized['id'])->errors()];
                continue;
            }
            $seen[$normalized['id']] = true;
            $items[] = $normalized;
        }
        if ($errors) {
            throw new WeeklyProgressValidationException($errors);
        }

        return [...$period, 'items' => $items];
    }

    private static function shape(array $input, array $required, array $allowed, string $field): void
    {
        $errors = [];
        foreach (array_diff($required, array_keys($input)) as $name) {
            $errors = [...$errors, ...WeeklyProgressValidationException::forField($name, "Kolom $name wajib tersedia pada $field.")->errors()];
        }
        foreach (array_diff(array_keys($input), $allowed) as $name) {
            $errors = [...$errors, ...WeeklyProgressValidationException::forField((string) $name, "Kolom $name tidak didukung pada $field.")->errors()];
        }
        if ($errors) {
            throw new WeeklyProgressValidationException($errors);
        }
    }
}
