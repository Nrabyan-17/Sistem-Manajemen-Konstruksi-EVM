<?php

namespace Tests\Feature;

use App\Livewire\PelaksanaDashboard;
use App\Livewire\PelaksanaProjects;
use App\Livewire\PelaksanaReports;
use App\Livewire\PelaksanaWeeklyForm;
use App\Livewire\PelaksanaWorkspace;
use App\Support\PelaksanaDemoData;
use App\Support\WeeklyProgressCalculator;
use App\Support\WeeklyProgressValidator;
use Livewire\Livewire;
use Tests\TestCase;

class PelaksanaUiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $compiled = sys_get_temp_dir().'/pelaksana-ui-test-views';
        if (!is_dir($compiled)) {
            mkdir($compiled, 0700, true);
        }
        config(['view.compiled' => $compiled, 'logging.default' => 'null']);
        // Fail before execution, including any attempted operational write.
        \Illuminate\Support\Facades\DB::connection()->beforeExecuting(fn () => $this->fail('Preview must not execute database queries.'));
    }

    public function test_default_components_are_empty_and_never_load_demo_data(): void
    {
        Livewire::test(PelaksanaWorkspace::class)->assertSee('Belum ada sumber data')->assertDontSee('DEMO-01');
        Livewire::test(PelaksanaDashboard::class)->assertSee('Data penugasan belum terhubung');
        Livewire::test(PelaksanaProjects::class)->assertSee('Belum ada proyek ditampilkan');
        Livewire::test(PelaksanaReports::class)->assertSee('Belum ada laporan ditampilkan');
        Livewire::test(PelaksanaWeeklyForm::class)->assertSee('Data proyek dan item belum tersedia');
    }

    public function test_demo_workspace_renders_all_screens(): void
    {
        Livewire::test(PelaksanaWorkspace::class, ['demo' => true])
            ->assertSee('DEMO')->assertSee('Dashboard Pelaksana')
            ->set('screen', 'projects')->assertSee('DEMO-01')
            ->set('screen', 'form')->assertSee('Input Progres Mingguan')
            ->set('projectId', 'DEMO-02')->assertSee('Peningkatan Jalan Kawasan')
            ->set('screen', 'reports')->assertSee('Menunggu Persetujuan')->assertSee('Disetujui');
    }

    public function test_all_screens_share_the_preview_sidebar_and_topbar_without_admin_navigation(): void
    {
        $workspace = Livewire::test(PelaksanaWorkspace::class, ['demo' => true]);
        foreach (['dashboard', 'projects', 'form', 'reports'] as $screen) {
            $workspace->set('screen', $screen);
            $document = new \DOMDocument;
            $previous = libxml_use_internal_errors(true);
            // libxml's HTML4 parser cannot parse Alpine's @/: attribute names.
            // Strip only client directives for this structural HTML assertion.
            $html = preg_replace('/\s(?:@|:|x-)[\w:.-]+="[^"]*"/u', '', $workspace->html());
            $document->loadHTML('<?xml encoding="UTF-8">'.$html);
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
            $xpath = new \DOMXPath($document);
            $this->assertSame(1, $xpath->query('//aside[@id="pelaksana-sidebar"]')->length);
            $this->assertSame(4, $xpath->query('//aside/nav/button')->length);
            $this->assertSame(1, $xpath->query('//aside/nav/button[@aria-current="page"]')->length);
            $active = $xpath->query('//aside/nav/button[@aria-current="page"]')->item(0);
            $this->assertStringContainsString("'".$screen."'", $active->getAttribute('wire:click'));
            $this->assertSame(1, $xpath->query('//header//button[@aria-controls="pelaksana-sidebar"]')->length);
            $this->assertSame(1, $xpath->query('//main[@id="pelaksana-content"]')->length);
            $this->assertSame(0, $xpath->query('//nav[not(ancestor::aside)]')->length);
            $this->assertSame(1, $xpath->query('//aside//button[@disabled]')->length);
            $workspace->assertSee('DEMO — bukan akun login')->assertSee('Sign Out')
                ->assertDontSee('max-w-7xl', false)->assertDontSee('>Users<', false)
                ->assertDontSee('>Approvals<', false)->assertDontSee('Executive Director');
        }
    }

    public function test_project_search_and_report_filters_use_injected_records(): void
    {
        Livewire::test(PelaksanaProjects::class, ['projects' => PelaksanaDemoData::projects()])
            ->set('search', 'bekasi')->assertSee('DEMO-02')->assertDontSee('DEMO-01')
            ->set('search', 'absent')->assertSee('Proyek tidak ditemukan');
        Livewire::test(PelaksanaReports::class, ['reports' => PelaksanaDemoData::reports()])
            ->set('statusFilter', 'REJECTED')->assertSee('DEMO-R4')->assertDontSee('DEMO-R3')
            ->set('selectedReportId', 'DEMO-R4')->assertSee('Lengkapi alasan progres nol');
    }

    public function test_preview_calculates_weighted_increment_and_negative_deviation(): void
    {
        $context = PelaksanaDemoData::context();
        $context['items'] = [['id' => 'item', 'name' => 'Item uji', 'category' => 'Struktur', 'unit' => 'm³', 'volume' => 10, 'weight' => 20, 'target' => 50, 'previous' => 20]];
        Livewire::test(PelaksanaWeeklyForm::class, ['context' => $context])
            ->assertSee('Belum lengkap')->set('increments.item', '10')
            ->assertSee('30,00%')->assertSee('-20,00 pp')->assertSee('6,00%')
            ->call('previewAction', 'submit')->assertHasNoErrors()
            ->assertSee('Validasi berhasil. Penyimpanan laporan belum tersedia pada mode pratinjau.')->assertSet('context.status', 'DRAFT');
    }

    public function test_submit_distinguishes_blank_zero_reason_and_cumulative_limit(): void
    {
        $form = Livewire::test(PelaksanaWeeklyForm::class, ['context' => PelaksanaDemoData::context()]);
        $form->call('previewAction', 'submit')->assertHasErrors(['increments.DEMO-I1']);
        $form->set('increments.DEMO-I1', '0')->set('increments.DEMO-I2', '51')->set('increments.DEMO-I3', '-1')
            ->call('previewAction', 'submit')->assertHasErrors(['reasons.DEMO-I1', 'increments.DEMO-I2', 'increments.DEMO-I3']);
        $form->set('reasons.DEMO-I1', 'Tidak ada kegiatan minggu ini.')->set('increments.DEMO-I2', '10')->set('increments.DEMO-I3', '0')
            ->call('previewAction', 'submit')->assertHasNoErrors()->assertSee('Validasi berhasil. Penyimpanan laporan belum tersedia pada mode pratinjau.');
    }

    public function test_demo_partial_item_accepts_thirty_percent_and_recalculates_live(): void
    {
        $context = PelaksanaDemoData::context();
        $this->assertSame('60', $context['items'][0]['target']);
        $this->assertSame('20', $context['items'][0]['previous']);
        $context['items'] = [$context['items'][0]];
        $form = Livewire::test(PelaksanaWeeklyForm::class, ['context' => $context])
            ->assertSee('Maksimal 80,00%.')->set('increments.DEMO-I1', '30')
            ->assertSee('50,00%')->assertSee('-10,00 pp')
            ->call('previewAction', 'submit')->assertHasNoErrors()->assertSee('Validasi berhasil. Penyimpanan laporan belum tersedia pada mode pratinjau.');
        $document = new \DOMDocument;
        @$document->loadHTML($form->html());
        $input = (new \DOMXPath($document))->query('//input[@id="increment-DEMO-I1"]')->item(0);
        $this->assertFalse($input->hasAttribute('disabled'));
        $this->assertSame('80.0000', $input->getAttribute('max'));
        $form->set('increments.DEMO-I1', '80')->assertSee('100,00%')
            ->call('previewAction', 'submit')->assertHasNoErrors()
            ->set('increments.DEMO-I1', '80.01')->assertSee('Belum lengkap')
            ->call('previewAction', 'submit')->assertHasErrors(['increments.DEMO-I1'])
            ->set('increments.DEMO-I1', '-1')->call('previewAction', 'submit')
            ->assertHasErrors(['increments.DEMO-I1']);
    }

    public function test_completed_item_is_disabled_and_keeps_a_zero_progress_reason(): void
    {
        $context = PelaksanaDemoData::context();
        $context['items'] = [$context['items'][2]];
        $form = Livewire::test(PelaksanaWeeklyForm::class, ['context' => $context])
            ->assertSee('Pekerjaan Selesai')->assertSee('Pekerjaan sudah mencapai 100%.')
            ->assertSee('100,00%')->assertSee('0,00 pp')
            ->assertSet('increments.DEMO-I3', '0')
            ->assertSet('reasons.DEMO-I3', 'Pekerjaan sudah mencapai 100% pada laporan sebelumnya.')
            ->call('previewAction', 'submit')->assertHasNoErrors()->assertSee('Validasi berhasil. Penyimpanan laporan belum tersedia pada mode pratinjau.');
        $document = new \DOMDocument;
        @$document->loadHTML($form->html());
        $input = (new \DOMXPath($document))->query('//input[@id="increment-DEMO-I3"]')->item(0);
        $this->assertTrue($input->hasAttribute('disabled'));
        $this->assertSame('0.0000', $input->getAttribute('max'));
        $form->set('increments.DEMO-I3', '30')->call('previewAction', 'submit')
            ->assertHasErrors(['increments.DEMO-I3'])
            ->set('increments.DEMO-I3', '0')->set('reasons.DEMO-I3', '')
            ->call('previewAction', 'submit')->assertHasNoErrors()->assertSet('reasons.DEMO-I3', '');
    }

    public function test_draft_can_be_incomplete_but_never_claims_persistence(): void
    {
        Livewire::test(PelaksanaWeeklyForm::class, ['context' => PelaksanaDemoData::context()])
            ->call('previewAction', 'draft')->assertHasNoErrors()->assertSee('Validasi berhasil. Penyimpanan laporan belum tersedia pada mode pratinjau.')
            ->assertSet('context.status', 'DRAFT');
    }

    public function test_invalid_period_is_rejected(): void
    {
        Livewire::test(PelaksanaWeeklyForm::class, ['context' => PelaksanaDemoData::context()])
            ->set('end', '2026-06-01')->call('previewAction', 'draft')->assertHasErrors(['start'])->assertSee('Tanggal mulai tidak boleh setelah tanggal akhir.');
    }

    public function test_pending_and_approved_are_read_only_even_with_direct_action_calls(): void
    {
        foreach (['PENDING_APPROVAL', 'APPROVED'] as $status) {
            $context = PelaksanaDemoData::context();
            $context['status'] = $status;
            Livewire::test(PelaksanaWeeklyForm::class, ['context' => $context])
                ->call('previewAction', 'submit')->assertSee('hanya dapat dibaca')->assertSet('context.status', $status);
        }
    }

    public function test_rendered_calculations_match_backend_and_keep_exact_summary_precision(): void
    {
        $context = PelaksanaDemoData::context();
        $context['items'] = [array_replace($context['items'][0], ['previous' => '0.1', 'target' => '0.4', 'weight' => '33.3333'])];
        $expected = WeeklyProgressCalculator::calculate('0.1', '0.2', '0.4', '33.3333');
        Livewire::test(PelaksanaWeeklyForm::class, ['context' => $context])
            ->set('increments.DEMO-I1', '0.2')
            ->assertViewHas('rows', fn ($rows) => $rows[0]['cumulative'] === $expected['cumulative_progress'] && $rows[0]['deviation'] === $expected['deviation'])
            ->assertViewHas('totalDisplay', '0,0999999')->assertViewHas('deviationDisplay', '-0,0333333')
            ->assertSet('increments.DEMO-I1', '0.2')->assertSee('step="0.0001"', false)
            ->call('previewAction', 'submit')->assertHasNoErrors()
            ->assertSee('Validasi berhasil. Penyimpanan laporan belum tersedia pada mode pratinjau.')
            ->assertSet('context', $context);
    }

    public function test_partial_progress_reaching_one_hundred_does_not_lock_the_input_as_previously_finished(): void
    {
        $context = PelaksanaDemoData::context();
        $context['items'] = [array_replace($context['items'][0], ['previous' => '70'])];
        $form = Livewire::test(PelaksanaWeeklyForm::class, ['context' => $context])
            ->assertSee('Maksimal 30,00%.')->set('increments.DEMO-I1', '31')
            ->call('previewAction', 'submit')->assertHasErrors(['increments.DEMO-I1'])
            ->assertSet('increments.DEMO-I1', '31')
            ->set('increments.DEMO-I1', '30')->call('previewAction', 'submit')->assertHasNoErrors()
            ->assertViewHas('rows', fn ($rows) => !$rows[0]['previously_finished'] && $rows[0]['completed_this_week'] && $rows[0]['cumulative'] === '100.0000')
            ->assertDontSee('Pekerjaan Selesai');
        $document = new \DOMDocument;
        @$document->loadHTML($form->html());
        $this->assertFalse((new \DOMXPath($document))->query('//input[@id="increment-DEMO-I1"]')->item(0)->hasAttribute('disabled'));
    }

    public function test_second_and_third_item_errors_are_mapped_to_the_correct_fields_without_losing_input(): void
    {
        Livewire::test(PelaksanaWeeklyForm::class, ['context' => PelaksanaDemoData::context()])
            ->set('increments.DEMO-I1', '30')->set('increments.DEMO-I2', '51')->set('increments.DEMO-I3', '1')
            ->call('previewAction', 'submit')
            ->assertHasErrors(['increments.DEMO-I2', 'increments.DEMO-I3'])
            ->assertHasNoErrors(['increments.DEMO-I1'])
            ->assertSee('id="error-increment-DEMO-I2"', false)->assertSee('id="error-increment-DEMO-I3"', false)
            ->assertSee('Tambahan progres melebihi sisa yang tersedia')
            ->assertSet('increments.DEMO-I1', '30')->assertSet('increments.DEMO-I2', '51')
            ->assertSet('increments.DEMO-I3', '1')->assertSet('context.status', 'DRAFT');
    }

    public function test_unicode_zero_reason_maps_to_second_item_and_completed_manual_note_is_preserved(): void
    {
        $note = " \u{00A0}Selesai, menunggu pemeriksaan.\n";
        $context = PelaksanaDemoData::context();
        $form = Livewire::test(PelaksanaWeeklyForm::class, ['context' => $context])
            ->set('increments.DEMO-I1', '30')->set('increments.DEMO-I2', '0')
            ->set('reasons.DEMO-I2', "\u{00A0}")->set('reasons.DEMO-I3', $note)
            ->call('previewAction', 'submit')->assertHasErrors(['reasons.DEMO-I2'])
            ->assertHasNoErrors(['reasons.DEMO-I3'])
            ->assertSee('id="error-reason-DEMO-I2"', false)
            ->assertSet('reasons.DEMO-I2', "\u{00A0}")->assertSet('reasons.DEMO-I3', $note)
            ->set('reasons.DEMO-I2', 'Belum ada kegiatan minggu ini.')
            ->call('previewAction', 'submit')->assertHasNoErrors()->assertSet('reasons.DEMO-I3', $note);
        $form->set('reasons.DEMO-I3', '')->call('previewAction', 'submit')->assertHasNoErrors()
            ->assertSet('reasons.DEMO-I3', '')->assertSee('Pekerjaan Selesai');
        $this->assertSame('Pekerjaan sudah mencapai 100% pada laporan sebelumnya.', WeeklyProgressValidator::COMPLETED_REASON);
    }

    public function test_invalid_numeric_formats_are_rejected_and_preserved(): void
    {
        $context = PelaksanaDemoData::context();
        $context['items'] = [$context['items'][0]];
        $form = Livewire::test(PelaksanaWeeklyForm::class, ['context' => $context]);
        foreach (['abc', '20,5', '1e2', '0.00001', ' 20 ', '100.0001', '-1', 20.5] as $input) {
            $form->set('increments.DEMO-I1', $input)->call('previewAction', 'submit')
                ->assertHasErrors(['increments.DEMO-I1'])->assertSet('increments.DEMO-I1', $input)
                ->assertViewHas('complete', false)->assertSet('notice', '');
        }
        $form->set('increments.DEMO-I1', '0.0001')->call('previewAction', 'submit')
            ->assertHasNoErrors()->assertSee('20,0001%')->assertSet('increments.DEMO-I1', '0.0001');
    }

    public function test_draft_blank_is_not_zero_and_submit_uses_backend_required_errors(): void
    {
        $context = PelaksanaDemoData::context();
        $context['items'] = [$context['items'][0]];
        Livewire::test(PelaksanaWeeklyForm::class, ['context' => $context])
            ->set('increments.DEMO-I1', null)->call('previewAction', 'draft')->assertHasNoErrors()
            ->assertSet('increments.DEMO-I1', null)->assertViewHas('rows', fn ($rows) => $rows[0]['cumulative'] === null)
            ->call('previewAction', 'submit')->assertHasErrors(['increments.DEMO-I1'])
            ->assertSet('increments.DEMO-I1', null)->assertSee('nilai kosong bukan 0%')
            ->set('increments.DEMO-I1', '0')->call('previewAction', 'submit')->assertHasErrors(['reasons.DEMO-I1'])
            ->set('reasons.DEMO-I1', 'Tidak ada pekerjaan minggu ini.')->call('previewAction', 'submit')->assertHasNoErrors();
    }

    public function test_invalid_source_float_is_explicit_and_never_converted_or_used_as_fallback(): void
    {
        $context = PelaksanaDemoData::context();
        $context['items'] = [array_replace($context['items'][0], ['previous' => 20.0])];
        Livewire::test(PelaksanaWeeklyForm::class, ['context' => $context])
            ->assertSee('Data sumber item belum valid')->set('increments.DEMO-I1', '30')
            ->call('previewAction', 'submit')->assertHasErrors(['context'])
            ->assertSee('Float dan format lain tidak dikonversi otomatis.')
            ->assertViewHas('complete', false)->assertSet('context', $context)->assertSet('increments.DEMO-I1', '30');
    }

    public function test_period_and_week_errors_remain_on_their_fields(): void
    {
        $context = PelaksanaDemoData::context();
        $context['items'] = [$context['items'][0]];
        Livewire::test(PelaksanaWeeklyForm::class, ['context' => $context])
            ->set('increments.DEMO-I1', '30')->set('start', '2026-02-30')->set('end', '')
            ->call('previewAction', 'submit')->assertHasErrors(['start', 'end'])
            ->assertSet('start', '2026-02-30')->assertSet('end', '')
            ->set('start', '2026-06-29')->set('end', '2026-06-29')->set('week', 0)
            ->call('previewAction', 'submit')->assertHasErrors(['week'])->assertHasNoErrors(['start', 'end'])
            ->set('week', 1)->call('previewAction', 'submit')->assertHasNoErrors();
    }

    public function test_rejected_report_waits_for_a_new_revision_without_changing_status(): void
    {
        $context = PelaksanaDemoData::context();
        $context['status'] = 'REJECTED';
        Livewire::test(PelaksanaWeeklyForm::class, ['context' => $context])
            ->call('previewAction', 'submit')->assertSee('sampai revisi baru tersedia')
            ->assertSet('context.status', 'REJECTED')->assertViewHas('editable', false);
    }

    public function test_locked_baseline_cannot_be_replaced_by_browser_state(): void
    {
        $this->expectException(\Livewire\Features\SupportLockedProperties\CannotUpdateLockedPropertyException::class);
        Livewire::test(PelaksanaWeeklyForm::class, ['context' => PelaksanaDemoData::context()])
            ->set('context.items.0.weight', '100');
    }
}
