<?php

namespace App\Livewire;

use App\Support\ProjectMockData;
use Livewire\Component;

class ProjectDetail extends Component
{
    public array $project;
    public array $history;
    public string $activeTab = 'overview';

    // EVM derived values — same formulas as Projects list, computed once in mount()
    public float $bac;
    public float $bcwp;
    public float $acwp;
    public float $bcws;
    public float $cv;
    public float $sv;
    public float $vac;

    // Data for the S-Curve chart (passed to Chart.js via @json in the view)
    public array $sCurveWeeks;
    public array $sCurveBcws;
    public array $sCurveBcwp;
    public array $sCurveAcwp;

    public array $milestones;

    public function mount(string $projectId)
    {
        $project = ProjectMockData::find($projectId);

        abort_if(!$project, 404, "Project {$projectId} not found");

        $this->project = $project;
        $this->history = ProjectMockData::historyFor($projectId);

        // ===== EVM formulas =====
        // BCWP (Earned Value) = Contract Value (BAC) x Progress%
        // ACWP = BCWP / CPI      (from CPI = BCWP / ACWP)
        // BCWS = BCWP / SPI      (from SPI = BCWP / BCWS)
        $this->bac  = (float) $project['contract_value'];
        $this->bcwp = $this->bac * ($project['progress'] / 100);
        $this->acwp = $this->bcwp / $project['cpi'];
        $this->bcws = $this->bcwp / $project['spi'];
        $this->cv   = $this->bcwp - $this->acwp;
        $this->sv   = $this->bcwp - $this->bcws;
        $this->vac  = $this->bac - $project['eac'];

        // ===== S-Curve series (12 fortnightly checkpoints, normalized S-shape) =====
        $shape = [0.019, 0.043, 0.081, 0.138, 0.219, 0.333, 0.467, 0.610, 0.743, 0.848, 0.933, 1.0];
        $cutoffIndex = (int) round(($project['progress'] / 100) * 11);

        $this->sCurveWeeks = array_map(fn ($i) => 'W' . (($i + 1) * 2), array_keys($shape));
        $this->sCurveBcws  = array_map(fn ($f) => round($f * $this->bcws / 1e9, 2), $shape);
        $this->sCurveBcwp  = array_map(
            fn ($f, $i) => $i <= $cutoffIndex ? round($f * $this->bcwp / 1e9, 2) : null,
            $shape, array_keys($shape)
        );
        $this->sCurveAcwp = array_map(
            fn ($f, $i) => $i <= $cutoffIndex ? round($f * $this->acwp / 1e9, 2) : null,
            $shape, array_keys($shape)
        );

        // ===== Milestones scaled to current progress =====
        $template = [
            ['name' => 'Mobilization & Site Setup', 'at' => 5],
            ['name' => 'Foundation Works', 'at' => 25],
            ['name' => 'Structural Works', 'at' => 55],
            ['name' => 'MEP Installation', 'at' => 75],
            ['name' => 'Finishing Works', 'at' => 90],
            ['name' => 'Handover (BAST)', 'at' => 100],
        ];
        $this->milestones = array_map(function ($m) use ($project) {
            $done = $project['progress'] >= $m['at'];
            $current = !$done && $project['progress'] >= ($m['at'] - 20);
            $m['status'] = $done ? 'Completed' : ($current ? 'In Progress' : 'Upcoming');
            return $m;
        }, $template);
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.project-detail')->layout('components.layouts.app');
    }
}
