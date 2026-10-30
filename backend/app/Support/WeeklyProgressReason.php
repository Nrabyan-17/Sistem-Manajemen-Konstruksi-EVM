<?php

namespace App\Support;

/** Checks text without trimming, rewriting or removing user content. */
final class WeeklyProgressReason
{
    public static function hasMeaningfulText(string $text): bool
    {
        // Separators, controls and formatting characters alone are not a reason.
        $result = preg_match('/[^\p{Z}\p{C}\s]/u', $text);
        if ($result === false) {
            throw WeeklyProgressValidationException::forField('reason', 'Keterangan harus berupa teks UTF-8 yang valid.');
        }

        return $result === 1;
    }
}
