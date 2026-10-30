<?php

namespace App\Livewire;

use App\Support\PelaksanaDemoData;
use Livewire\Attributes\Locked;
use Livewire\Component;

/** Optional composition for UI review; not an authentication or access boundary. */
class PelaksanaWorkspace extends Component
{
    #[Locked] public bool $demo = false;
    public string $screen = 'dashboard';
    public string $projectId = '';

    public function render()
    {
        $projects = $this->demo ? PelaksanaDemoData::projects() : [];
        $projectId = $this->projectId ?: ($projects[0]['id'] ?? '');

        return view('livewire.pelaksana-workspace', [
            'projects' => $projects,
            'reports' => $this->demo ? PelaksanaDemoData::reports() : [],
            'context' => $this->demo ? PelaksanaDemoData::context($projectId) : [],
        ]);
    }
}
