<?php

namespace App\Livewire;

use App\Support\ProjectMockData;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

#[Title('Projects - EVM Dashboard')]
class Projects extends Component
{
    use WithFileUploads;

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

    // File Uploads for new project
    public $newRabImport;
    public $newSCurveImport;

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
            'newRabImport' => 'nullable|file|mimes:xlsx,xls,csv|max:10240',
            'newSCurveImport' => 'nullable|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $projectId = $this->newProjId;

        // Calculate remaining days
        $bast = new \DateTime($this->newBastDate);
        $today = new \DateTime();
        $interval = $today->diff($bast);
        $remainingVal = $bast < $today ? 0 : (int)$interval->format('%a');

        $newProjectObj = [
            'project_id' => $projectId,
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
            'status' => 'ON TRACK',
            'termin_diterima' => 0,
            'last_updated' => date('Y-m-d'),
        ];

        $this->projects[] = $newProjectObj;

        // Initialize history log
        $this->projectHistory[$projectId] = [
            [
                'date' => date('Y-m-d'),
                'user' => 'System',
                'action' => 'Project Created',
                'old' => '—',
                'new' => "{$projectId} initialized"
            ]
        ];

        // ===== Parse Excel RAB → BOQ Items =====
        if ($this->newRabImport) {
            $boqItems = $this->parseRabExcel($this->newRabImport);
            if (!empty($boqItems)) {
                ProjectMockData::updateBoqItems($projectId, $boqItems);
            }
        }

        // ===== Parse Excel Time-Schedule → Weekly Progress =====
        if ($this->newSCurveImport) {
            $weeklyData = $this->parseTimeScheduleExcel($this->newSCurveImport);
            if (!empty($weeklyData)) {
                ProjectMockData::updateWeeklyProgress($projectId, $weeklyData);
            }
        }

        // Reset Form Fields
        $this->reset([
            'newProjName', 'newProjId', 'newSpk', 'newClient', 'newPm',
            'newValue', 'newStartDate', 'newBastDate', 'newStatus', 'newLocation',
        ]);
        $this->newRabImport = null;
        $this->newSCurveImport = null;

        // Dispatch browser event to show toast and close modal
        $this->dispatch('project-created', message: 'Project created successfully!');
    }

    /**
     * Parse an RAB Excel file into BOQ item rows.
     * Expected columns: No, Item/Uraian, Satuan, Volume, Harga Satuan, Subtotal
     */
    private function parseRabExcel($file): array
    {
        try {
            $path = $file->getRealPath();
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, false);

            if (count($rows) < 2) return [];

            $items = [];
            $no = 1;

            // Skip header row(s) — start from row index 1
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                // Skip completely empty rows
                if (empty(array_filter($row, fn($v) => $v !== null && $v !== ''))) continue;

                // Flexible: try to extract columns
                // Format: No | Item | Satuan | Volume | Harga Satuan | Subtotal
                $itemName = trim((string)($row[1] ?? $row[0] ?? ''));
                if (empty($itemName)) continue;

                $satuan = trim((string)($row[2] ?? 'Ls'));
                $volume = (float)($row[3] ?? 0);
                $hargaSatuan = (float)($row[4] ?? 0);
                $subtotal = (float)($row[5] ?? ($volume * $hargaSatuan));

                $items[] = [
                    'no' => $no++,
                    'item' => $itemName,
                    'satuan' => $satuan ?: 'Ls',
                    'volume' => $volume,
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal > 0 ? $subtotal : ($volume * $hargaSatuan),
                ];
            }

            return $items;
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Parse a Time-Schedule Excel file into weekly progress rows.
     * Expected columns: Mg ke | Tgl Awal | Tgl Akhir | Progres Rencana | Kumulatif Rencana
     *                   (optional: Progres Realisasi | Kumulatif Realisasi | Deviasi | ACWP)
     */
    private function parseTimeScheduleExcel($file): array
    {
        try {
            $path = $file->getRealPath();
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray(null, true, true, false);

            if (count($rows) < 2) return [];

            $items = [];

            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                if (empty(array_filter($row, fn($v) => $v !== null && $v !== ''))) continue;

                // Column 0: Mg ke (week number — strip non-numeric prefix like "MG")
                $weekRaw = trim((string)($row[0] ?? ''));
                $weekNum = (int) preg_replace('/[^0-9]/', '', $weekRaw);
                if ($weekNum <= 0) continue;

                // Columns 1,2: Start/end dates
                $startDate = $this->parseExcelDate($row[1] ?? '', $sheet->getCell([2, $i + 1]));
                $endDate = $this->parseExcelDate($row[2] ?? '', $sheet->getCell([3, $i + 1]));

                // Columns 3,4: Plan %, Cumulative Plan %
                $planPct = $this->parsePercentValue($row[3] ?? 0);
                $cumPlanPct = $this->parsePercentValue($row[4] ?? 0);

                // Columns 5,6: Actual %, Cumulative Actual % (optional)
                $actualPct = $this->parsePercentValue($row[5] ?? 0);
                $cumActualPct = $this->parsePercentValue($row[6] ?? 0);

                $items[] = [
                    'week' => $weekNum,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'plan_pct' => $planPct,
                    'cumulative_plan_pct' => $cumPlanPct,
                    'actual_pct' => $actualPct,
                    'cumulative_actual_pct' => $cumActualPct,
                ];
            }

            return $items;
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Safely parse an Excel date value (could be a serial number or a string).
     */
    private function parseExcelDate($value, $cell = null): string
    {
        if (empty($value)) return '';

        // If it's a numeric Excel serial date
        if (is_numeric($value) && $value > 40000) {
            try {
                $dateTime = ExcelDate::excelToDateTimeObject((float)$value);
                return $dateTime->format('Y-m-d');
            } catch (\Throwable $e) {}
        }

        // Try parsing common date formats
        $formats = ['d-M', 'd-M-y', 'd/m/Y', 'd-m-Y', 'Y-m-d', 'd M Y', 'd-M-Y'];
        foreach ($formats as $fmt) {
            $dt = \DateTime::createFromFormat($fmt, trim((string)$value));
            if ($dt !== false) {
                // If year was not in format, assume current year
                if (!str_contains($fmt, 'Y') && !str_contains($fmt, 'y')) {
                    $dt->setDate((int)date('Y'), (int)$dt->format('m'), (int)$dt->format('d'));
                }
                return $dt->format('Y-m-d');
            }
        }

        return trim((string)$value);
    }

    /**
     * Parse a percentage value — handles both 0.0532 (fraction) and 5.32 (already %) forms.
     */
    private function parsePercentValue($value): float
    {
        $v = (float) $value;
        // If value looks like it's already a percentage (>= 0 and stated as-is), return as-is
        // The Excel format shows 5,32 meaning 5.32%
        return round($v, 2);
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

        // Calculate EVM derived fields for each filtered project (Poin 4 Revisi)
        $allAddendums = \App\Support\ProjectMockData::addendums();
        $enriched = array_map(function ($p) use ($allAddendums) {
            $bac = (float) $p['contract_value'];
            $bcwp = $bac * ($p['progress'] / 100);
            $acwp = $p['cpi'] > 0 ? $bcwp / $p['cpi'] : 0;
            $bcws = $p['spi'] > 0 ? $bcwp / $p['spi'] : 0;

            $bcwsPct = $bac > 0 ? round(($bcws / $bac) * 100, 2) : 0;
            $bcwpPct = (float) $p['progress'];
            $deviasi = round($bcwsPct - $bcwpPct, 2);

            // Addendum risk percentage
            $projAddendums = array_filter($allAddendums, fn($a) => $a['project_id'] === $p['project_id']);
            $totalAddendumValue = array_reduce($projAddendums, fn($carry, $a) => $carry + abs($a['value']), 0);
            $addendumPct = $bac > 0 ? round(($totalAddendumValue / $bac) * 100, 2) : 0;

            $p['bcws_pct'] = $bcwsPct;
            $p['bcwp_pct'] = $bcwpPct;
            $p['acwp'] = $acwp;
            $p['deviasi'] = $deviasi;
            $p['addendum_pct'] = $addendumPct;

            return $p;
        }, $filtered);

        // Re-index
        $filtered = array_values($enriched);

        // Calculate KPIs dynamically
        $kpiTotal = count($this->projects);
        $kpiCompleted = count(array_filter($this->projects, fn($p) => $p['status'] === 'COMPLETED'));
        $kpiOnTrack = count(array_filter($this->projects, fn($p) => $p['status'] === 'ON TRACK'));
        $kpiAtRisk = count(array_filter($this->projects, fn($p) => $p['status'] === 'AT RISK'));
        $kpiCritical = count(array_filter($this->projects, fn($p) => $p['status'] === 'CRITICAL'));
        $kpiTotalContract = array_reduce($this->projects, fn($acc, $p) => $acc + $p['contract_value'], 0);
        $kpiTotalRealCost = array_reduce($this->projects, function ($acc, $p) {
            $bac = (float) ($p['contract_value'] ?? 0);
            $progress = (float) ($p['progress'] ?? 0);
            $bcwp = $bac * ($progress / 100);
            $cpi = (float) ($p['cpi'] ?? 1);
            $acwp = $cpi > 0 ? $bcwp / $cpi : 0;
            return $acc + $acwp;
        }, 0);

        return view('livewire.projects', [
            'filteredProjects' => $filtered,
            'paginatedProjects' => $paginated,
            'totalPages' => $totalPages,
            'kpiTotal' => $kpiTotal,
            'kpiCompleted' => $kpiCompleted,
            'kpiOnTrack' => $kpiOnTrack,
            'kpiAtRisk' => $kpiAtRisk,
            'kpiCritical' => $kpiCritical,
            'kpiTotalContract' => $kpiTotalContract,
            'kpiTotalRealCost' => $kpiTotalRealCost,
        ])->layout('components.layouts.app');
    }
}
