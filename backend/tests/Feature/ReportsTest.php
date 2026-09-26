<?php

namespace Tests\Feature;

use App\Livewire\Reports;
use Livewire\Livewire;
use Tests\TestCase;

class ReportsTest extends TestCase
{
    public function test_reports_page_renders_successfully(): void
    {
        Livewire::test(Reports::class)
            ->assertStatus(200)
            ->assertSee('EVM Performance Reports')
            ->assertSee('Grand Horizon Tower')
            ->assertSee('Performance Breakdown');
    }

    public function test_clicking_project_row_opens_evm_pop_modal(): void
    {
        Livewire::test(Reports::class)
            ->assertSet('showProjectModal', false)
            ->call('openProjectModal', 'PRJ-001')
            ->assertSet('showProjectModal', true)
            ->assertSee('Grand Horizon Tower')
            ->assertSee('Parameter Utama EVM')
            ->assertSee('Indeks Kinerja & Analisis Varians')
            ->assertSee('Buka Detail Proyek Lengkap')
            ->call('closeProjectModal')
            ->assertSet('showProjectModal', false);
    }
}
