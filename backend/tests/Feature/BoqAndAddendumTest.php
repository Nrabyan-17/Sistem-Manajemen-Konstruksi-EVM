<?php

namespace Tests\Feature;

use App\Livewire\ProjectDetail;
use Livewire\Livewire;
use Tests\TestCase;

class BoqAndAddendumTest extends TestCase
{
    public function test_boq_tab_renders_actions_and_summary_cards(): void
    {
        Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001'])
            ->assertStatus(200)
            ->assertSee('+ Input Item RAB')
            ->assertSee('+ Addendum Biaya')
            ->assertSee('+ Addendum Waktu')
            ->assertSee('RAB Awal (Baseline BAC)')
            ->assertSee('Addendum Biaya (Item Baru)')
            ->assertSee('Addendum Waktu (EOT)');
    }

    public function test_input_rab_item_adds_to_boq_table(): void
    {
        Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001'])
            ->set('newRabItem', 'Pemasangan Plafon Gypsum')
            ->set('newRabSatuan', 'm²')
            ->set('newRabVolume', 500)
            ->set('newRabHargaSatuan', 120000)
            ->call('submitRabItem')
            ->assertDispatched('rab-item-saved')
            ->assertSee('Pemasangan Plafon Gypsum')
            ->assertSee('500')
            ->assertSee('m²');
    }

    public function test_input_addendum_cost_updates_addendum_and_risk(): void
    {
        Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001'])
            ->set('addendumContractNo', 'ADD-01/SPK-2026/001')
            ->set('addendumTitle', 'Pekerjaan Retaining Wall Tambahan')
            ->set('addendumValue', 2500000000)
            ->set('addendumDesc', 'Tambahan penguatan dinding penahan tanah')
            ->call('submitAddendumCost')
            ->assertDispatched('addendum-cost-saved')
            ->assertSee('ADD-01/SPK-2026/001')
            ->assertSee('Pekerjaan Retaining Wall Tambahan')
            ->assertSee('BIAYA (COST)');
    }

    public function test_input_addendum_time_updates_bast_date_and_remaining_days(): void
    {
        $component = Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001'])
            ->set('addDays', 45)
            ->set('timeTitle', 'EOT Cuaca Ekstrem')
            ->set('timeReason', 'Hujan ekstrem berkepanjangan menghambat pekerjaan struktur')
            ->call('submitAddendumTime')
            ->assertDispatched('addendum-time-saved')
            ->assertSee('+45 Hari')
            ->assertSee('EOT Cuaca Ekstrem')
            ->assertSee('WAKTU (EOT)');

        $this->assertEquals(45, $component->get('totalDaysAdded'));
        $this->assertNotEmpty($component->get('effectiveBastDate'));
    }

    public function test_only_pending_addendum_can_be_edited(): void
    {
        $component = Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-002'])
            ->call('openEditAddendumModal', 0);

        $component->assertSet('showEditAddendumModal', false);

        $component->set('addendumTitle', 'Pending Addendum')
            ->set('addendumValue', 100000)
            ->set('addendumDesc', 'Pending')
            ->call('submitAddendumCost');

        $component->call('openEditAddendumModal', count($component->get('addendums')) - 1)
            ->assertSet('showEditAddendumModal', true)
            ->set('editAddendumTitle', 'Pending Addendum Diperbarui')
            ->call('updateAddendum')
            ->assertSet('showEditAddendumModal', false)
            ->assertSet('addendums.' . (count($component->get('addendums')) - 1) . '.title', 'Pending Addendum Diperbarui');
    }

    public function test_submit_weekly_progress_calculates_deviasi_and_saves(): void
    {
        $component = Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001'])
            ->set('inputWeekNumber', 26)
            ->set('inputPlanPct', 2.00)
            ->set('inputActualPct', 2.50)
            ->assertSee('Label Deviasi Mingguan:')
            ->call('submitWeeklyProgress')
            ->assertDispatched('weekly-progress-saved');

        $progressList = $component->get('weeklyProgress');
        $lastItem = end($progressList);
        $this->assertEquals(26, $lastItem['week']);
        $this->assertEquals(2.00, $lastItem['plan_pct']);
        $this->assertEquals(2.50, $lastItem['actual_pct']);
        $this->assertEquals(-0.50, $lastItem['deviasi']);
    }
}
