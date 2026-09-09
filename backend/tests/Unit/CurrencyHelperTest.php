<?php

namespace Tests\Unit;

use App\Support\CurrencyHelper;
use PHPUnit\Framework\TestCase;

class CurrencyHelperTest extends TestCase
{
    public function test_formats_miliar_examples()
    {
        $this->assertEquals('Rp 12,5 Miliar', CurrencyHelper::format(12500000000));
        $this->assertEquals('Rp 9,8 Miliar', CurrencyHelper::format(9800000000));
        $this->assertEquals('Rp 2,5 Miliar', CurrencyHelper::format(2500000000));
        $this->assertEquals('Rp 18 Miliar', CurrencyHelper::format(18000000000));
        $this->assertEquals('Rp 12,75 Miliar', CurrencyHelper::format(12750000000));
    }

    public function test_formats_juta_examples()
    {
        $this->assertEquals('Rp 850 Juta', CurrencyHelper::format(850000000));
        $this->assertEquals('Rp 1,5 Juta', CurrencyHelper::format(1500000));
    }

    public function test_formats_ribu_and_small_values()
    {
        $this->assertEquals('Rp 750 Ribu', CurrencyHelper::format(750000));
        $this->assertEquals('Rp 500.000', CurrencyHelper::format(500000));
        $this->assertEquals('Rp 500', CurrencyHelper::format(500));
        $this->assertEquals('Rp 0', CurrencyHelper::format(0));
    }

    public function test_formats_full_rupiah()
    {
        $this->assertEquals('Rp 12.500.000.000', CurrencyHelper::formatFull(12500000000));
        $this->assertEquals('Rp 500.000', CurrencyHelper::formatFull(500000));
    }

    public function test_formats_word_rupiah()
    {
        $this->assertEquals('Rp 12,5 Miliar', CurrencyHelper::formatWord(12500000000));
        $this->assertEquals('Rp 9,8 Miliar', CurrencyHelper::formatWord(9800000000));
        $this->assertEquals('Rp 2,5 Miliar', CurrencyHelper::formatWord(2500000000));
        $this->assertEquals('Rp 850 Juta', CurrencyHelper::formatWord(850000000));
    }

    public function test_formats_differences_and_negative_values()
    {
        $this->assertEquals('+Rp 1,5 Miliar', CurrencyHelper::formatDiff(1500000000));
        $this->assertEquals('-Rp 1,5 Miliar', CurrencyHelper::formatDiff(-1500000000));
        $this->assertEquals('-Rp 850 Juta', CurrencyHelper::formatDiff(-850000000));
        $this->assertEquals('Rp 0', CurrencyHelper::formatDiff(0));
    }
}
