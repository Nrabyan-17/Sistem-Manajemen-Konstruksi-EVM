<?php

namespace Tests\Unit;

use App\Support\WeeklyProgressCalculator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WeeklyProgressCalculatorTest extends TestCase
{
    public function test_requested_example_uses_percent_scale_and_exact_output(): void
    {
        $this->assertSame([
            'max_additional' => '80.0000', 'cumulative' => '50.0000',
            'deviation' => '-10.0000', 'weighted_contribution' => '10.0000000000',
            'finished' => false,
            'previously_finished' => false, 'completed_this_week' => false,
            'cumulative_progress' => '50.0000',
        ], WeeklyProgressCalculator::calculate(20, 30, 60, 20));
    }

    public function test_remaining_progress_can_be_used_exactly(): void
    {
        $result = WeeklyProgressCalculator::calculate(70, 30, 100, 100);
        $this->assertSame('100.0000', $result['cumulative']);
        $this->assertSame('100.0000000000', $result['weighted_contribution']);
        $this->assertSame('0.0000', $result['deviation']);
        $this->assertTrue($result['finished']);
    }

    public function test_completed_work_accepts_only_zero_additional(): void
    {
        $result = WeeklyProgressCalculator::calculate(100, 0, 90, 20);
        $this->assertSame('0.0000', $result['max_additional']);
        $this->assertSame('10.0000', $result['deviation']);
        $this->assertTrue($result['finished']);
    }

    #[DataProvider('invalidValues')]
    public function test_invalid_percentage_values_are_rejected_in_every_argument(mixed $value): void
    {
        foreach (range(0, 3) as $index) {
            $arguments = [0, 0, 0, 0];
            $arguments[$index] = $value;
            try {
                WeeklyProgressCalculator::calculate(...$arguments);
                $this->fail('Invalid percentage accepted.');
            } catch (InvalidArgumentException $exception) {
                $this->assertNotSame('', $exception->getMessage());
            }
        }
    }

    public static function invalidValues(): array
    {
        return array_map(fn ($value) => [$value], [
            -1, '101', '100.0001', '', ' ', null, 'abc', true, [],
            '1e2', '20,5', '0.00001', ' 20 ', '020', '999999999999999999999',
            0.1, INF, NAN,
        ]);
    }

    #[DataProvider('overflows')]
    public function test_cumulative_overflow_is_rejected(mixed $previous, mixed $additional): void
    {
        $this->expectException(InvalidArgumentException::class);
        WeeklyProgressCalculator::calculate($previous, $additional, 60, 20);
    }

    public static function overflows(): array
    {
        return [[70, 31], [100, 1], ['99.9999', '0.0002']];
    }

    public function test_decimal_arithmetic_does_not_round_or_use_binary_floats(): void
    {
        $result = WeeklyProgressCalculator::calculate('0.1', '0.2', '0.4', '33.3333');
        $this->assertSame('0.3000', $result['cumulative']);
        $this->assertSame('-0.1000', $result['deviation']);
        $this->assertSame('0.0999999000', $result['weighted_contribution']);
        $small = WeeklyProgressCalculator::calculate(0, '0.0001', 0, '0.0001');
        $this->assertSame('0.0000000001', $small['weighted_contribution']);
        $boundary = WeeklyProgressCalculator::calculate('99.9999', '0.0001', 100, 100);
        $this->assertSame('100.0000', $boundary['cumulative']);
    }
}
