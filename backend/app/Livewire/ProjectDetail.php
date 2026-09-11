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

    // Financial indicators (Poin 6 Revisi)
    public float $terminDiterima;
    public float $labaRugi;

    // Data for the S-Curve chart (passed to Chart.js via @json in the view)
    public array $sCurveWeeks;
    public array $sCurveBcws;
    public array $sCurveBcwp;
    public array $sCurveAcwp;

    public array $milestones;
    public array $addendums = [];

    // BOQ Data (Poin 8 Revisi)
    public array $boqItems = [];

    // Weekly Progress Data (Poin 9 Revisi)
    public array $weeklyProgress = [];

    // Weekly Report Input Modal State
    public bool $showWeeklyModal = false;
    public int $inputWeekNumber = 1;
    public string $inputActualPct = '';

    // Modal & Form States: Input RAB
    public bool $showRabModal = false;
    public string $newRabItem = '';
    public string $newRabSatuan = 'm³';
    public string $newRabVolume = '';
    public string $newRabHargaSatuan = '';

    // Modal & Form States: Input Addendum Biaya
    public bool $showAddendumCostModal = false;
    public string $addendumTitle = '';
    public string $addendumValue = '';
    public string $addendumDesc = '';

    // Modal & Form States: Input Add Waktu
    public bool $showAddendumTimeModal = false;
    public string $timeTitle = '';
    public int $addDays = 30;
    public string $timeReason = '';
    public string $previewBastDate = '';

    // Time extension trackers
    public int $totalDaysAdded = 0;
    public string $effectiveBastDate = '';
    public int $effectiveRemainingDays = 0;

    // Deviasi & Risk (Poin 4 Revisi)
    public float $deviasi;
    public string $riskLevel;
    public float $addendumPercentage;

    public function mount(string $projectId)
    {
        $project = ProjectMockData::find($projectId);

        abort_if(!$project, 404, "Project {$projectId} not found");

        $this->project = $project;
        $this->history = ProjectMockData::historyFor($projectId);
        $this->addendums = ProjectMockData::addendumsFor($projectId);
        $this->boqItems = ProjectMockData::boqItemsFor($projectId);
        $this->weeklyProgress = ProjectMockData::weeklyProgressFor($projectId);

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

        // ===== Financial Indicators (Poin 6 Revisi) =====
        $this->terminDiterima = (float) ($project['termin_diterima'] ?? 0);
        $this->labaRugi = $this->terminDiterima - $this->acwp;

        // ===== Deviasi (Poin 4 Revisi) =====
        // Schedule Deviation = BCWS% - BCWP% (in percentage points)
        $bcwsPct = ($this->bcws / $this->bac) * 100;
        $bcwpPct = ($this->bcwp / $this->bac) * 100;
        $this->deviasi = round($bcwsPct - $bcwpPct, 2);

        // Addendum Risk Percentage = Total Addendum Value / RAB Awal x 100%
        $totalAddendumValue = array_reduce($this->addendums, fn($carry, $a) => $carry + abs($a['value']), 0);
        $this->addendumPercentage = $this->bac > 0 ? round(($totalAddendumValue / $this->bac) * 100, 2) : 0;

        // Risk Level based on addendum percentage thresholds
        if ($this->addendumPercentage < 20) {
            $this->riskLevel = 'ON TRACK';
        } elseif ($this->addendumPercentage <= 30) {
            $this->riskLevel = 'AT RISK';
        } else {
            $this->riskLevel = 'CRITICAL';
        }

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

        // Set initial week for weekly input
        $lastWeek = end($this->weeklyProgress);
        $this->inputWeekNumber = $lastWeek ? $lastWeek['week'] + 1 : 1;

        // ===== Time Extension Tracker =====
        $this->totalDaysAdded = 0;
        foreach ($this->addendums as $a) {
            if (!empty($a['days_added'])) {
                $this->totalDaysAdded += (int) $a['days_added'];
            }
        }
        $baseBast = new \DateTime($this->project['bast_date']);
        if ($this->totalDaysAdded > 0) {
            $baseBast->modify("+{$this->totalDaysAdded} days");
        }
        $this->effectiveBastDate = $baseBast->format('Y-m-d');
        $today = new \DateTime();
        $diff = $today->diff($baseBast);
        $this->effectiveRemainingDays = $diff->invert ? -$diff->days : $diff->days;

        $preview = clone $baseBast;
        $preview->modify("+30 days");
        $this->previewBastDate = $preview->format('d M Y');
    }

    public function updatedAddDays($val)
    {
        $days = max(1, (int) $val);
        $base = new \DateTime($this->effectiveBastDate ?: $this->project['bast_date']);
        $base->modify("+{$days} days");
        $this->previewBastDate = $base->format('d M Y');
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function openWeeklyModal()
    {
        $this->showWeeklyModal = true;
    }

    public function closeWeeklyModal()
    {
        $this->showWeeklyModal = false;
        $this->inputActualPct = '';
    }

    public function submitWeeklyProgress()
    {
        $this->validate([
            'inputWeekNumber' => 'required|integer|min:1',
            'inputActualPct' => 'required|numeric|min:0|max:100',
        ]);

        // Determine start/end dates based on project start and week number
        $startDate = new \DateTime($this->project['start_date']);
        $startDate->modify('+' . (($this->inputWeekNumber - 1) * 7) . ' days');
        $endDate = clone $startDate;
        $endDate->modify('+6 days');

        // Calculate cumulative
        $prevCumulative = 0;
        if (!empty($this->weeklyProgress)) {
            $last = end($this->weeklyProgress);
            $prevCumulative = $last['cumulative_actual_pct'];
        }

        $newEntry = [
            'week' => $this->inputWeekNumber,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'plan_pct' => 0,
            'cumulative_plan_pct' => 0,
            'actual_pct' => (float) $this->inputActualPct,
            'cumulative_actual_pct' => round($prevCumulative + (float) $this->inputActualPct, 2),
        ];

        $this->weeklyProgress[] = $newEntry;
        $this->inputWeekNumber++;
        $this->inputActualPct = '';
        $this->showWeeklyModal = false;

        $this->dispatch('weekly-progress-saved', message: 'Laporan mingguan berhasil disimpan!');
    }

    // ===== Action Methods: Input RAB =====
    public function openRabModal()
    {
        $this->showRabModal = true;
    }

    public function closeRabModal()
    {
        $this->showRabModal = false;
        $this->resetRabForm();
    }

    public function resetRabForm()
    {
        $this->newRabItem = '';
        $this->newRabSatuan = 'm³';
        $this->newRabVolume = '';
        $this->newRabHargaSatuan = '';
    }

    public function submitRabItem()
    {
        $this->validate([
            'newRabItem' => 'required|string|min:3|max:255',
            'newRabSatuan' => 'required|string|max:50',
            'newRabVolume' => 'required|numeric|min:0.01',
            'newRabHargaSatuan' => 'required|numeric|min:1',
        ]);

        $volume = (float) $this->newRabVolume;
        $hargaSatuan = (float) $this->newRabHargaSatuan;
        $subtotal = $volume * $hargaSatuan;

        $newNo = count($this->boqItems) + 1;
        $this->boqItems[] = [
            'no' => $newNo,
            'item' => $this->newRabItem,
            'satuan' => $this->newRabSatuan,
            'volume' => $volume,
            'harga_satuan' => $hargaSatuan,
            'subtotal' => $subtotal,
            'is_addendum' => false,
        ];

        $this->resetRabForm();
        $this->showRabModal = false;
        $this->dispatch('rab-item-saved', message: 'Item RAB baru berhasil ditambahkan ke BOQ!');
    }

    // ===== Action Methods: Input Addendum Biaya =====
    public function openAddendumCostModal()
    {
        $this->showAddendumCostModal = true;
    }

    public function closeAddendumCostModal()
    {
        $this->showAddendumCostModal = false;
        $this->addendumTitle = '';
        $this->addendumValue = '';
        $this->addendumDesc = '';
    }

    public function submitAddendumCost()
    {
        $this->validate([
            'addendumTitle' => 'required|string|min:3|max:255',
            'addendumValue' => 'required|numeric',
            'addendumDesc' => 'nullable|string|max:500',
        ]);

        $val = (float) $this->addendumValue;
        $projCode = substr($this->project['project_id'], 4) ?: '001';
        $addId = 'ADD-' . $projCode . '-' . str_pad(count($this->addendums) + 1, 2, '0', STR_PAD_LEFT);

        $this->addendums[] = [
            'addendum_id' => $addId,
            'project_id' => $this->project['project_id'],
            'project_name' => $this->project['project_name'],
            'title' => $this->addendumTitle,
            'value' => $val,
            'date' => date('Y-m-d'),
            'status' => 'APPROVED',
            'description' => $this->addendumDesc ?: 'Addendum biaya pekerjaan tambah/kurang.',
            'type' => 'COST',
            'days_added' => 0,
        ];

        // Also add row to BOQ with indicator is_addendum
        $newNo = count($this->boqItems) + 1;
        $this->boqItems[] = [
            'no' => $newNo,
            'item' => '[ADDENDUM] ' . $this->addendumTitle,
            'satuan' => 'Ls',
            'volume' => 1,
            'harga_satuan' => $val,
            'subtotal' => $val,
            'is_addendum' => true,
        ];

        // Recalculate addendum risk percentage
        $totalAddendumValue = array_reduce($this->addendums, fn($carry, $a) => $carry + abs($a['value'] ?? 0), 0);
        $this->addendumPercentage = $this->bac > 0 ? round(($totalAddendumValue / $this->bac) * 100, 2) : 0;
        if ($this->addendumPercentage < 20) {
            $this->riskLevel = 'ON TRACK';
        } elseif ($this->addendumPercentage <= 30) {
            $this->riskLevel = 'AT RISK';
        } else {
            $this->riskLevel = 'CRITICAL';
        }

        $this->closeAddendumCostModal();
        $this->dispatch('addendum-cost-saved', message: 'Addendum Biaya berhasil dicatat & ditambahkan ke BOQ!');
    }

    // ===== Action Methods: Input Add Waktu =====
    public function openAddendumTimeModal()
    {
        $this->showAddendumTimeModal = true;
        $base = new \DateTime($this->effectiveBastDate ?: $this->project['bast_date']);
        $base->modify("+{$this->addDays} days");
        $this->previewBastDate = $base->format('d M Y');
    }

    public function closeAddendumTimeModal()
    {
        $this->showAddendumTimeModal = false;
        $this->timeTitle = '';
        $this->addDays = 30;
        $this->timeReason = '';
    }

    public function submitAddendumTime()
    {
        $this->validate([
            'addDays' => 'required|integer|min:1',
            'timeReason' => 'required|string|min:3|max:500',
            'timeTitle' => 'nullable|string|max:255',
        ]);

        $days = (int) $this->addDays;
        $this->totalDaysAdded += $days;

        // Recalculate effective BAST date
        $baseBast = new \DateTime($this->project['bast_date']);
        $baseBast->modify("+{$this->totalDaysAdded} days");
        $this->effectiveBastDate = $baseBast->format('Y-m-d');

        $today = new \DateTime();
        $diff = $today->diff($baseBast);
        $this->effectiveRemainingDays = $diff->invert ? -$diff->days : $diff->days;

        // Add to addendums list
        $projCode = substr($this->project['project_id'], 4) ?: '001';
        $addId = 'ADD-T-' . $projCode . '-' . str_pad(count($this->addendums) + 1, 2, '0', STR_PAD_LEFT);

        $this->addendums[] = [
            'addendum_id' => $addId,
            'project_id' => $this->project['project_id'],
            'project_name' => $this->project['project_name'],
            'title' => $this->timeTitle ?: "Perpanjangan Waktu (+{$days} Hari)",
            'value' => 0,
            'date' => date('Y-m-d'),
            'status' => 'APPROVED',
            'description' => $this->timeReason,
            'type' => 'TIME',
            'days_added' => $days,
        ];

        $this->closeAddendumTimeModal();
        $this->dispatch('addendum-time-saved', message: "Addendum Waktu berhasil ditambahkan (+{$days} Hari)! BAST: " . date('d M Y', strtotime($this->effectiveBastDate)));
    }

    public function render()
    {
        return view('livewire.project-detail')->layout('components.layouts.app');
    }
}
