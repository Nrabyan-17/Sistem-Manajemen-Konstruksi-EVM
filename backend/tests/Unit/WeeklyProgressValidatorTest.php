<?php

namespace Tests\Unit;

use App\Support\WeeklyProgressValidator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WeeklyProgressValidatorTest extends TestCase
{
    private function item(array $changes = []): array
    {
        return array_replace([
            'id' => 'WORK-1', 'previous_approved_progress' => 20,
            'current_week_additional' => 30, 'planned_cumulative' => 60,
            'item_weight_percent' => 20, 'reason' => '',
        ], $changes);
    }

    public function test_partial_item_is_valid_and_input_is_not_mutated(): void
    {
        $input = $this->item();
        $original = $input;
        $result = WeeklyProgressValidator::validateItem($input);
        $this->assertSame($original, $input);
        $this->assertSame('50.0000', $result['calculation']['cumulative']);
        $this->assertSame('-10.0000', $result['calculation']['deviation']);
        $this->assertSame('30.0000', $result['current_week_additional']);
    }

    public function test_completed_item_uses_a_system_reason_without_manual_input(): void
    {
        $result = WeeklyProgressValidator::validateItem($this->item([
            'previous_approved_progress' => 100, 'current_week_additional' => 0,
        ]));
        $this->assertSame(WeeklyProgressValidator::COMPLETED_REASON, $result['reason']);
        $this->assertSame('SYSTEM_COMPLETED', $result['reason_source']);
        $this->assertTrue($result['calculation']['finished']);
        $this->assertSame('100.0000', $result['calculation']['cumulative']);
    }

    public function test_zero_on_unfinished_item_requires_a_reason_but_accepts_explained_zero(): void
    {
        $result = WeeklyProgressValidator::validateItem($this->item([
            'previous_approved_progress' => 50, 'current_week_additional' => '0',
            'reason' => '  Hujan menghentikan pekerjaan.  ',
        ]));
        $this->assertSame('0.0000', $result['current_week_additional']);
        $this->assertSame('  Hujan menghentikan pekerjaan.  ', $result['reason']);
        $this->assertSame('USER', $result['reason_source']);
        $this->expectException(InvalidArgumentException::class);
        WeeklyProgressValidator::validateItem($this->item(['previous_approved_progress' => 50, 'current_week_additional' => 0, 'reason' => '   ']));
    }

    #[DataProvider('invalidItemChanges')]
    public function test_invalid_item_data_is_rejected(array $changes): void
    {
        $this->expectException(InvalidArgumentException::class);
        WeeklyProgressValidator::validateItem($this->item($changes));
    }

    public static function invalidItemChanges(): array
    {
        return [
            [['current_week_additional' => -1]], [['current_week_additional' => 'abc']],
            [['current_week_additional' => null]], [['current_week_additional' => '']],
            [['current_week_additional' => '   ']], [['current_week_additional' => false]],
            [['current_week_additional' => []]], [['current_week_additional' => '101']],
            [['previous_approved_progress' => 70, 'current_week_additional' => 31]],
            [['previous_approved_progress' => 100, 'current_week_additional' => 1]],
            [['previous_approved_progress' => 101]], [['planned_cumulative' => -1]],
            [['item_weight_percent' => 101]], [['reason' => []]],
            [['reason' => str_repeat('a', 1001)]], [['id' => '']],
            [['id' => 'item.with.dot']], [['unsupported' => true]],
        ];
    }

    public function test_draft_keeps_missing_progress_distinct_from_zero(): void
    {
        foreach ([null, '', '  '] as $blank) {
            $result = WeeklyProgressValidator::validateItem($this->item(['current_week_additional' => $blank]), draft: true);
            $this->assertNull($result['current_week_additional']);
            $this->assertNull($result['calculation']);
        }
        $zero = WeeklyProgressValidator::validateItem($this->item(['current_week_additional' => 0]), draft: true);
        $this->assertSame('0.0000', $zero['current_week_additional']);
        $this->assertSame('20.0000', $zero['calculation']['cumulative']);
    }

    public function test_draft_does_not_bypass_numeric_limits(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WeeklyProgressValidator::validateItem($this->item(['current_week_additional' => 81]), draft: true);
    }

    public function test_item_structure_requires_every_base_field(): void
    {
        $item = $this->item();
        unset($item['planned_cumulative']);
        $this->expectException(InvalidArgumentException::class);
        WeeklyProgressValidator::validateItem($item);
    }

    public function test_period_accepts_equal_dates_and_a_real_leap_day(): void
    {
        $this->assertSame(['period_start' => '2024-02-29', 'period_end' => '2024-02-29'], WeeklyProgressValidator::validatePeriod('2024-02-29', '2024-02-29'));
    }

    #[DataProvider('invalidPeriods')]
    public function test_invalid_or_reversed_dates_are_rejected(mixed $start, mixed $end): void
    {
        $this->expectException(InvalidArgumentException::class);
        WeeklyProgressValidator::validatePeriod($start, $end);
    }

    public static function invalidPeriods(): array
    {
        return [
            ['2026-07-05', '2026-06-29'], ['2026-02-29', '2026-03-01'],
            ['2026-06-31', '2026-07-01'], ['2026-6-01', '2026-06-02'],
            ['', '2026-06-02'], [null, '2026-06-02'], ['2026-06-01', []],
            ["2026-06-01\0", '2026-06-02'], ['0000-01-01', '2026-06-02'],
        ];
    }

    public function test_report_keeps_completed_items_and_normalizes_every_item(): void
    {
        $result = WeeklyProgressValidator::validateReport([
            'period_start' => '2026-06-29', 'period_end' => '2026-07-05',
            'items' => [$this->item(), $this->item(['id' => 'WORK-2', 'previous_approved_progress' => 100, 'current_week_additional' => 0])],
        ]);
        $this->assertCount(2, $result['items']);
        $this->assertSame('WORK-2', $result['items'][1]['id']);
        $this->assertSame('100.0000', $result['items'][1]['calculation']['cumulative']);
    }

    #[DataProvider('invalidReportItems')]
    public function test_report_rejects_invalid_detail_structure(mixed $items): void
    {
        $this->expectException(InvalidArgumentException::class);
        WeeklyProgressValidator::validateReport(['period_start' => '2026-06-29', 'period_end' => '2026-07-05', 'items' => $items]);
    }

    public static function invalidReportItems(): array
    {
        return [[[]], [null], [['not an item']], [['named' => []]]];
    }

    public function test_duplicate_item_ids_are_rejected(): void
    {
        $this->expectException(InvalidArgumentException::class);
        WeeklyProgressValidator::validateReport(['period_start' => '2026-06-29', 'period_end' => '2026-07-05', 'items' => [$this->item(), $this->item()]]);
    }
}
