<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Contract Addendums - EVM Dashboard')]
class Addendum extends Component
{
    public $addendums = [];
    public $projects = [];

    // Filters
    public $search = '';
    public $statusFilter = 'all';
    public $projectFilter = 'all';

    // New Addendum Form State
    public $newProjectId = '';
    public $newTitle = '';
    public $newValue = '';
    public $newDate = '';
    public $newStatus = 'PENDING';
    public $newDescription = '';

    public function mount()
    {
        $this->projects = \App\Support\ProjectMockData::all();

        // Seed initial mock addendums
        $this->addendums = [
            [
                'addendum_id' => 'ADD-001-01',
                'project_id' => 'PRJ-001',
                'project_name' => 'Grand Horizon Tower',
                'title' => 'Spun Pile Volume Adjustment',
                'value' => 1500000000,
                'date' => '2026-07-20',
                'status' => 'APPROVED',
                'description' => 'Adjustment of foundation volume due to soil condition findings.'
            ],
            [
                'addendum_id' => 'ADD-001-02',
                'project_id' => 'PRJ-001',
                'project_name' => 'Grand Horizon Tower',
                'title' => 'Facade Glass Upgrade',
                'value' => 800000000,
                'date' => '2026-08-10',
                'status' => 'PENDING',
                'description' => 'Upgraded facade panels to meet higher energy efficiency ratings.'
            ],
            [
                'addendum_id' => 'ADD-002-01',
                'project_id' => 'PRJ-002',
                'project_name' => 'Industrial Park Phase II',
                'title' => 'Road Base Material Change',
                'value' => -500000000,
                'date' => '2026-05-12',
                'status' => 'APPROVED',
                'description' => 'Cost savings realized by switching to local supplier for subbase materials.'
            ],
            [
                'addendum_id' => 'ADD-004-01',
                'project_id' => 'PRJ-004',
                'project_name' => 'Metro Line Extension',
                'title' => 'Excavation Support Wall Expansion',
                'value' => 3200000000,
                'date' => '2026-07-15',
                'status' => 'PENDING',
                'description' => 'Expansion of retention wall to prevent soil displacement in Segment C.'
            ],
            [
                'addendum_id' => 'ADD-006-01',
                'project_id' => 'PRJ-006',
                'project_name' => 'Surabaya Smart City Flyover',
                'title' => 'Steel Girder Span Increase',
                'value' => 500000000,
                'date' => '2026-08-03',
                'status' => 'APPROVED',
                'description' => 'Lengthened girder spans to optimize support pier locations.'
            ],
        ];
    }

    public function createAddendum()
    {
        $this->validate([
            'newProjectId' => 'required',
            'newTitle' => 'required',
            'newValue' => 'required|numeric',
            'newDate' => 'required|date',
            'newStatus' => 'required',
        ]);

        $project = \App\Support\ProjectMockData::find($this->newProjectId);
        $projectName = $project ? $project['project_name'] : 'Unknown Project';

        // Generate a random ID
        $count = count(array_filter($this->addendums, fn($a) => $a['project_id'] === $this->newProjectId)) + 1;
        $suffix = str_pad($count, 2, '0', STR_PAD_LEFT);
        $addendumId = 'ADD-' . substr($this->newProjectId, 4) . '-' . $suffix;

        $newAddendum = [
            'addendum_id' => $addendumId,
            'project_id' => $this->newProjectId,
            'project_name' => $projectName,
            'title' => $this->newTitle,
            'value' => floatval($this->newValue),
            'date' => $this->newDate,
            'status' => $this->newStatus,
            'description' => $this->newDescription
        ];

        $this->addendums[] = $newAddendum;

        // Reset form
        $this->reset([
            'newProjectId', 'newTitle', 'newValue', 'newDate', 'newStatus', 'newDescription'
        ]);

        $this->dispatch('addendum-created', message: 'Addendum request submitted successfully!');
    }

    public function approveAddendum($id)
    {
        foreach ($this->addendums as &$addendum) {
            if ($addendum['addendum_id'] === $id) {
                $addendum['status'] = 'APPROVED';
                break;
            }
        }
        $this->dispatch('addendum-updated', message: 'Addendum approved successfully!');
    }

    public function rejectAddendum($id)
    {
        foreach ($this->addendums as &$addendum) {
            if ($addendum['addendum_id'] === $id) {
                $addendum['status'] = 'REJECTED';
                break;
            }
        }
        $this->dispatch('addendum-updated', message: 'Addendum status updated!');
    }

    public function render()
    {
        $filtered = array_filter($this->addendums, function($a) {
            $q = strtolower(trim($this->search));
            $matchesSearch = empty($q) || 
                str_contains(strtolower($a['title']), $q) ||
                str_contains(strtolower($a['addendum_id']), $q) ||
                str_contains(strtolower($a['project_name']), $q);

            $matchesStatus = $this->statusFilter === 'all' || $a['status'] === $this->statusFilter;
            $matchesProject = $this->projectFilter === 'all' || $a['project_id'] === $this->projectFilter;

            return $matchesSearch && $matchesStatus && $matchesProject;
        });

        // KPIs
        $kpiTotal = count($this->addendums);
        $kpiApproved = count(array_filter($this->addendums, fn($a) => $a['status'] === 'APPROVED'));
        $kpiPending = count(array_filter($this->addendums, fn($a) => $a['status'] === 'PENDING'));
        
        $kpiTotalValue = array_reduce(
            array_filter($this->addendums, fn($a) => $a['status'] === 'APPROVED'), 
            fn($acc, $a) => $acc + $a['value'], 
            0
        );

        return view('livewire.addendum', [
            'filteredAddendums' => $filtered,
            'kpiTotal' => $kpiTotal,
            'kpiApproved' => $kpiApproved,
            'kpiPending' => $kpiPending,
            'kpiTotalValue' => $kpiTotalValue,
        ])->layout('components.layouts.app');
    }
}
