<?php

namespace App\Livewire;

use App\Support\ProjectMockData;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProjectDetail extends Component
{
    use WithFileUploads;

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
    public array $sCurveDeviasi;

    public array $milestones;
    public array $addendums = [];

    // BOQ Data (Poin 8 Revisi)
    public array $boqItems = [];

    // Weekly Progress Data (Poin 9 Revisi)
    public array $weeklyProgress = [];

    // Weekly Report Input Modal State
    public bool $showWeeklyModal = false;
    public string $weeklyModalMode = 'manual'; // 'manual' or 'import'
    public int $inputWeekNumber = 1;
    public string $inputPlanPct = '';
    public string $inputActualPct = '';
    public string $inputStartDate = '';
    public string $inputEndDate = '';
    public string $inputDateRange = '';

    // Weekly Report Import Excel State
    public $weeklyImportFile = null;
    public array $importPreviewRows = [];
    public string $importError = '';

    // Modal & Form States: Input RAB
    public bool $showRabModal = false;
    public string $newRabItem = '';
    public string $newRabSatuan = 'm³';
    public string $newRabVolume = '';
    public string $newRabHargaSatuan = '';

    // Modal & Form States: Input Addendum Biaya
    public bool $showAddendumCostModal = false;
    public string $addendumContractNo = '';
    public string $addendumDate = '';
    public string $addendumTitle = 'Add tambah kurang volume';
    public string $addendumValue = '';
    public string $addendumDesc = 'Add tambah kurang volume';
    public $addendumRabFile = null;
    public $addendumTsFile = null;
    public bool $showEditAddendumModal = false;
    public ?int $editAddendumIndex = null;
    public string $editAddendumContractNo = '';
    public string $editAddendumDate = '';
    public string $editAddendumTitle = '';
    public string $editAddendumValue = '';
    public string $editAddendumDesc = '';
    public string $editAddendumDays = '';

    // Modal & Form States: Input Add Waktu
    public bool $showAddendumTimeModal = false;
    public string $timeTitle = '';
    public int $addDays = 30;
    public string $timeReason = '';
    public string $previewBastDate = '';
    public $addendumTimePdfFile = null;

    // Modal & Form State: Edit Project Master Data
    public bool $showEditProjectModal = false;
    public string $editProjectName = '';
    public string $editSpkNumber = '';
    public string $editClient = '';
    public string $editProjectManager = '';
    public string $editLocation = '';
    public string $editContractValue = '';
    public string $editStartDate = '';
    public string $editBastDate = '';
    public $editRabImport;
    public $editSCurveImport;

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
        $this->sCurveDeviasi = array_map(
            fn ($f, $i) => $i <= $cutoffIndex ? round($this->sCurveBcws[$i] - $this->sCurveBcwp[$i], 2) : null,
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

        // Set initial week and automatic dates for weekly input
        $lastWeek = end($this->weeklyProgress);
        $this->inputWeekNumber = $lastWeek ? (int) $lastWeek['week'] + 1 : 1;
        $this->updateCalculatedDatesAndPlan();

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

        // Populate edit modal fields immediately
        $this->editProjectName = $project['project_name'];
        $this->editSpkNumber = $project['spk_number'];
        $this->editClient = $project['client'];
        $this->editProjectManager = $project['project_manager'];
        $this->editLocation = $project['location'];
        $this->editContractValue = (string) $project['contract_value'];
        $this->editStartDate = $project['start_date'];
        $this->editBastDate = $project['bast_date'];
    }

    public function updatedAddDays($val)
    {
        $days = max(1, (int) $val);
        $base = new \DateTime($this->effectiveBastDate ?: $this->project['bast_date']);
        $base->modify("+{$days} days");
        $this->previewBastDate = $base->format('d M Y');
    }

    public function openEditProjectModal(): void
    {
        $this->showEditProjectModal = true;
    }

    public function closeEditProjectModal(): void
    {
        $this->showEditProjectModal = false;
        $this->editRabImport = null;
        $this->editSCurveImport = null;
        $this->resetValidation();
    }

    public function openEditAddendumModal(int $index): void
    {
        if (!isset($this->addendums[$index]) || ($this->addendums[$index]['status'] ?? '') !== 'PENDING') {
            return;
        }

        $addendum = $this->addendums[$index];
        $this->editAddendumIndex = $index;
        $this->editAddendumContractNo = $addendum['contract_no'] ?? $addendum['addendum_id'] ?? '';
        $this->editAddendumDate = $addendum['date'] ?? date('Y-m-d');
        $this->editAddendumTitle = $addendum['title'];
        $this->editAddendumValue = (string) ($addendum['value'] ?? 0);
        $this->editAddendumDesc = $addendum['description'] ?? '';
        $this->editAddendumDays = (string) ($addendum['days_added'] ?? 0);
        $this->showEditAddendumModal = true;
    }

    public function closeEditAddendumModal(): void
    {
        $this->showEditAddendumModal = false;
        $this->editAddendumIndex = null;
        $this->editAddendumContractNo = '';
        $this->editAddendumDate = '';
        $this->resetValidation();
    }

    public function updateAddendum(): void
    {
        $this->validate([
            'editAddendumContractNo' => 'nullable|string|max:100',
            'editAddendumDate' => 'nullable|date',
            'editAddendumTitle' => 'required|string|min:3|max:255',
            'editAddendumValue' => 'required|numeric',
            'editAddendumDesc' => 'nullable|string|max:500',
            'editAddendumDays' => 'required|integer|min:0',
        ]);

        if ($this->editAddendumIndex === null || !isset($this->addendums[$this->editAddendumIndex])
            || ($this->addendums[$this->editAddendumIndex]['status'] ?? '') !== 'PENDING') {
            return;
        }

        if (trim($this->editAddendumContractNo) !== '') {
            $this->addendums[$this->editAddendumIndex]['addendum_id'] = trim($this->editAddendumContractNo);
            $this->addendums[$this->editAddendumIndex]['contract_no'] = trim($this->editAddendumContractNo);
        }
        if (!empty($this->editAddendumDate)) {
            $this->addendums[$this->editAddendumIndex]['date'] = $this->editAddendumDate;
        }
        $this->addendums[$this->editAddendumIndex]['title'] = trim($this->editAddendumTitle);
        $this->addendums[$this->editAddendumIndex]['value'] = (float) $this->editAddendumValue;
        $this->addendums[$this->editAddendumIndex]['description'] = trim($this->editAddendumDesc);
        $this->addendums[$this->editAddendumIndex]['days_added'] = (int) $this->editAddendumDays;
        ProjectMockData::updateAddendums($this->project['project_id'], $this->addendums);
        $this->closeEditAddendumModal();
        $this->recalculateAddendumRisk();
        $this->dispatch('addendum-updated', message: 'Addendum berhasil diperbarui.');
    }

    private function recalculateAddendumRisk(): void
    {
        $totalAddendumValue = array_reduce(
            array_filter($this->addendums, fn ($a) => ($a['status'] ?? '') !== 'VOID'),
            fn ($carry, $a) => $carry + abs($a['value'] ?? 0),
            0
        );
        $this->addendumPercentage = $this->bac > 0 ? round(($totalAddendumValue / $this->bac) * 100, 2) : 0;
        $this->riskLevel = $this->addendumPercentage < 20 ? 'ON TRACK' : ($this->addendumPercentage <= 30 ? 'AT RISK' : 'CRITICAL');
    }

    public function updateProject(): void
    {
        $this->validate([
            'editProjectName' => 'required|string|min:3|max:255',
            'editProjectManager' => 'required|string|max:255',
            'editContractValue' => 'required|numeric|min:1',
            'editStartDate' => 'required|date',
            'editBastDate' => 'required|date|after_or_equal:editStartDate',
            'editRabImport' => 'nullable|file|mimes:xlsx,xls,csv|max:10240',
            'editSCurveImport' => 'nullable|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        ProjectMockData::update($this->project['project_id'], [
            'project_name' => trim($this->editProjectName),
            'project_manager' => trim($this->editProjectManager),
            'contract_value' => (float) $this->editContractValue,
            'start_date' => $this->editStartDate,
            'bast_date' => $this->editBastDate,
        ]);

        $projectId = $this->project['project_id'];
        $this->showEditProjectModal = false;
        $this->editRabImport = null;
        $this->editSCurveImport = null;
        $this->mount($projectId);
        $this->dispatch('project-updated', message: 'Data project berhasil diperbarui!');
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function openWeeklyModal()
    {
        $this->showWeeklyModal = true;
        $this->weeklyModalMode = 'manual';
        $this->resetImportForm();
        $lastWeek = end($this->weeklyProgress);
        $this->inputWeekNumber = $lastWeek ? (int) $lastWeek['week'] + 1 : 1;
        $this->updateCalculatedDatesAndPlan();
        $this->inputActualPct = '';
    }

    public function closeWeeklyModal()
    {
        $this->showWeeklyModal = false;
        $this->resetImportForm();
        $this->inputActualPct = '';
        $this->resetValidation();
    }

    public function setWeeklyModalMode(string $mode)
    {
        $this->weeklyModalMode = in_array($mode, ['manual', 'import'], true) ? $mode : 'manual';
        $this->resetValidation();
    }

    public function updatedInputWeekNumber($val)
    {
        $this->inputWeekNumber = max(1, (int) $val);
        $this->updateCalculatedDatesAndPlan();
    }

    public function getPlannedProgressForWeek(int $week): float
    {
        // 1. Check if mock data has plan for this week
        $mockList = ProjectMockData::weeklyProgressFor($this->project['project_id'] ?? '');
        foreach ($mockList as $item) {
            if ((int) $item['week'] === $week && isset($item['plan_pct'])) {
                return (float) $item['plan_pct'];
            }
        }

        // 2. Check if already recorded in weeklyProgress
        foreach ($this->weeklyProgress as $item) {
            if ((int) $item['week'] === $week && isset($item['plan_pct'])) {
                return (float) $item['plan_pct'];
            }
        }

        // 3. Fallback calculation based on project schedule duration
        $startDate = new \DateTime($this->project['start_date'] ?? '2026-01-01');
        $bastDate = new \DateTime($this->effectiveBastDate ?: ($this->project['bast_date'] ?? '2026-12-31'));
        $days = max(7, $startDate->diff($bastDate)->days);
        $totalWeeks = max(1, (int) ceil($days / 7));

        if ($week > $totalWeeks) {
            return 0.0;
        }

        $existingPlanSum = array_sum(array_column($this->weeklyProgress, 'plan_pct'));
        $remainingPlan = max(0, 100 - $existingPlanSum);
        $remainingWeeks = max(1, $totalWeeks - count($this->weeklyProgress));

        return round($remainingPlan / $remainingWeeks, 2);
    }

    public function updateCalculatedDatesAndPlan(): void
    {
        if (empty($this->project['start_date'])) return;

        $week = max(1, $this->inputWeekNumber);
        $startDate = new \DateTime($this->project['start_date']);
        $startDate->modify('+' . (($week - 1) * 7) . ' days');
        $endDate = clone $startDate;
        $endDate->modify('+6 days');

        $this->inputStartDate = $startDate->format('Y-m-d');
        $this->inputEndDate = $endDate->format('Y-m-d');
        $this->inputDateRange = $startDate->format('d M Y') . ' — ' . $endDate->format('d M Y');

        $plan = $this->getPlannedProgressForWeek($week);
        $this->inputPlanPct = number_format($plan, 2, '.', '');
    }

    public function submitWeeklyProgress()
    {
        $this->validate([
            'inputWeekNumber' => 'required|integer|min:1',
            'inputPlanPct' => 'nullable|numeric|min:0|max:100',
            'inputActualPct' => 'required|numeric|min:0|max:100',
        ]);

        if (empty($this->inputStartDate) || empty($this->inputEndDate)) {
            $this->updateCalculatedDatesAndPlan();
        }

        $startDate = new \DateTime($this->inputStartDate ?: $this->project['start_date']);
        $endDate = new \DateTime($this->inputEndDate ?: $startDate->format('Y-m-d'));

        // Calculate cumulative
        $prevCumulativeActual = 0;
        $prevCumulativePlan = 0;
        if (!empty($this->weeklyProgress)) {
            $last = end($this->weeklyProgress);
            $prevCumulativeActual = $last['cumulative_actual_pct'] ?? 0;
            $prevCumulativePlan = $last['cumulative_plan_pct'] ?? 0;
        }

        $plan = is_numeric($this->inputPlanPct) ? (float) $this->inputPlanPct : $this->getPlannedProgressForWeek($this->inputWeekNumber);
        $actual = (float) $this->inputActualPct;
        $deviasi = round($plan - $actual, 2);

        $newEntry = [
            'week' => $this->inputWeekNumber,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'plan_pct' => $plan,
            'cumulative_plan_pct' => round($prevCumulativePlan + $plan, 2),
            'actual_pct' => $actual,
            'cumulative_actual_pct' => round($prevCumulativeActual + $actual, 2),
            'deviasi' => $deviasi,
        ];

        $this->weeklyProgress[] = $newEntry;

        // Update project EVM metrics
        $this->project['progress'] = $newEntry['cumulative_actual_pct'];
        $this->bcwp = $this->bac * ($this->project['progress'] / 100);
        $this->acwp = $this->bcwp / max(0.01, $this->project['cpi'] ?? 1);
        $this->bcws = $this->bac * ($newEntry['cumulative_plan_pct'] / 100);
        $this->cv   = $this->bcwp - $this->acwp;
        $this->sv   = $this->bcwp - $this->bcws;
        $this->deviasi = round(($this->bcws / $this->bac * 100) - ($this->project['progress']), 2);

        $this->inputWeekNumber++;
        $this->updateCalculatedDatesAndPlan();
        $this->inputActualPct = '';
        $this->showWeeklyModal = false;

        $this->dispatch('weekly-progress-saved', message: "Laporan Minggu ke-{$newEntry['week']} berhasil disimpan!");
    }

    public function updatedWeeklyImportFile()
    {
        $this->validate([
            'weeklyImportFile' => 'required|file|mimes:pdf|max:10240',
        ]);

        $this->importPreviewRows = [];
        $this->importError = '';

        try {
            $path = $this->weeklyImportFile->getRealPath();
            $content = file_get_contents($path);

            $text = '';
            if (preg_match_all('/stream[\r\n]+(.*?)[\r\n]+endstream/is', $content, $matches)) {
                foreach ($matches[1] as $stream) {
                    $uncompressed = @gzuncompress($stream);
                    if ($uncompressed !== false) {
                        $text .= ' ' . $uncompressed;
                    } else {
                        $text .= ' ' . $stream;
                    }
                }
            } else {
                $text = $content;
            }

            preg_match_all('/\((.*?)\)|\[(.*?)\]/s', $text, $textMatches);
            $rawStrings = implode(' ', array_merge($textMatches[1] ?? [], $textMatches[2] ?? []));
            $combinedText = $rawStrings . ' ' . strip_tags($text);

            $startDateObj = new \DateTime($this->project['start_date'] ?? '2026-01-01');
            $preview = [];

            preg_match_all('/(?:Minggu|Week|W)\s*(\d+)[\s:,|-]+(?:([0-9.]+)[% ]*)?(?:[\s:,|-]+([0-9.]+)[% ]*)?/i', $combinedText, $extracted);

            if (!empty($extracted[1])) {
                foreach ($extracted[1] as $idx => $wNum) {
                    $week = (int) $wNum;
                    $sDate = (clone $startDateObj)->modify('+' . (($week - 1) * 7) . ' days')->format('Y-m-d');
                    $eDate = (new \DateTime($sDate))->modify('+6 days')->format('Y-m-d');
                    $planVal = !empty($extracted[2][$idx]) ? (float) $extracted[2][$idx] : $this->getPlannedProgressForWeek($week);
                    $actualVal = !empty($extracted[3][$idx]) ? (float) $extracted[3][$idx] : (is_numeric($this->inputActualPct) ? (float) $this->inputActualPct : $planVal);
                    $dev = round($planVal - $actualVal, 2);

                    $preview[] = [
                        'week' => $week,
                        'start_date' => $sDate,
                        'end_date' => $eDate,
                        'plan_pct' => $planVal,
                        'actual_pct' => $actualVal,
                        'deviasi' => $dev,
                    ];
                }
            }

            if (empty($preview)) {
                $week = $this->inputWeekNumber ?: (count($this->weeklyProgress) + 1);
                $sDate = (clone $startDateObj)->modify('+' . (($week - 1) * 7) . ' days')->format('Y-m-d');
                $eDate = (new \DateTime($sDate))->modify('+6 days')->format('Y-m-d');
                $planVal = (float) ($this->inputPlanPct ?: $this->getPlannedProgressForWeek($week));
                $actualVal = is_numeric($this->inputActualPct) ? (float) $this->inputActualPct : $planVal;
                $dev = round($planVal - $actualVal, 2);

                $preview[] = [
                    'week' => $week,
                    'start_date' => $sDate,
                    'end_date' => $eDate,
                    'plan_pct' => $planVal,
                    'actual_pct' => $actualVal,
                    'deviasi' => $dev,
                ];
            }

            $this->importPreviewRows = $preview;
        } catch (\Throwable $e) {
            $this->importError = 'Gagal memproses file PDF: ' . $e->getMessage();
        }
    }

    public function processWeeklyImport()
    {
        if (empty($this->importPreviewRows)) {
            $this->importError = 'Tidak ada baris data untuk diimpor. Silakan upload file PDF terlebih dahulu.';
            return;
        }

        usort($this->importPreviewRows, fn($a, $b) => $a['week'] <=> $b['week']);

        $existingWeeks = [];
        foreach ($this->weeklyProgress as $p) {
            $existingWeeks[$p['week']] = $p;
        }

        foreach ($this->importPreviewRows as $item) {
            $existingWeeks[$item['week']] = [
                'week' => (int) $item['week'],
                'start_date' => $item['start_date'],
                'end_date' => $item['end_date'],
                'plan_pct' => (float) $item['plan_pct'],
                'cumulative_plan_pct' => 0,
                'actual_pct' => (float) $item['actual_pct'],
                'cumulative_actual_pct' => 0,
                'deviasi' => round((float) $item['plan_pct'] - (float) $item['actual_pct'], 2),
            ];
        }

        ksort($existingWeeks);

        $runningPlan = 0;
        $runningActual = 0;
        $recalculated = [];
        foreach ($existingWeeks as $w => $entry) {
            $runningPlan += $entry['plan_pct'];
            $runningActual += $entry['actual_pct'];
            $entry['cumulative_plan_pct'] = round($runningPlan, 2);
            $entry['cumulative_actual_pct'] = round($runningActual, 2);
            $recalculated[] = $entry;
        }

        $this->weeklyProgress = $recalculated;

        $lastEntry = end($this->weeklyProgress);
        if ($lastEntry) {
            $this->project['progress'] = $lastEntry['cumulative_actual_pct'];
            $this->bcwp = $this->bac * ($this->project['progress'] / 100);
            $this->acwp = $this->bcwp / max(0.01, $this->project['cpi'] ?? 1);
            $this->bcws = $this->bac * ($lastEntry['cumulative_plan_pct'] / 100);
            $this->cv   = $this->bcwp - $this->acwp;
            $this->sv   = $this->bcwp - $this->bcws;
            $this->deviasi = round(($this->bcws / $this->bac * 100) - ($this->project['progress']), 2);
        }

        $count = count($this->importPreviewRows);
        $this->resetImportForm();
        $this->showWeeklyModal = false;

        $this->dispatch('weekly-progress-saved', message: "Berhasil mengimpor {$count} data laporan mingguan dari file PDF!");
    }

    public function resetImportForm()
    {
        $this->weeklyImportFile = null;
        $this->importPreviewRows = [];
        $this->importError = '';
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

        ProjectMockData::updateBoqItems($this->project['project_id'], $this->boqItems);

        $this->resetRabForm();
        $this->showRabModal = false;
        $this->dispatch('rab-item-saved', message: 'Item RAB baru berhasil ditambahkan ke BOQ!');
    }

    // ===== Action Methods: Input Addendum Biaya =====
    public function openAddendumCostModal()
    {
        $this->showAddendumCostModal = true;
        $projCode = substr($this->project['project_id'], 4) ?: '001';
        $this->addendumContractNo = 'ADD-' . $projCode . '-' . str_pad(count($this->addendums) + 1, 2, '0', STR_PAD_LEFT);
        $this->addendumDate = date('Y-m-d');
        $this->addendumTitle = 'Add tambah kurang volume';
        $this->addendumValue = '';
        $this->addendumDesc = 'Add tambah kurang volume';
        $this->addendumRabFile = null;
        $this->addendumTsFile = null;
    }

    public function closeAddendumCostModal()
    {
        $this->showAddendumCostModal = false;
        $this->addendumContractNo = '';
        $this->addendumDate = '';
        $this->addendumTitle = 'Add tambah kurang volume';
        $this->addendumValue = '';
        $this->addendumDesc = 'Add tambah kurang volume';
        $this->addendumRabFile = null;
        $this->addendumTsFile = null;
        $this->resetValidation();
    }

    public function removeAddendumRabFile()
    {
        $this->addendumRabFile = null;
    }

    public function removeAddendumTsFile()
    {
        $this->addendumTsFile = null;
    }

    public function submitAddendumCost()
    {
        if (empty($this->addendumTitle)) {
            $this->addendumTitle = 'Add tambah kurang volume';
        }
        if (empty($this->addendumDesc)) {
            $this->addendumDesc = 'Add tambah kurang volume';
        }

        $this->validate([
            'addendumContractNo' => 'nullable|string|max:100',
            'addendumDate' => 'nullable|date',
            'addendumTitle' => 'required|string|min:3|max:255',
            'addendumValue' => 'required|numeric',
            'addendumRabFile' => 'nullable|file|mimes:pdf|max:10240',
            'addendumTsFile' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $val = (float) $this->addendumValue;
        $projCode = substr($this->project['project_id'], 4) ?: '001';
        $generatedId = 'ADD-' . $projCode . '-' . str_pad(count($this->addendums) + 1, 2, '0', STR_PAD_LEFT);
        $addId = trim($this->addendumContractNo) !== '' ? trim($this->addendumContractNo) : $generatedId;

        $attachmentName = null;
        if ($this->addendumRabFile) {
            $attachmentName = $this->addendumRabFile->getClientOriginalName();
        }
        $timeScheduleAttachmentName = null;
        if ($this->addendumTsFile) {
            $timeScheduleAttachmentName = $this->addendumTsFile->getClientOriginalName();
        }

        $this->addendums[] = [
            'addendum_id' => $addId,
            'contract_no' => trim($this->addendumContractNo) !== '' ? trim($this->addendumContractNo) : $addId,
            'project_id' => $this->project['project_id'],
            'project_name' => $this->project['project_name'],
            'title' => $this->addendumTitle ?: 'Add tambah kurang volume',
            'value' => $val,
            'date' => $this->addendumDate ?: date('Y-m-d'),
            'status' => 'PENDING',
            'description' => $this->addendumDesc
                . ($attachmentName ? " (Lampiran RAB: {$attachmentName})" : '')
                . ($timeScheduleAttachmentName ? " (Lampiran TS: {$timeScheduleAttachmentName})" : ''),
            'type' => 'COST',
            'days_added' => 0,
            'attachment' => $attachmentName,
            'time_schedule_attachment' => $timeScheduleAttachmentName,
        ];

        ProjectMockData::updateAddendums($this->project['project_id'], $this->addendums);

        // Recalculate addendum risk percentage
        $totalAddendumValue = array_reduce($this->addendums, fn($carry, $a) => $carry + (($a['status'] ?? '') === 'VOID' ? 0 : abs($a['value'] ?? 0)), 0);
        $this->addendumPercentage = $this->bac > 0 ? round(($totalAddendumValue / $this->bac) * 100, 2) : 0;
        if ($this->addendumPercentage < 20) {
            $this->riskLevel = 'ON TRACK';
        } elseif ($this->addendumPercentage <= 30) {
            $this->riskLevel = 'AT RISK';
        } else {
            $this->riskLevel = 'CRITICAL';
        }

        $this->closeAddendumCostModal();
        $this->dispatch('addendum-cost-saved', message: 'Addendum Biaya berhasil dicatat ke Riwayat Addendum & Kartu Keuangan!');
    }

    // ===== Action Methods: Input Add Waktu =====
    public function openAddendumTimeModal()
    {
        $this->showAddendumTimeModal = true;
        $this->addendumTimePdfFile = null;
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
        $this->addendumTimePdfFile = null;
    }

    public function removeAddendumTimePdfFile()
    {
        $this->addendumTimePdfFile = null;
    }

    public function submitAddendumTime()
    {
        $this->validate([
            'addDays' => 'required|integer|min:1',
            'timeReason' => 'required|string|min:3|max:500',
            'timeTitle' => 'nullable|string|max:255',
            'addendumTimePdfFile' => 'nullable|file|mimes:pdf|max:10240',
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

        $pdfAttachmentName = null;
        if ($this->addendumTimePdfFile) {
            $pdfAttachmentName = $this->addendumTimePdfFile->getClientOriginalName();
        }

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
            'status' => 'PENDING',
            'description' => $this->timeReason . ($pdfAttachmentName ? " (Dokumen PDF: {$pdfAttachmentName})" : ''),
            'type' => 'TIME',
            'days_added' => $days,
            'attachment' => $pdfAttachmentName,
        ];

        ProjectMockData::updateAddendums($this->project['project_id'], $this->addendums);

        $this->closeAddendumTimeModal();
        $this->dispatch('addendum-time-saved', message: "Addendum Waktu berhasil ditambahkan (+{$days} Hari)! BAST: " . date('d M Y', strtotime($this->effectiveBastDate)));
    }

    public function render()
    {
        return view('livewire.project-detail')->layout('components.layouts.app');
    }
}
