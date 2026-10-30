<?php

namespace App\Livewire;

use Livewire\Attributes\Locked;
use Livewire\Component;

class PelaksanaProjects extends Component
{
    #[Locked] public array $projects = [];
    public string $search = '';

    public function render()
    {
        $query = mb_strtolower(trim($this->search));

        return view('livewire.pelaksana-projects', [
            'filteredProjects' => array_filter($this->projects, fn ($p) => $query === '' || str_contains(mb_strtolower($p['code'].' '.$p['name'].' '.$p['location']), $query)),
        ]);
    }
}
