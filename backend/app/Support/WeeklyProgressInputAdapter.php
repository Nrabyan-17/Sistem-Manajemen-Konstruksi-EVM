<?php

namespace App\Support;

/**
 * Opt-in adapter for later integration; does not read Livewire, Auth or a DB.
 * Accepts integer or original dot-decimal string (0..100, up to four places).
 * Comma/exponent formats remain unsupported pending a team locale decision.
 * Legacy floats are rejected: obtain the original decimal from the source or
 * agree an explicit audited conversion policy before integrating legacy data.
 * Never use (float), parseFloat or round to manufacture an accepted value.
 * This temporary input precision does not define database columns.
 */
final class WeeklyProgressInputAdapter
{
    public static function normalizePercent(mixed $value, bool $allowBlank = false, string $field = 'progress'): ?string
    {
        $blank = $value === null || (is_string($value) && preg_match('/\A[\p{Z}\s]*\z/u', $value) === 1);
        if ($blank) {
            if ($allowBlank) {
                return null;
            }
            throw WeeklyProgressValidationException::forField($field, 'Nilai progres wajib diisi; nilai kosong bukan 0%.');
        }

        return WeeklyProgressDecimal::format(WeeklyProgressDecimal::percent($value, $field));
    }
}
