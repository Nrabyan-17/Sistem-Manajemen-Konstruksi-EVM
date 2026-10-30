<?php

namespace App\Livewire;

use App\Support\PelaksanaUiData;
use Livewire\Attributes\Locked;
use Livewire\Component;

class PelaksanaReports extends Component
{
    #[Locked] public array $reports = [];
    public string $statusFilter = '';
    public string $search = '';
    public string $selectedReportId = '';

    public function render()
    {
        $query = mb_strtolower(trim($this->search));

        return view('livewire.pelaksana-reports', [
            'statuses' => PelaksanaUiData::STATUSES,
            'filteredReports' => array_filter($this->reports, fn ($r) => ($this->statusFilter === '' || $r['status'] === $this->statusFilter) && ($query === '' || str_contains(mb_strtolower($r['project_name'].' '.$r['id']), $query))),
            'selectedReport' => collect($this->reports)->firstWhere('id', $this->selectedReportId),
        ]);
    }
}
