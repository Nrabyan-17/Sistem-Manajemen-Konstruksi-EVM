<?php

namespace App\Livewire;

use Livewire\Attributes\Locked;
use Livewire\Component;

class PelaksanaDashboard extends Component
{
    #[Locked] public array $projects = [];
    #[Locked] public array $reports = [];

    public function render()
    {
        return view('livewire.pelaksana-dashboard', [
            'drafts' => count(array_filter($this->reports, fn ($r) => $r['status'] === 'DRAFT')),
            'pending' => count(array_filter($this->reports, fn ($r) => $r['status'] === 'PENDING_APPROVAL')),
            'rejected' => count(array_filter($this->reports, fn ($r) => $r['status'] === 'REJECTED')),
        ]);
    }
}
