<?php

namespace App\Support;

use LogicException;

/** Pure percentage arithmetic. No EVM indicators, persistence or display rounding. */
final class WeeklyProgressCalculator
{
    /**
     * All arguments use percent scale 0..100, not fractions 0..1.
     * Results are exact decimal strings: four places for percentages/deviation,
     * ten places for weighted contribution (exact product divided by 100).
     * Deviation/contribution units are percentage points.
     */
    public static function calculate(
        mixed $previousApprovedProgress,
        mixed $currentWeekAdditional,
        mixed $plannedCumulative,
        mixed $itemWeightPercent,
    ): array {
        if (PHP_INT_SIZE < 8) {
            throw new LogicException('Weekly progress fixed-point arithmetic requires 64-bit PHP.');
        }
        $previous = WeeklyProgressDecimal::percent($previousApprovedProgress, 'previous_approved_progress');
        $additional = WeeklyProgressDecimal::percent($currentWeekAdditional, 'current_week_additional');
        $planned = WeeklyProgressDecimal::percent($plannedCumulative, 'planned_cumulative');
        $weight = WeeklyProgressDecimal::percent($itemWeightPercent, 'item_weight_percent');
        $maximum = WeeklyProgressDecimal::HUNDRED - $previous;
        if ($additional > $maximum) {
            throw WeeklyProgressValidationException::forField('current_week_additional', 'Tambahan progres melebihi sisa yang tersedia; realisasi kumulatif maksimal 100%.');
        }
        $cumulative = $previous + $additional;

        return [
            'max_additional' => WeeklyProgressDecimal::format($maximum),
            'cumulative' => WeeklyProgressDecimal::format($cumulative),
            'deviation' => WeeklyProgressDecimal::format($cumulative - $planned),
            // Both operands have four decimal places; /100 adds two more.
            'weighted_contribution' => WeeklyProgressDecimal::format($weight * $cumulative, 10),
            'finished' => $cumulative === WeeklyProgressDecimal::HUNDRED,
            // Additive aliases: finished/cumulative retain their original meanings.
            'previously_finished' => $previous === WeeklyProgressDecimal::HUNDRED,
            'completed_this_week' => $previous < WeeklyProgressDecimal::HUNDRED && $cumulative === WeeklyProgressDecimal::HUNDRED,
            'cumulative_progress' => WeeklyProgressDecimal::format($cumulative),
        ];
    }
}
