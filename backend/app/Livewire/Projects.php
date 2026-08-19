<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Projects - EVM Dashboard')]
class Projects extends Component
{
    // Projects data
    public $projects = [];
    public $projectHistory = [];
    public $selectedHistoryId = null;

    // Filters State
    public $search = '';
    public $statusFilter = 'all';
    public $pmFilter = 'all';
    public $periodFilter = 'all';

    // UI Configuration
    public $viewMode = 'cards';
    public $currentPage = 1;
    public $pageSize = 5;
    public $sortKey = null;
    public $sortDir = 1; // 1 = Asc, -1 = Desc

    // New Project Form State
    public $newProjName = '';
    public $newProjId = '';
    public $newSpk = '';
    public $newClient = '';
    public $newPm = '';
    public $newValue = '';
    public $newStartDate = '';
    public $newBastDate = '';
    public $newStatus = 'ON TRACK';
    public $newLocation = 'Jakarta';

    public function mount()
    {
        // Initial projects seed
        $this->projects = \App\Support\ProjectMockData::all();

        // Initial project history
        $this->projectHistory = \App\Support\ProjectMockData::history();
    }

    public function showHistory($id)
    {
        $this->selectedHistoryId = $id;
        $this->dispatch('open-history-modal');
    }

    public function handleSort($key)
    {
        if ($this->sortKey === $key) {
            $this->sortDir = $this->sortDir * -1;
        } else {
            $this->sortKey = $key;
            $this->sortDir = 1;
        }
    }

    public function handleClearFilters()
    {
        $this->search = '';
        $this->statusFilter = 'all';
        $this->pmFilter = 'all';
        $this->periodFilter = 'all';
        $this->currentPage = 1;
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function createProject()
    {
        $this->validate([
            'newProjName' => 'required',
            'newProjId' => 'required',
            'newSpk' => 'required',
            'newClient' => 'required',
            'newPm' => 'required',
            'newValue' => 'required|numeric',
            'newStartDate' => 'required|date',
            'newBastDate' => 'required|date',
        ]);

        // Calculate remaining days
        $bast = new \DateTime($this->newBastDate);
        $today = new \DateTime();
        $interval = $today->diff($bast);
        $remainingVal = $bast < $today ? 0 : (int)$interval->format('%a');

        $newProjectObj = [
            'project_id' => $this->newProjId,
            'project_name' => $this->newProjName,
            'spk_number' => $this->newSpk,
            'client' => $this->newClient,
            'project_manager' => $this->newPm,
            'location' => $this->newLocation,
            'contract_value' => floatval($this->newValue),
            'start_date' => $this->newStartDate,
            'bast_date' => $this->newBastDate,
            'remaining_days' => $remainingVal,
            'progress' => 0,
            'cpi' => 1.00,
            'spi' => 1.00,
            'eac' => floatval($this->newValue),
            'status' => $this->newStatus
        ];

        $this->projects[] = $newProjectObj;

        // Initialize history log
        $this->projectHistory[$this->newProjId] = [
            [
                'date' => date('Y-m-d'),
                'user' => 'System',
                'action' => 'Project Created',
                'old' => '—',
                'new' => "{$this->newProjId} initialized"
            ]
        ];

        // Reset Form Fields
        $this->reset([
            'newProjName', 'newProjId', 'newSpk', 'newClient', 'newPm',
            'newValue', 'newStartDate', 'newBastDate', 'newStatus', 'newLocation'
        ]);

        // Dispatch browser event to show toast and close modal
        $this->dispatch('project-created', message: 'Project created successfully!');
    }

    public function render()
    {
        // 1. Filtering
        $filtered = array_filter($this->projects, function($p) {
            $q = strtolower(trim($this->search));
            $matchesSearch = empty($q) || 
                str_contains(strtolower($p['project_name']), $q) ||
                str_contains(strtolower($p['location']), $q) ||
                str_contains(strtolower($p['project_manager']), $q) ||
                str_contains(strtolower($p['spk_number']), $q) ||
                str_contains(strtolower($p['client']), $q);

            $matchesStatus = $this->statusFilter === 'all' || $p['status'] === $this->statusFilter;
            $matchesPM = $this->pmFilter === 'all' || $p['project_manager'] === $this->pmFilter;

            $matchesPeriod = true;
            if ($this->periodFilter === 'month') {
                $pDate = new \DateTime($p['start_date']);
                $now = new \DateTime();
                $matchesPeriod = $pDate->format('m-Y') === $now->format('m-Y');
            } elseif ($this->periodFilter === 'quarter') {
                $pDate = new \DateTime($p['start_date']);
                $now = new \DateTime();
                $pQuarter = floor(($pDate->format('n') - 1) / 3);
                $nowQuarter = floor(($now->format('n') - 1) / 3);
                $matchesPeriod = $pQuarter === $nowQuarter && $pDate->format('Y') === $now->format('Y');
            } elseif ($this->periodFilter === 'year') {
                $pDate = new \DateTime($p['start_date']);
                $now = new \DateTime();
                $matchesPeriod = $pDate->format('Y') === $now->format('Y');
            }

            return $matchesSearch && $matchesStatus && $matchesPM && $matchesPeriod;
        });

        // 2. Sorting
        if ($this->sortKey) {
            usort($filtered, function($a, $b) {
                $av = $a[$this->sortKey];
                $bv = $b[$this->sortKey];

                if (is_string($av)) {
                    $cmp = strcmp($av, $bv);
                } else {
                    $cmp = $av <=> $bv;
                }

                return $cmp * $this->sortDir;
            });
        }

        // 3. Pagination (only used in table view, but good to calculate)
        $totalItems = count($filtered);
        $totalPages = max(1, ceil($totalItems / $this->pageSize));
        
        // Ensure currentPage doesn't overshoot
        if ($this->currentPage > $totalPages) {
            $this->currentPage = $totalPages;
        }

        $paginated = array_slice($filtered, ($this->currentPage - 1) * $this->pageSize, $this->pageSize);

        // Calculate KPIs dynamically
        $kpiTotal = count($this->projects);
        $kpiActive = count(array_filter($this->projects, fn($p) => $p['status'] !== 'COMPLETED'));
        $kpiOnTrack = count(array_filter($this->projects, fn($p) => $p['status'] === 'ON TRACK'));
        $kpiAtRisk = count(array_filter($this->projects, fn($p) => $p['status'] === 'AT RISK'));
        $kpiCritical = count(array_filter($this->projects, fn($p) => $p['status'] === 'CRITICAL'));
        $kpiTotalContract = array_reduce($this->projects, fn($acc, $p) => $acc + $p['contract_value'], 0);

        return view('livewire.projects', [
            'filteredProjects' => $filtered,
            'paginatedProjects' => $paginated,
            'totalPages' => $totalPages,
            'kpiTotal' => $kpiTotal,
            'kpiActive' => $kpiActive,
            'kpiOnTrack' => $kpiOnTrack,
            'kpiAtRisk' => $kpiAtRisk,
            'kpiCritical' => $kpiCritical,
            'kpiTotalContract' => $kpiTotalContract,
        ])->layout('components.layouts.app');
    }
}
