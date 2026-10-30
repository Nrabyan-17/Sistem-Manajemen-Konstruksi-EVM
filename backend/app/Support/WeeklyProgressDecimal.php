<?php

namespace App\Support;

use InvalidArgumentException;

/**
 * Internal fixed-point helper, provisional until business precision is agreed.
 * Percent input: integer or dot-decimal string, at most four fractional digits.
 * Floats, exponent notation, comma decimals and silent rounding are rejected.
 * This is an arithmetic boundary, not a final database precision contract.
 */
final class WeeklyProgressDecimal
{
    public const SCALE = 10_000;
    public const HUNDRED = 100 * self::SCALE;

    public static function percent(mixed $value, string $field): int
    {
        if ((!is_int($value) && !is_string($value))
            || !preg_match('/\A(?:0|[1-9][0-9]{0,2})(?:\.[0-9]{1,4})?\z/', (string) $value)) {
            throw WeeklyProgressValidationException::forField($field, 'Gunakan integer atau string desimal bertitik dengan maksimal empat angka pecahan. Float dan format lain tidak dikonversi otomatis.');
        }

        [$whole, $fraction] = array_pad(explode('.', (string) $value, 2), 2, '');
        $scaled = (int) $whole * self::SCALE + (int) str_pad($fraction, 4, '0');
        if ($scaled > self::HUNDRED) {
            throw WeeklyProgressValidationException::forField($field, 'Nilai persentase harus berada di antara 0% dan 100%.');
        }

        return $scaled;
    }

    /** Supported scaled domains: +/-100% at four or ten fractional places. */
    public static function format(mixed $value, mixed $places = 4): string
    {
        if (PHP_INT_SIZE < 8 || !is_int($value) || !is_int($places) || !in_array($places, [4, 10], true)) {
            throw new InvalidArgumentException('Formatter memerlukan PHP 64-bit, nilai integer, dan presisi 4 atau 10.');
        }
        $limit = $places === 4 ? self::HUNDRED : self::HUNDRED * self::HUNDRED;
        // Check the signed range before abs(), including PHP_INT_MIN.
        if ($value < -$limit || $value > $limit) {
            throw new InvalidArgumentException('Nilai integer berada di luar domain persentase formatter.');
        }
        $digits = (string) abs($value);
        $digits = str_pad($digits, $places + 1, '0', STR_PAD_LEFT);

        return ($value < 0 ? '-' : '').substr($digits, 0, -$places).'.'.substr($digits, -$places);
    }
}
