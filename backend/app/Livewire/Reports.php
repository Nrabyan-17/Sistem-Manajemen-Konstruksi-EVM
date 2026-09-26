<?php

namespace App\Livewire;

use App\Support\ProjectMockData;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Reports - EVM Dashboard')]
class Reports extends Component
{
    public $selectedProject = '';
    public $statusFilter = 'all';
    public $pmFilter = 'all';
    public $reportType = 'evm'; // evm, financial, schedule

    // Export simulation state
    public $exporting = false;
    public $toastMessage = '';
    public $showToast = false;

    // Pop form / project detail modal state
    public bool $showProjectModal = false;
    public ?array $selectedProjectData = null;

    public function openProjectModal(string $projectId)
    {
        $allProjects = ProjectMockData::all();
        foreach ($allProjects as $p) {
            if ($p['project_id'] === $projectId) {
                $bac = (float) $p['contract_value'];
                $progress = (float) $p['progress'];
                $cpi = (float) ($p['cpi'] ?? 1);
                $spi = (float) ($p['spi'] ?? 1);
                $bcwp = $bac * ($progress / 100);
                $acwp = $cpi > 0 ? $bcwp / $cpi : $bcwp;
                $bcws = $spi > 0 ? $bcwp / $spi : $bcwp;
                $cv = $bcwp - $acwp;
                $sv = $bcwp - $bcws;
                $eac = (float) ($p['eac'] ?? ($cpi > 0 ? $bac / $cpi : $bac));
                $vac = $bac - $eac;

                $plannedProgress = $bac > 0 ? min(100, round(($bcws / $bac) * 100, 1)) : 0;

                // Evaluasi status
                $costStatus = $cpi >= 1.0 ? 'Under Budget' : 'Over Budget';
                $costDesc = $cpi >= 1.0 ? 'Pengeluaran biaya lebih hemat dari nilai progres' : 'Pengeluaran biaya aktual melebihi nilai progres terpasang';
                
                $schedStatus = $spi >= 1.0 ? 'On Schedule' : 'Behind Schedule';
                $schedDesc = $spi >= 1.0 ? 'Progres fisik di lapangan sesuai/lebih cepat dari rencana' : 'Progres fisik di lapangan mengalami keterlambatan';

                if ($cpi < 1.0 && $spi < 1.0) {
                    $diagnosticType = 'critical';
                    $diagnosticTitle = 'Kondisi Kritis (Over Budget & Terlambat)';
                    $diagnosticMessage = 'Proyek mengalami keterlambatan progres sekaligus pembengkakan biaya riil. Diperlukan mitigasi percepatan dan audit pengeluaran.';
                } elseif ($cpi < 1.0) {
                    $diagnosticType = 'warning';
                    $diagnosticTitle = 'Peringatan Biaya (Over Budget)';
                    $diagnosticMessage = 'Progres fisik berjalan sesuai target, namun pengeluaran aktual melebihi anggaran rencana.';
                } elseif ($spi < 1.0) {
                    $diagnosticType = 'warning';
                    $diagnosticTitle = 'Peringatan Jadwal (Terlambat)';
                    $diagnosticMessage = 'Biaya proyek terkendali dengan baik, namun progres fisik di lapangan tertinggal dari target rencana mingguan.';
                } else {
                    $diagnosticType = 'success';
                    $diagnosticTitle = 'Performa Sangat Baik (On Track)';
                    $diagnosticMessage = 'Proyek berjalan tepat waktu dengan efisiensi biaya yang sangat baik di bawah pagu anggaran.';
                }

                $this->selectedProjectData = array_merge($p, [
                    'bac' => $bac,
                    'bcwp' => $bcwp,
                    'acwp' => $acwp,
                    'bcws' => $bcws,
                    'cv' => $cv,
                    'sv' => $sv,
                    'eac' => $eac,
                    'vac' => $vac,
                    'planned_progress' => $plannedProgress,
                    'cost_status' => $costStatus,
                    'cost_desc' => $costDesc,
                    'sched_status' => $schedStatus,
                    'sched_desc' => $schedDesc,
                    'diagnostic_type' => $diagnosticType,
                    'diagnostic_title' => $diagnosticTitle,
                    'diagnostic_message' => $diagnosticMessage,
                ]);
                $this->showProjectModal = true;
                break;
            }
        }
    }

    public function closeProjectModal()
    {
        $this->showProjectModal = false;
        $this->selectedProjectData = null;
    }

    public function handleClearFilters()
    {
        $this->selectedProject = '';
        $this->statusFilter = 'all';
        $this->pmFilter = 'all';
        $this->reportType = 'evm';
    }

    public function triggerExport(string $format)
    {
        $this->exporting = true;
        
        // Simulate download
        $this->toastMessage = "Report exported successfully as " . strtoupper($format) . "!";
        $this->showToast = true;
        $this->exporting = false;
    }

    public function hideToast()
    {
        $this->showToast = false;
    }

    public function render()
    {
        $allProjects = ProjectMockData::all();

        // Apply filters
        $filtered = array_filter($allProjects, function ($p) {
            $projectSearch = strtolower(trim((string) $this->selectedProject));
            $matchesProject = $projectSearch === '' || $projectSearch === 'all'
                || str_contains(strtolower($p['project_id']), $projectSearch)
                || str_contains(strtolower($p['project_name']), $projectSearch);
            $matchesStatus = $this->statusFilter === 'all' || $p['status'] === $this->statusFilter;
            $matchesPM = $this->pmFilter === 'all' || $p['project_manager'] === $this->pmFilter;

            return $matchesProject && $matchesStatus && $matchesPM;
        });

        // Compute aggregate metrics
        $totalBac = 0;
        $totalBcwp = 0;
        $totalAcwp = 0;
        $totalBcws = 0;
        $totalEac = 0;
        $projectCount = count($filtered);

        $projectsData = [];

        foreach ($filtered as $p) {
            $bac = (float) $p['contract_value'];
            $progress = (float) $p['progress'];
            
            // EVM Calculations
            $bcwp = $bac * ($progress / 100);
            $acwp = $bcwp / (float) $p['cpi'];
            $bcws = $bcwp / (float) $p['spi'];
            $cv = $bcwp - $acwp;
            $sv = $bcwp - $bcws;
            $eac = (float) $p['eac'];
            $vac = $bac - $eac;

            $totalBac += $bac;
            $totalBcwp += $bcwp;
            $totalAcwp += $acwp;
            $totalBcws += $bcws;
            $totalEac += $eac;

            $projectsData[] = array_merge($p, [
                'bac' => $bac,
                'bcwp' => $bcwp,
                'acwp' => $acwp,
                'bcws' => $bcws,
                'cv' => $cv,
                'sv' => $sv,
                'vac' => $vac
            ]);
        }

        // Aggregate EVM values
        $aggCv = $totalBcwp - $totalAcwp;
        $aggSv = $totalBcwp - $totalBcws;
        $aggCpi = $totalAcwp > 0 ? ($totalBcwp / $totalAcwp) : 1.0;
        $aggSpi = $totalBcws > 0 ? ($totalBcwp / $totalBcws) : 1.0;
        $aggVac = $totalBac - $totalEac;

        return view('livewire.reports', [
            'projects' => $projectsData,
            'projectList' => $allProjects, // for the project dropdown
            'projectCount' => $projectCount,
            'totalBac' => $totalBac,
            'totalBcwp' => $totalBcwp,
            'totalAcwp' => $totalAcwp,
            'totalBcws' => $totalBcws,
            'totalEac' => $totalEac,
            'aggCv' => $aggCv,
            'aggSv' => $aggSv,
            'aggCpi' => $aggCpi,
            'aggSpi' => $aggSpi,
            'aggVac' => $aggVac,
        ])->layout('components.layouts.app');
    }
}
