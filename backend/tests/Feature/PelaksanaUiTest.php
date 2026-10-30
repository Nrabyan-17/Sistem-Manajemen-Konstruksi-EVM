<?php

namespace Tests\Feature;

use App\Livewire\PelaksanaDashboard;
use App\Livewire\PelaksanaProjects;
use App\Livewire\PelaksanaReports;
use App\Livewire\PelaksanaWeeklyForm;
use App\Livewire\PelaksanaWorkspace;
use App\Support\PelaksanaDemoData;
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
        // Any accidental database query must fail; this suite is UI-only.
        \Illuminate\Support\Facades\DB::listen(fn () => $this->fail('UI must not query a database.'));
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
            ->assertSee('Belum dikirim')->assertSet('context.status', 'DRAFT');
    }

    public function test_submit_distinguishes_blank_zero_reason_and_cumulative_limit(): void
    {
        $form = Livewire::test(PelaksanaWeeklyForm::class, ['context' => PelaksanaDemoData::context()]);
        $form->call('previewAction', 'submit')->assertHasErrors(['increments.DEMO-I1' => 'required']);
        $form->set('increments.DEMO-I1', '0')->set('increments.DEMO-I2', '51')->set('increments.DEMO-I3', '-1')
            ->call('previewAction', 'submit')->assertHasErrors(['reasons.DEMO-I1' => 'required', 'increments.DEMO-I2' => 'max', 'increments.DEMO-I3' => 'min']);
        $form->set('reasons.DEMO-I1', 'Tidak ada kegiatan minggu ini.')->set('increments.DEMO-I2', '10')->set('increments.DEMO-I3', '0')
            ->call('previewAction', 'submit')->assertHasNoErrors()->assertSee('Belum dikirim');
    }

    public function test_demo_partial_item_accepts_thirty_percent_and_recalculates_live(): void
    {
        $context = PelaksanaDemoData::context();
        $this->assertSame(60.0, $context['items'][0]['target']);
        $this->assertSame(20.0, $context['items'][0]['previous']);
        $context['items'] = [$context['items'][0]];
        $form = Livewire::test(PelaksanaWeeklyForm::class, ['context' => $context])
            ->assertSee('Maksimal 80,00%.')->set('increments.DEMO-I1', '30')
            ->assertSee('50,00%')->assertSee('-10,00 pp')
            ->call('previewAction', 'submit')->assertHasNoErrors()->assertSee('Belum dikirim');
        $document = new \DOMDocument;
        @$document->loadHTML($form->html());
        $input = (new \DOMXPath($document))->query('//input[@id="increment-DEMO-I1"]')->item(0);
        $this->assertFalse($input->hasAttribute('disabled'));
        $this->assertSame('80', $input->getAttribute('max'));
        $form->set('increments.DEMO-I1', '80')->assertSee('100,00%')
            ->call('previewAction', 'submit')->assertHasNoErrors()
            ->set('increments.DEMO-I1', '80.01')->assertSee('Belum lengkap')
            ->call('previewAction', 'submit')->assertHasErrors(['increments.DEMO-I1' => 'max'])
            ->set('increments.DEMO-I1', '-1')->call('previewAction', 'submit')
            ->assertHasErrors(['increments.DEMO-I1' => 'min']);
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
            ->call('previewAction', 'submit')->assertHasNoErrors()->assertSee('Belum dikirim');
        $document = new \DOMDocument;
        @$document->loadHTML($form->html());
        $input = (new \DOMXPath($document))->query('//input[@id="increment-DEMO-I3"]')->item(0);
        $this->assertTrue($input->hasAttribute('disabled'));
        $this->assertSame('0', $input->getAttribute('max'));
        $form->set('increments.DEMO-I3', '30')->call('previewAction', 'submit')
            ->assertHasErrors(['increments.DEMO-I3' => 'max'])
            ->set('increments.DEMO-I3', '0')->set('reasons.DEMO-I3', '')
            ->call('previewAction', 'submit')->assertHasErrors(['reasons.DEMO-I3' => 'required']);
    }

    public function test_draft_can_be_incomplete_but_never_claims_persistence(): void
    {
        Livewire::test(PelaksanaWeeklyForm::class, ['context' => PelaksanaDemoData::context()])
            ->call('previewAction', 'draft')->assertHasNoErrors()->assertSee('Belum disimpan')
            ->assertSet('context.status', 'DRAFT');
    }

    public function test_invalid_period_is_rejected(): void
    {
        Livewire::test(PelaksanaWeeklyForm::class, ['context' => PelaksanaDemoData::context()])
            ->set('end', '2026-06-01')->call('previewAction', 'draft')->assertHasErrors(['end' => 'after_or_equal']);
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
}
