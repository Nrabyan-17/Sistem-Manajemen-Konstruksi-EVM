<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Dashboard - EVM Dashboard')]
class Dashboard extends Component
{
    public $projectKey = 'portfolio';

    public $weeks = ['W1', 'W2', 'W4', 'W6', 'W8', 'W10', 'W12', 'W14', 'W16', 'W18', 'W20', 'W22', 'W24'];

    public $projectData = [
        'portfolio' => [
            'bcws' => [0.08, 0.25, 0.75, 1.60, 2.90, 4.60, 6.40, 8.25, 9.60, 10.40, 10.85, 11.10, 11.20],
            'bcwp' => [0.06, 0.20, 0.65, 1.40, 2.65, 4.25, 6.05, 7.90, 9.20, 9.95, null, null, null],
            'acwp' => [0.08, 0.28, 0.80, 1.65, 3.10, 4.80, 6.75, 8.65, 9.90, 10.60, null, null, null],
            'statBcws' => 'Rp 8.25 Milyar',
            'statBcwp' => 'Rp 7.90 Milyar',
            'statAcwp' => 'Rp 8.65 Milyar',
        ],
        'horizon' => [
            'bcws' => [0.10, 0.30, 0.80, 1.70, 3.00, 4.80, 6.80, 8.60, 9.80, 10.60, 11.10, 11.35, 11.50],
            'bcwp' => [0.12, 0.35, 0.90, 1.85, 3.20, 5.05, 7.10, 8.95, 10.10, 10.80, null, null, null],
            'acwp' => [0.10, 0.32, 0.85, 1.75, 3.05, 4.85, 6.80, 8.60, 9.70, 10.35, null, null, null],
            'statBcws' => 'Rp 8.60 Milyar',
            'statBcwp' => 'Rp 8.95 Milyar',
            'statAcwp' => 'Rp 8.60 Milyar',
        ],
        'metro' => [
            'bcws' => [0.05, 0.18, 0.60, 1.45, 2.70, 4.30, 6.20, 8.00, 9.40, 10.20, 10.70, 10.95, 11.00],
            'bcwp' => [0.04, 0.12, 0.40, 0.95, 1.80, 2.90, 4.20, 5.60, 6.80, 7.50, null, null, null],
            'acwp' => [0.06, 0.22, 0.75, 1.80, 3.30, 5.10, 7.10, 9.10, 10.50, 11.40, null, null, null],
            'statBcws' => 'Rp 8.00 Milyar',
            'statBcwp' => 'Rp 5.60 Milyar',
            'statAcwp' => 'Rp 9.10 Milyar',
        ],
        'industrial' => [
            'bcws' => [0.08, 0.22, 0.70, 1.50, 2.80, 4.40, 6.10, 7.80, 9.10, 9.90, 10.40, 10.70, 10.80],
            'bcwp' => [0.06, 0.18, 0.55, 1.20, 2.20, 3.50, 4.90, 6.40, 7.50, 8.20, null, null, null],
            'acwp' => [0.07, 0.20, 0.62, 1.35, 2.50, 3.90, 5.50, 7.20, 8.40, 9.10, null, null, null],
            'statBcws' => 'Rp 7.80 Milyar',
            'statBcwp' => 'Rp 6.40 Milyar',
            'statAcwp' => 'Rp 7.20 Milyar',
        ],
    ];

    public function setProject($key)
    {
        if (array_key_exists($key, $this->projectData)) {
            $this->projectKey = $key;
            // Dispatch a browser event so Alpine.js knows it needs to re-render the chart
            $this->dispatch('project-changed', projectKey: $key);
        }
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'currentProject' => $this->projectData[$this->projectKey],
        ])->layout('components.layouts.app');
    }
}
