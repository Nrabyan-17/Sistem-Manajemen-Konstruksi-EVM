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
            ->assertSee('+ Item RAB')
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
            ->set('addendumTitle', 'Pekerjaan Retaining Wall Tambahan')
            ->set('addendumValue', 2500000000)
            ->set('addendumDesc', 'Tambahan penguatan dinding penahan tanah')
            ->call('submitAddendumCost')
            ->assertDispatched('addendum-cost-saved')
            ->assertSee('Pekerjaan Retaining Wall Tambahan')
            ->assertSee('ADDENDUM BIAYA');
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
            ->assertSee('ADDENDUM WAKTU');

        $this->assertEquals(45, $component->get('totalDaysAdded'));
        $this->assertNotEmpty($component->get('effectiveBastDate'));
    }

    public function test_delete_rab_confirmation_can_be_cancelled(): void
    {
        Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001'])
            ->call('confirmDeleteRab', 0)
            ->assertSet('showDeleteRabModal', true)
            ->call('closeDeleteRabModal')
            ->assertSet('showDeleteRabModal', false)
            ->assertSet('selectedRabIndex', null);
    }

    public function test_void_addendum_confirmation_can_be_cancelled(): void
    {
        Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001'])
            ->call('confirmVoidAddendum', 0)
            ->assertSet('showVoidAddendumModal', true)
            ->call('closeVoidAddendumModal')
            ->assertSet('showVoidAddendumModal', false)
            ->assertSet('selectedAddendumIndex', null);
    }

    public function test_rab_item_can_be_edited_and_subtotal_is_recalculated(): void
    {
        Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001'])
            ->call('openEditRabModal', 0)
            ->set('editRabItem', 'Item RAB Diperbarui')
            ->set('editRabVolume', 2)
            ->set('editRabHargaSatuan', 100000)
            ->call('updateRabItem')
            ->assertSet('showEditRabModal', false)
            ->assertSet('boqItems.0.item', 'Item RAB Diperbarui')
            ->assertSet('boqItems.0.subtotal', 200000);
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
}
