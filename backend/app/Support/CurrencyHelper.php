<?php

namespace App\Support;

class CurrencyHelper
{
    /**
     * Format an amount to Indonesian Rupiah (IDR).
     *
     * Rules:
     * - Miliar: e.g. 12500000000 => 'Rp 12,5 M' (18000000000 => 'Rp 18 M')
     * - Juta:   e.g. 850000000   => 'Rp 850 Juta'
     * - Ribu:   e.g. 750000      => 'Rp 750 Ribu'
     * - Nilai kecil / exact: e.g. 500000 => 'Rp 500.000'
     *
     * Decimal separator: comma (,)
     * Thousand separator: dot (.)
     *
     * @param float|int|string|null $amount
     * @param bool $compact
     * @param bool $showSign
     * @return string
     */
    public static function format($amount, bool $compact = true, bool $showSign = false): string
    {
        if ($amount === null || $amount === '') {
            return '—';
        }

        $numeric = (float) $amount;
        $isNegative = $numeric < 0;
        $abs = abs($numeric);

        $sign = '';
        if ($isNegative) {
            $sign = '-';
        } elseif ($showSign && $numeric > 0) {
            $sign = '+';
        }

        if (!$compact) {
            return $sign . 'Rp ' . number_format($abs, 0, ',', '.');
        }

        // Tier 1: Miliar (>= 1 Billion)
        if ($abs >= 1_000_000_000) {
            $val = $abs / 1_000_000_000;
            return $sign . 'Rp ' . self::formatDecimal($val, 2) . ' Miliar';
        }

        // Tier 2: Juta (>= 1 Million)
        if ($abs >= 1_000_000) {
            $val = $abs / 1_000_000;
            return $sign . 'Rp ' . self::formatDecimal($val, 2) . ' Juta';
        }

        // Tier 3: Ribu (>= 750 Thousand or round thousands > 500k)
        if ($abs > 500_000 && fmod($abs, 1_000) === 0.0) {
            $val = $abs / 1_000;
            return $sign . 'Rp ' . self::formatDecimal($val, 2) . ' Ribu';
        }

        // Tier 4: Nilai kecil / exact format (e.g. 500000 => Rp 500.000, 500 => Rp 500)
        return $sign . 'Rp ' . number_format($abs, 0, ',', '.');
    }

    /**
     * Format decimal with comma as separator, stripping unnecessary trailing zeros.
     * E.g. 12.50 => '12,5', 18.00 => '18', 12.75 => '12,75'
     */
    public static function formatDecimal(float $val, int $maxDecimals = 2): string
    {
        $formatted = number_format($val, $maxDecimals, ',', '.');
        if (str_contains($formatted, ',')) {
            $formatted = rtrim(rtrim($formatted, '0'), ',');
        }
        return $formatted;
    }

    /**
     * Format with full descriptive word for billions (e.g. 'Rp 12,5 Miliar').
     */
    public static function formatWord($amount, bool $showSign = false): string
    {
        if ($amount === null || $amount === '') {
            return '—';
        }

        $numeric = (float) $amount;
        $isNegative = $numeric < 0;
        $abs = abs($numeric);

        $sign = '';
        if ($isNegative) {
            $sign = '-';
        } elseif ($showSign && $numeric > 0) {
            $sign = '+';
        }

        if ($abs >= 1_000_000_000) {
            $val = $abs / 1_000_000_000;
            return $sign . 'Rp ' . self::formatDecimal($val, 2) . ' Miliar';
        }

        if ($abs >= 1_000_000) {
            $val = $abs / 1_000_000;
            return $sign . 'Rp ' . self::formatDecimal($val, 2) . ' Juta';
        }

        if ($abs > 500_000 && fmod($abs, 1_000) === 0.0) {
            $val = $abs / 1_000;
            return $sign . 'Rp ' . self::formatDecimal($val, 2) . ' Ribu';
        }

        return $sign . 'Rp ' . number_format($abs, 0, ',', '.');
    }

    /**
     * Format full currency with dot as thousand separator (e.g. 'Rp 12.500.000.000').
     */
    public static function formatFull($amount, bool $showSign = false): string
    {
        return self::format($amount, false, $showSign);
    }

    /**
     * Format difference / variance values with '+' or '-' sign.
     * E.g. '+Rp 1,5 M' or '-Rp 200 Juta'
     */
    public static function formatDiff($amount, bool $compact = true): string
    {
        return self::format($amount, $compact, true);
    }
}
