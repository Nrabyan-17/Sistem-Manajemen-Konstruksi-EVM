<?php

namespace Tests\Feature;

use App\Livewire\Projects;
use App\Livewire\ProjectDetail;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectsCurrencyTest extends TestCase
{
    public function test_projects_page_displays_currency_in_rupiah(): void
    {
        Livewire::test(Projects::class)
            ->assertStatus(200)
            ->assertSee('Rp 82,7 Miliar') // Total contract KPI
            ->assertSee('Rp 12,5 Miliar') // PRJ-001 Contract Value
            ->assertSee('Rp 18 Miliar')   // PRJ-002 Contract Value
            ->assertSee('Est. Cost: Rp 9,8 Miliar') // PRJ-001 EAC
            ->assertDontSee('Rp 12.50B')
            ->assertDontSee('Rp 82.7B');
    }

    public function test_project_detail_page_displays_currency_in_rupiah(): void
    {
        Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001'])
            ->assertStatus(200)
            ->assertSee('Rp 12,5 Miliar') // Contract Value in overview & financial tabs
            ->assertDontSee('Rp 12.50B');
    }

    public function test_create_project_defaults_to_on_track_without_status_input(): void
    {
        Livewire::test(Projects::class)
            ->set('newProjId', 'PRJ-099')
            ->set('newProjName', 'Gedung Uji Coba')
            ->set('newSpk', 'SPK-2026-099')
            ->set('newClient', 'PT Uji Coba')
            ->set('newPm', 'Andi Pratama')
            ->set('newValue', '15000000000')
            ->set('newStartDate', '2026-03-01')
            ->set('newBastDate', '2026-12-31')
            ->call('createProject')
            ->assertDispatched('project-created')
            ->assertSee('Gedung Uji Coba')
            ->assertSee('Rp 15 Miliar');
    }

    public function test_project_detail_displays_addendum_tab(): void
    {
        Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001'])
            ->assertStatus(200)
            ->assertSee('Addendum')
            ->assertSee('ADD-001-01')
            ->assertSee('Spun Pile Volume Adjustment')
            ->assertSee('+Rp 1,5 Miliar')
            ->assertDontSee('href="/addendum"', false);
    }
}
