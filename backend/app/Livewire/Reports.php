<?php

namespace App\Livewire;

use App\Support\ProjectMockData;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Reports - EVM Dashboard')]
class Reports extends Component
{
    public $selectedProject = 'all';
    public $statusFilter = 'all';
    public $pmFilter = 'all';
    public $reportType = 'evm'; // evm, financial, schedule

    // Export simulation state
    public $exporting = false;
    public $toastMessage = '';
    public $showToast = false;

    public function handleClearFilters()
    {
        $this->selectedProject = 'all';
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
            $matchesProject = $this->selectedProject === 'all' || $p['project_id'] === $this->selectedProject;
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
