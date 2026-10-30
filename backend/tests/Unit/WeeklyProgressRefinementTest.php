<?php

namespace Tests\Unit;

use App\Support\WeeklyProgressCalculator as Calculator;
use App\Support\WeeklyProgressDecimal as Decimal;
use App\Support\WeeklyProgressInputAdapter as Adapter;
use App\Support\WeeklyProgressValidationException as ValidationError;
use App\Support\WeeklyProgressValidator as Validator;
use App\Support\WeeklyProgressWorkflow as Workflow;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WeeklyProgressRefinementTest extends TestCase
{
    private function item(array $changes = []): array
    {
        return array_replace([
            'id' => 'ITEM-1', 'previous_approved_progress' => '20',
            'current_week_additional' => '30', 'planned_cumulative' => '60',
            'item_weight_percent' => '20', 'reason' => '',
        ], $changes);
    }

    #[DataProvider('blankReasons')]
    public function test_unfinished_zero_rejects_whitespace_or_invisible_reason(string $reason): void
    {
        $this->expectException(ValidationError::class);
        Validator::validateItem($this->item(['current_week_additional' => 0, 'reason' => $reason]));
    }

    #[DataProvider('blankReasons')]
    public function test_rejection_reason_requires_meaningful_text(string $reason): void
    {
        $this->assertFalse(Workflow::canTransition('PENDING_APPROVAL', 'REJECTED', 'ADMIN_PROYEK', $reason));
        $this->expectException(ValidationError::class);
        Workflow::transition('PENDING_APPROVAL', 'REJECTED', 'ADMIN_PROYEK', $reason);
    }

    #[DataProvider('blankReasons')]
    public function test_completed_item_uses_system_fallback_only_when_note_is_blank(string $reason): void
    {
        $result = Validator::validateItem($this->item(['previous_approved_progress' => 100, 'current_week_additional' => 0, 'reason' => $reason]));
        $this->assertSame(Validator::COMPLETED_REASON, $result['reason']);
        $this->assertSame('SYSTEM_COMPLETED', $result['reason_source']);
        $this->assertTrue($result['previously_finished']);
        $this->assertFalse($result['completed_this_week']);
    }

    public static function blankReasons(): array
    {
        return array_map(fn ($text) => [$text], [
            '', '   ', "\t\r\n", "\u{00A0}", "\u{2003}", "\u{202F}",
            "\u{200B}", "\u{2060}", "\u{FEFF}", " \t\u{00A0}\u{200B}\n",
        ]);
    }

    public function test_valid_manual_note_is_preserved_exactly_for_completed_and_unfinished_items(): void
    {
        $note = " \u{00A0}Catatan pemeriksaan: selesai.\nBaris kedua.\t ";
        foreach ([20, 100] as $previous) {
            $result = Validator::validateItem($this->item(['previous_approved_progress' => $previous, 'current_week_additional' => 0, 'reason' => $note]));
            $this->assertSame($note, $result['reason']);
            $this->assertSame('USER', $result['reason_source']);
        }
        $decision = Workflow::transition('PENDING_APPROVAL', 'REJECTED', 'ADMIN_PROYEK', $note);
        $this->assertSame($note, $decision['reason']);
    }

    public function test_invalid_utf8_is_rejected_in_both_reason_paths(): void
    {
        foreach (['item', 'workflow'] as $path) {
            try {
                if ($path === 'item') {
                    Validator::validateItem($this->item(['reason' => "\xFF"]));
                } else {
                    Workflow::transition('PENDING_APPROVAL', 'REJECTED', 'ADMIN_PROYEK', "\xFF");
                }
                $this->fail('Invalid UTF-8 was accepted.');
            } catch (ValidationError $exception) {
                $this->assertSame('reason', $exception->errors()[0]['field']);
            }
        }
    }

    public function test_report_aggregates_period_second_and_third_item_errors_with_identity(): void
    {
        try {
            Validator::validateReport([
                'period_start' => '2026-07-05', 'period_end' => '2026-06-29',
                'items' => [
                    $this->item(),
                    $this->item(['id' => 'ITEM-2', 'current_week_additional' => 81]),
                    $this->item(['id' => 'ITEM-3', 'current_week_additional' => 0]),
                ],
            ]);
            $this->fail('Invalid report was accepted.');
        } catch (ValidationError $exception) {
            $this->assertInstanceOf(InvalidArgumentException::class, $exception);
            $errors = $exception->errors();
            $this->assertSame(['period_start', 'items.1.current_week_additional', 'items.2.reason'], array_column($errors, 'path'));
            $this->assertSame(['period_start', 'current_week_additional', 'reason'], array_column($errors, 'field'));
            $this->assertSame([null, 'ITEM-2', 'ITEM-3'], array_column($errors, 'item_id'));
            $this->assertStringContainsString('Tambahan progres', $errors[1]['message']);
            $this->assertStringContainsString('Keterangan wajib', $errors[2]['message']);
        }
    }

    public function test_missing_field_and_duplicate_id_errors_keep_original_indices(): void
    {
        $missing = $this->item(['id' => 'ITEM-3']);
        unset($missing['planned_cumulative']);
        try {
            Validator::validateReport([
                'period_start' => '2026-06-29', 'period_end' => '2026-07-05',
                'items' => [$this->item(), $this->item(), $missing],
            ]);
            $this->fail('Duplicate or missing fields were accepted.');
        } catch (ValidationError $exception) {
            $this->assertSame(['items.1.id', 'items.2.planned_cumulative'], array_column($exception->errors(), 'path'));
            $this->assertSame(['ITEM-1', 'ITEM-3'], array_column($exception->errors(), 'item_id'));
        }
    }

    public function test_draft_null_is_not_zero_and_completed_draft_still_requires_explicit_zero(): void
    {
        $draft = Validator::validateItem($this->item(['current_week_additional' => null]), draft: true);
        $this->assertNull($draft['current_week_additional']);
        $this->assertNull($draft['calculation']);
        $this->assertNull($draft['completed_this_week']);
        $this->assertNull($draft['cumulative_progress']);
        $this->assertFalse($draft['previously_finished']);
        $this->expectException(ValidationError::class);
        Validator::validateItem($this->item(['previous_approved_progress' => 100, 'current_week_additional' => null]), draft: true);
    }

    public function test_invalid_detail_and_both_period_errors_have_structured_paths(): void
    {
        try {
            Validator::validateReport(['period_start' => '', 'period_end' => '2026-02-30', 'items' => [false]]);
            $this->fail('Malformed report was accepted.');
        } catch (ValidationError $exception) {
            $this->assertSame(['period_start', 'period_end', 'items.0'], array_column($exception->errors(), 'path'));
            $this->assertSame('items', $exception->errors()[2]['field']);
            $this->assertNull($exception->errors()[2]['item_id']);
        }
    }

    public function test_completion_flags_distinguish_previous_completion_from_current_completion(): void
    {
        foreach ([[100, 0, true, false], [70, 30, false, true], [20, 30, false, false]] as [$previous, $additional, $before, $now]) {
            $result = Calculator::calculate($previous, $additional, 60, 20);
            $this->assertSame($before, $result['previously_finished']);
            $this->assertSame($now, $result['completed_this_week']);
            $this->assertSame($before || $now, $result['finished']);
            $this->assertSame($result['cumulative'], $result['cumulative_progress']);
        }
        $justFinished = Validator::validateItem($this->item(['previous_approved_progress' => 70]));
        $this->assertTrue($justFinished['completed_this_week']);
        $this->assertFalse($justFinished['previously_finished']);
        $this->assertSame('', $justFinished['reason']);
        $this->assertNull($justFinished['reason_source']);
    }

    public function test_adapter_normalizes_decimal_strings_without_rounding_or_blank_to_zero(): void
    {
        $this->assertSame('20.0000', Adapter::normalizePercent(20));
        $this->assertSame('20.1250', Adapter::normalizePercent('20.125'));
        $this->assertSame('0.0000', Adapter::normalizePercent('0'));
        $this->assertSame('0.0001', Adapter::normalizePercent('0.0001'));
        foreach ([null, '', ' ', "\u{00A0}"] as $blank) {
            $this->assertNull(Adapter::normalizePercent($blank, allowBlank: true));
        }
    }

    #[DataProvider('invalidAdapterValues')]
    public function test_adapter_rejects_invalid_or_legacy_values_explicitly(mixed $value): void
    {
        $this->expectException(ValidationError::class);
        Adapter::normalizePercent($value);
    }

    public static function invalidAdapterValues(): array
    {
        return array_map(fn ($value) => [$value], [
            20.0, 20.125, NAN, INF, null, '', '20,5', '20abc', '1e2',
            '0.00001', ' 20 ', '020', '100.0001', true, [], "\u{200B}",
        ]);
    }

    #[DataProvider('invalidFormatterValues')]
    public function test_formatter_rejects_unsupported_precision_and_integer_domain(mixed $value, mixed $places): void
    {
        $this->expectException(InvalidArgumentException::class);
        Decimal::format($value, $places);
    }

    public static function invalidFormatterValues(): array
    {
        return [
            [123, 0], [123, -1], [123, 3], [123, 11], [123, '4'],
            [123.0, 4], ['123', 4], [PHP_INT_MIN, 4], [PHP_INT_MAX, 10],
            [1_000_001, 4], [-1_000_001, 4],
            [1_000_000_000_001, 10], [-1_000_000_000_001, 10],
        ];
    }

    public function test_formatter_preserves_exact_signed_domain_boundaries(): void
    {
        $this->assertSame('100.0000', Decimal::format(1_000_000));
        $this->assertSame('-100.0000', Decimal::format(-1_000_000));
        $this->assertSame('100.0000000000', Decimal::format(1_000_000_000_000, 10));
        $this->assertSame('-100.0000000000', Decimal::format(-1_000_000_000_000, 10));
        $this->assertSame('0.0000', Decimal::format(0));
        $this->assertSame('0.0000000000', Decimal::format(0, 10));
    }

    public function test_full_workflow_matrix_keeps_only_the_four_allowed_transitions(): void
    {
        $states = ['DRAFT', 'PENDING_APPROVAL', 'REJECTED', 'APPROVED', 'UNKNOWN'];
        foreach ($states as $from) {
            foreach ($states as $to) {
                foreach (['PELAKSANA_LAPANGAN', 'ADMIN_PROYEK', 'UNKNOWN'] as $actor) {
                    foreach ([false, true] as $revision) {
                        $expected = !$revision && $from === 'DRAFT' && $to === 'PENDING_APPROVAL' && $actor === 'PELAKSANA_LAPANGAN'
                            || !$revision && $from === 'PENDING_APPROVAL' && in_array($to, ['APPROVED', 'REJECTED'], true) && $actor === 'ADMIN_PROYEK'
                            || $revision && $from === 'REJECTED' && $to === 'DRAFT' && $actor === 'PELAKSANA_LAPANGAN';
                        $this->assertSame($expected, Workflow::canTransition($from, $to, $actor, 'Catatan review', $revision), "$from -> $to / $actor / revision=$revision");
                    }
                }
            }
        }
    }
}
