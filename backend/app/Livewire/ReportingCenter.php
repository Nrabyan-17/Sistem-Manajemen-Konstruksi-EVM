<?php

namespace App\Livewire;

use App\Support\ProjectMockData;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Reporting Center - EVM Dashboard')]
class ReportingCenter extends Component
{
    // ── Generate Report modal state ─────────────────────────────
    public bool $showGenerateModal = false;
    public string $modalReportType = 'EVM Performance';
    public string $modalProject = 'all';
    public string $modalPeriod = '';

    // ── Toast notification state (same pattern as Reports.php) ──
    public string $toastMessage = '';
    public bool $showToast = false;

    // ── Recent Generated Reports ─────────────────────────────────
    // TODO(backend): replace this seeded array with real data,
    // e.g. from a `reports` table via a ReportRepository/Model.
    // Keep the same array shape ('id','name','project','date','by','icon_color')
    // so the Blade view does not need to change when wired to real data.
    public array $recentReports = [];

    protected array $reportTypeCards = [
        [
            'key' => 'weekly_progress',
            'label' => 'Weekly Progress',
            'description' => 'Detailed tracking of work items and schedule variance.',
            'icon_color' => 'blue',
        ],
        [
            'key' => 'financial',
            'label' => 'Financial Report',
            'description' => 'Budget utilization, actual costs, and cash flow analysis.',
            'icon_color' => 'emerald',
        ],
        [
            'key' => 'profit_loss',
            'label' => 'Profit and Loss',
            'description' => 'Project margin analysis and revenue projections.',
            'icon_color' => 'violet',
        ],
        [
            'key' => 'evm_performance',
            'label' => 'EVM Performance',
            'description' => 'CPI, SPI metrics with predictive forecasting data.',
            'icon_color' => 'orange',
        ],
    ];

    public function mount(): void
    {
        // TODO(backend): seed data below is placeholder only, matches the
        // approved design reference. Swap for a real query when the
        // `reports` audit-log table/endpoint is available.
        $this->recentReports = [
            [
                'id' => 'REP-104',
                'name' => 'Monthly EVM Performance',
                'project' => 'Grand Horizon Tower',
                'date' => 'June 01, 2026',
                'by' => 'Alex Thompson',
                'icon_color' => 'rose',
            ],
            [
                'id' => 'REP-103',
                'name' => 'Weekly Progress Report',
                'project' => 'Coastal Bridge',
                'date' => 'May 28, 2026',
                'by' => 'Siti Aminah',
                'icon_color' => 'emerald',
            ],
            [
                'id' => 'REP-102',
                'name' => 'Financial Audit Report',
                'project' => 'Metro Line Ext.',
                'date' => 'May 24, 2026',
                'by' => 'Budi Santoso',
                'icon_color' => 'rose',
            ],
            [
                'id' => 'REP-101',
                'name' => 'Annual Safety Review',
                'project' => 'Industrial Park II',
                'date' => 'May 20, 2026',
                'by' => 'Rina Putri',
                'icon_color' => 'rose',
            ],
        ];
    }

    public function openGenerateModal(string $reportLabel): void
    {
        $this->modalReportType = $reportLabel;
        $this->modalProject = 'all';
        $this->modalPeriod = '';
        $this->showGenerateModal = true;
    }

    public function closeGenerateModal(): void
    {
        $this->showGenerateModal = false;
    }

    /**
     * TODO(backend): this only pushes a placeholder row into the
     * in-memory $recentReports list for preview purposes. Replace with
     * a real job dispatch / API call that actually generates the
     * document, then refresh $recentReports from the database.
     */
    public function generateReport(): void
    {
        $nextNumber = 105 + count($this->recentReports) - 4;

        array_unshift($this->recentReports, [
            'id' => 'REP-' . $nextNumber,
            'name' => $this->modalReportType,
            'project' => $this->modalProject === 'all'
                ? 'All Projects'
                : $this->modalProject,
            'date' => now()->format('F d, Y'),
            'by' => 'Alex Thompson', // TODO(backend): use authenticated user
            'icon_color' => 'blue',
        ]);

        $this->showGenerateModal = false;
        $this->flashToast("{$this->modalReportType} report generated.");
    }

    public function exportPdf(): void
    {
        // TODO(backend): hook up real PDF export endpoint/job.
        $this->flashToast('Export PDF triggered — pending backend integration.');
    }

    public function exportExcel(): void
    {
        // TODO(backend): hook up real Excel export endpoint/job.
        $this->flashToast('Export Excel triggered — pending backend integration.');
    }

    public function downloadReport(string $id): void
    {
        // TODO(backend): stream/redirect to the actual generated file for $id.
        $this->flashToast("Download for {$id} — pending backend integration.");
    }

    private function flashToast(string $message): void
    {
        $this->toastMessage = $message;
        $this->showToast = true;
    }

    public function render()
    {
        return view('livewire.reporting-center', [
            'reportTypeCards' => $this->reportTypeCards,
            'projectList' => ProjectMockData::all(),
        ])->layout('components.layouts.app');
    }
}
