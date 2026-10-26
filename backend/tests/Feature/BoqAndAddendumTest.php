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

    public function test_addendum_cost_modal_has_automatic_fields_and_supports_pdf_upload(): void
    {
        $component = Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001']);

        // Check that opening addendum cost modal auto-fills default title and contract no
        $component->call('openAddendumCostModal')
            ->assertSet('addendumTitle', 'Add tambah kurang volume')
            ->assertSee('Add tambah kurang volume')
            ->assertSee('Upload PDF RAB Addendum')
            ->assertSee('Upload PDF Time Schedule Addendum')
            ->assertSee('Grand Horizon Tower')
            ->assertSee('Tgl Kontrak')
            ->assertSee('Tgl BAST')
            ->assertSee('Nilai Kontrak');

        // Create a fake PDF upload
        $file = \Illuminate\Http\UploadedFile::fake()->create('rab_addendum_v1.pdf', 150, 'application/pdf');
        $timeScheduleFile = \Illuminate\Http\UploadedFile::fake()->create('ts_addendum_v1.pdf', 120, 'application/pdf');
        $component->set('addendumRabFile', $file)
            ->set('addendumTsFile', $timeScheduleFile)
            ->set('addendumValue', 750000000)
            ->call('submitAddendumCost')
            ->assertDispatched('addendum-cost-saved')
            ->assertSee('Add tambah kurang volume');

        $addendums = $component->get('addendums');
        $lastAddendum = end($addendums);
        $this->assertEquals('Add tambah kurang volume', $lastAddendum['title']);
        $this->assertEquals(750000000, $lastAddendum['value']);
        $this->assertEquals('rab_addendum_v1.pdf', $lastAddendum['attachment']);
        $this->assertEquals('ts_addendum_v1.pdf', $lastAddendum['time_schedule_attachment']);
    }

    public function test_input_addendum_time_updates_bast_date_and_remaining_days(): void
    {
        $pdfFile = \Illuminate\Http\UploadedFile::fake()->create('surat_permohonan_eot.pdf', 80, 'application/pdf');

        $component = Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001'])
            ->call('openAddendumTimeModal')
            ->assertSee('Unggah Dokumen Pendukung Addendum Waktu (.pdf)')
            ->set('addDays', 45)
            ->set('timeTitle', 'EOT Cuaca Ekstrem')
            ->set('timeReason', 'Hujan ekstrem berkepanjangan menghambat pekerjaan struktur')
            ->set('addendumTimePdfFile', $pdfFile)
            ->call('submitAddendumTime')
            ->assertDispatched('addendum-time-saved')
            ->assertSee('+45 Hari')
            ->assertSee('EOT Cuaca Ekstrem')
            ->assertSee('WAKTU (EOT)');

        $this->assertEquals(45, $component->get('totalDaysAdded'));
        $this->assertNotEmpty($component->get('effectiveBastDate'));

        $addendums = $component->get('addendums');
        $last = end($addendums);
        $this->assertEquals('surat_permohonan_eot.pdf', $last['attachment'] ?? null);
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
            ->assertSee('Deviasi Mingguan:')
            ->call('submitWeeklyProgress')
            ->assertDispatched('weekly-progress-saved');

        $progressList = $component->get('weeklyProgress');
        $lastItem = end($progressList);
        $this->assertEquals(26, $lastItem['week']);
        $this->assertEquals(2.00, $lastItem['plan_pct']);
        $this->assertEquals(2.50, $lastItem['actual_pct']);
        $this->assertEquals(-0.50, $lastItem['deviasi']);
    }

    public function test_weekly_progress_auto_calculates_dates_and_plan_when_week_changes(): void
    {
        $component = Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001']);
        $component->call('openWeeklyModal')
            ->assertSet('weeklyModalMode', 'manual')
            ->set('inputWeekNumber', 5)
            ->assertSee('09 Feb 2026 — 15 Feb 2026')
            ->assertSet('inputPlanPct', '2.50');

        $this->assertNotEmpty($component->get('inputDateRange'));
        $this->assertEquals('2.50', $component->get('inputPlanPct'));
    }

    public function test_opening_weekly_modal_preserves_entered_actual_progress(): void
    {
        Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001'])
            ->set('inputActualPct', '2.50')
            ->call('openWeeklyModal')
            ->assertSet('inputActualPct', '2.50');
    }

    public function test_weekly_modal_supports_pdf_upload(): void
    {
        $file = \Illuminate\Http\UploadedFile::fake()->create('laporan_mingguan.pdf', 50, 'application/pdf');

        $component = Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001'])
            ->call('openWeeklyModal')
            ->assertSee('Unggah File Laporan (.pdf)')
            ->assertSee('Klik atau seret file laporan PDF ke sini')
            ->assertDontSee('Download Format Template')
            ->assertDontSee('Unduh CSV')
            ->set('weeklyImportFile', $file)
            ->assertSet('importError', '');

        $this->assertNotEmpty($component->get('importPreviewRows'));

        $component->call('processWeeklyImport')
            ->assertDispatched('weekly-progress-saved');
    }

    public function test_weekly_progress_table_is_sorted_descending_by_week(): void
    {
        $component = Livewire::test(ProjectDetail::class, ['projectId' => 'PRJ-001']);
        $html = $component->html();

        // Check that W25 appears before W1 in the rendered table
        $posW25 = strpos($html, 'W25');
        $posW1 = strpos($html, 'W1</td>');

        $this->assertNotFalse($posW25);
        $this->assertNotFalse($posW1);
        $this->assertLessThan($posW1, $posW25, 'Week 25 should appear before Week 1 in the table');
    }
}
