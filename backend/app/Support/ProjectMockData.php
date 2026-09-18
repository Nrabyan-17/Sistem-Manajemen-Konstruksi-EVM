<?php

namespace App\Support;

class ProjectMockData
{
    public static function all(): array
    {
        $projects = [
            [
                'project_id' => 'PRJ-001', 'project_name' => 'Grand Horizon Tower', 'spk_number' => 'SPK-2026-001',
                'client' => 'PT ABC', 'project_manager' => 'Andi Pratama', 'location' => 'Jakarta',
                'contract_value' => 12500000000, 'start_date' => '2026-01-12', 'bast_date' => '2026-12-30',
                'remaining_days' => 45, 'progress' => 75, 'cpi' => 1.04, 'spi' => 0.98,
                'eac' => 9800000000, 'status' => 'ON TRACK',
                'termin_diterima' => 7200000000, 'last_updated' => '2026-08-05',
            ],
            [
                'project_id' => 'PRJ-002', 'project_name' => 'Industrial Park Phase II', 'spk_number' => 'SPK-2026-002',
                'client' => 'PT Mitra Logistik Nusantara', 'project_manager' => 'Budi Santoso', 'location' => 'Bekasi',
                'contract_value' => 18000000000, 'start_date' => '2026-02-01', 'bast_date' => '2027-04-15',
                'remaining_days' => 120, 'progress' => 42, 'cpi' => 0.88, 'spi' => 0.85,
                'eac' => 15200000000, 'status' => 'AT RISK',
                'termin_diterima' => 5400000000, 'last_updated' => '2026-08-01',
            ],
            [
                'project_id' => 'PRJ-003', 'project_name' => 'Coastal Bridge Revitalization', 'spk_number' => 'SPK-2025-089',
                'client' => 'Dinas Pekerjaan Umum', 'project_manager' => 'Dimas Wijaya', 'location' => 'Surabaya',
                'contract_value' => 9200000000, 'start_date' => '2025-11-10', 'bast_date' => '2026-08-15',
                'remaining_days' => 12, 'progress' => 92, 'cpi' => 1.12, 'spi' => 1.05,
                'eac' => 8400000000, 'status' => 'ON TRACK',
                'termin_diterima' => 7800000000, 'last_updated' => '2026-08-08',
            ],
            [
                'project_id' => 'PRJ-004', 'project_name' => 'Metro Line Extension', 'spk_number' => 'SPK-2026-003',
                'client' => 'PT Kereta Api Indonesia (Persero)', 'project_manager' => 'Rizky Ramadhan', 'location' => 'Bandung',
                'contract_value' => 24000000000, 'start_date' => '2026-03-01', 'bast_date' => '2027-02-28',
                'remaining_days' => 240, 'progress' => 28, 'cpi' => 0.75, 'spi' => 0.72,
                'eac' => 21500000000, 'status' => 'CRITICAL',
                'termin_diterima' => 4200000000, 'last_updated' => '2026-08-09',
            ],
            [
                'project_id' => 'PRJ-005', 'project_name' => 'Bintang High-Rise Data Center', 'spk_number' => 'SPK-2025-044',
                'client' => 'PT Telematika Nusantara', 'project_manager' => 'Fajar Nugroho', 'location' => 'Jakarta',
                'contract_value' => 8000000000, 'start_date' => '2025-06-01', 'bast_date' => '2026-05-30',
                'remaining_days' => 0, 'progress' => 100, 'cpi' => 1.02, 'spi' => 1.00,
                'eac' => 7900000000, 'status' => 'COMPLETED',
                'termin_diterima' => 8000000000, 'last_updated' => '2026-05-30',
            ],
            [
                'project_id' => 'PRJ-006', 'project_name' => 'Surabaya Smart City Flyover', 'spk_number' => 'SPK-2026-004',
                'client' => 'Pemerintah Kota Surabaya', 'project_manager' => 'Andi Pratama', 'location' => 'Surabaya',
                'contract_value' => 11000000000, 'start_date' => '2026-02-20', 'bast_date' => '2026-12-20',
                'remaining_days' => 180, 'progress' => 58, 'cpi' => 1.01, 'spi' => 0.99,
                'eac' => 10600000000, 'status' => 'ON TRACK',
                'termin_diterima' => 4800000000, 'last_updated' => '2026-08-03',
            ],
        ];

        foreach (session()->get('project_overrides', []) as $projectId => $override) {
            foreach ($projects as $index => $project) {
                if ($project['project_id'] === $projectId) {
                    $projects[$index] = array_merge($project, $override);
                    break;
                }
            }
        }

        return $projects;
    }

    public static function find(string $projectId): ?array
    {
        foreach (self::all() as $project) {
            if ($project['project_id'] === $projectId) {
                return $project;
            }
        }
        return null;
    }

    public static function update(string $projectId, array $changes): void
    {
        $overrides = session()->get('project_overrides', []);
        $overrides[$projectId] = array_merge($overrides[$projectId] ?? [], $changes);
        session()->put('project_overrides', $overrides);
    }

    public static function history(): array
    {
        return [
            'PRJ-001' => [
                ['date' => '2026-08-05', 'user' => 'Andi Pratama', 'action' => 'Progress Updated', 'old' => '68%', 'new' => '75%'],
                ['date' => '2026-07-20', 'user' => 'Koordinator (Sinta)', 'action' => 'Addendum Approved', 'old' => 'Vol. 1,200 m³', 'new' => 'Vol. 1,350 m³'],
                ['date' => '2026-03-02', 'user' => 'Andi Pratama', 'action' => 'Schedule Updated', 'old' => 'Time Schedule v1', 'new' => 'Time Schedule v2'],
                ['date' => '2026-01-12', 'user' => 'System', 'action' => 'Project Created', 'old' => '—', 'new' => 'PRJ-001 initialized'],
            ],
            'PRJ-002' => [
                ['date' => '2026-08-01', 'user' => 'Budi Santoso', 'action' => 'Status Changed', 'old' => 'ON TRACK', 'new' => 'AT RISK'],
                ['date' => '2026-06-14', 'user' => 'Budi Santoso', 'action' => 'Progress Updated', 'old' => '35%', 'new' => '42%'],
                ['date' => '2026-02-01', 'user' => 'System', 'action' => 'Project Created', 'old' => '—', 'new' => 'PRJ-002 initialized'],
            ],
            'PRJ-003' => [
                ['date' => '2026-08-08', 'user' => 'Dimas Wijaya', 'action' => 'Progress Updated', 'old' => '85%', 'new' => '92%'],
                ['date' => '2025-11-10', 'user' => 'System', 'action' => 'Project Created', 'old' => '—', 'new' => 'PRJ-003 initialized'],
            ],
            'PRJ-004' => [
                ['date' => '2026-08-09', 'user' => 'Koordinator (Sinta)', 'action' => 'Status Changed', 'old' => 'AT RISK', 'new' => 'CRITICAL'],
                ['date' => '2026-07-01', 'user' => 'Rizky Ramadhan', 'action' => 'Project Manager Changed', 'old' => 'Ir. Irfan Wijaya', 'new' => 'Rizky Ramadhan'],
                ['date' => '2026-03-01', 'user' => 'System', 'action' => 'Project Created', 'old' => '—', 'new' => 'PRJ-004 initialized'],
            ],
            'PRJ-005' => [
                ['date' => '2026-05-30', 'user' => 'Fajar Nugroho', 'action' => 'Status Changed', 'old' => 'ON TRACK', 'new' => 'COMPLETED'],
                ['date' => '2025-06-01', 'user' => 'System', 'action' => 'Project Created', 'old' => '—', 'new' => 'PRJ-005 initialized'],
            ],
            'PRJ-006' => [
                ['date' => '2026-08-03', 'user' => 'Andi Pratama', 'action' => 'Contract Value Updated', 'old' => 'Rp 10,5 Miliar', 'new' => 'Rp 11 Miliar'],
                ['date' => '2026-02-20', 'user' => 'System', 'action' => 'Project Created', 'old' => '—', 'new' => 'PRJ-006 initialized'],
            ],
        ];
    }

    public static function historyFor(string $projectId): array
    {
        return self::history()[$projectId] ?? [];
    }

    public static function addendums(): array
    {
        return [
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

    public static function addendumsFor(string $projectId): array
    {
        $addendums = array_values(array_filter(self::addendums(), fn($a) => $a['project_id'] === $projectId));
        return session()->get('project_addendum_overrides', [])[$projectId] ?? $addendums;
    }

    public static function updateAddendums(string $projectId, array $addendums): void
    {
        $overrides = session()->get('project_addendum_overrides', []);
        $overrides[$projectId] = array_values($addendums);
        session()->put('project_addendum_overrides', $overrides);
    }

    /**
     * BOQ Items (Rencana Anggaran Biaya) per project.
     * Structure: No, Item Pekerjaan, Satuan, Volume, Harga Satuan, Subtotal
     */
    public static function boqItems(): array
    {
        return [
            'PRJ-001' => [
                ['no' => 1, 'item' => 'Mobilisasi & Demobilisasi', 'satuan' => 'Ls', 'volume' => 1, 'harga_satuan' => 350000000, 'subtotal' => 350000000],
                ['no' => 2, 'item' => 'Pekerjaan Pondasi Spun Pile', 'satuan' => 'm³', 'volume' => 1350, 'harga_satuan' => 2800000, 'subtotal' => 3780000000],
                ['no' => 3, 'item' => 'Pekerjaan Struktur Beton', 'satuan' => 'm³', 'volume' => 850, 'harga_satuan' => 3200000, 'subtotal' => 2720000000],
                ['no' => 4, 'item' => 'Pekerjaan Baja Tulangan', 'satuan' => 'kg', 'volume' => 125000, 'harga_satuan' => 18500, 'subtotal' => 2312500000],
                ['no' => 5, 'item' => 'Pekerjaan Facade & Kaca', 'satuan' => 'm²', 'volume' => 3200, 'harga_satuan' => 650000, 'subtotal' => 2080000000],
                ['no' => 6, 'item' => 'Instalasi MEP', 'satuan' => 'Ls', 'volume' => 1, 'harga_satuan' => 1257500000, 'subtotal' => 1257500000],
            ],
            'PRJ-002' => [
                ['no' => 1, 'item' => 'Pekerjaan Tanah & Galian', 'satuan' => 'm³', 'volume' => 45000, 'harga_satuan' => 85000, 'subtotal' => 3825000000],
                ['no' => 2, 'item' => 'Sub-base & Base Course', 'satuan' => 'm³', 'volume' => 12000, 'harga_satuan' => 320000, 'subtotal' => 3840000000],
                ['no' => 3, 'item' => 'Pekerjaan Aspal', 'satuan' => 'm²', 'volume' => 28000, 'harga_satuan' => 185000, 'subtotal' => 5180000000],
                ['no' => 4, 'item' => 'Saluran Drainase', 'satuan' => 'm\'', 'volume' => 5200, 'harga_satuan' => 450000, 'subtotal' => 2340000000],
                ['no' => 5, 'item' => 'Penerangan & Utilitas', 'satuan' => 'Ls', 'volume' => 1, 'harga_satuan' => 2815000000, 'subtotal' => 2815000000],
            ],
            'PRJ-003' => [
                ['no' => 1, 'item' => 'Demolisi Struktur Lama', 'satuan' => 'Ls', 'volume' => 1, 'harga_satuan' => 850000000, 'subtotal' => 850000000],
                ['no' => 2, 'item' => 'Bore Pile Pondasi', 'satuan' => 'titik', 'volume' => 48, 'harga_satuan' => 45000000, 'subtotal' => 2160000000],
                ['no' => 3, 'item' => 'Girder Baja', 'satuan' => 'ton', 'volume' => 320, 'harga_satuan' => 12500000, 'subtotal' => 4000000000],
                ['no' => 4, 'item' => 'Lantai Jembatan', 'satuan' => 'm²', 'volume' => 4800, 'harga_satuan' => 456250, 'subtotal' => 2190000000],
            ],
            'PRJ-004' => [
                ['no' => 1, 'item' => 'Pekerjaan Galian Tunnel', 'satuan' => 'm³', 'volume' => 85000, 'harga_satuan' => 95000, 'subtotal' => 8075000000],
                ['no' => 2, 'item' => 'Shotcrete & Liner', 'satuan' => 'm²', 'volume' => 18000, 'harga_satuan' => 280000, 'subtotal' => 5040000000],
                ['no' => 3, 'item' => 'Sistem Rail & Sinyal', 'satuan' => 'km', 'volume' => 12, 'harga_satuan' => 550000000, 'subtotal' => 6600000000],
                ['no' => 4, 'item' => 'Stasiun & Fasilitas', 'satuan' => 'unit', 'volume' => 3, 'harga_satuan' => 1428333333, 'subtotal' => 4285000000],
            ],
            'PRJ-005' => [
                ['no' => 1, 'item' => 'Struktur Bangunan', 'satuan' => 'm²', 'volume' => 6500, 'harga_satuan' => 580000, 'subtotal' => 3770000000],
                ['no' => 2, 'item' => 'Raised Floor System', 'satuan' => 'm²', 'volume' => 4200, 'harga_satuan' => 350000, 'subtotal' => 1470000000],
                ['no' => 3, 'item' => 'Cooling System (HVAC)', 'satuan' => 'Ls', 'volume' => 1, 'harga_satuan' => 1560000000, 'subtotal' => 1560000000],
                ['no' => 4, 'item' => 'Power & UPS System', 'satuan' => 'Ls', 'volume' => 1, 'harga_satuan' => 1200000000, 'subtotal' => 1200000000],
            ],
            'PRJ-006' => [
                ['no' => 1, 'item' => 'Pekerjaan Pondasi Pier', 'satuan' => 'titik', 'volume' => 36, 'harga_satuan' => 85000000, 'subtotal' => 3060000000],
                ['no' => 2, 'item' => 'Pier Column', 'satuan' => 'unit', 'volume' => 36, 'harga_satuan' => 62500000, 'subtotal' => 2250000000],
                ['no' => 3, 'item' => 'Steel Box Girder', 'satuan' => 'ton', 'volume' => 280, 'harga_satuan' => 13500000, 'subtotal' => 3780000000],
                ['no' => 4, 'item' => 'Deck Slab & Overlay', 'satuan' => 'm²', 'volume' => 8500, 'harga_satuan' => 224706, 'subtotal' => 1910000000],
            ],
        ];
    }

    public static function boqItemsFor(string $projectId): array
    {
        return session()->get('project_boq_overrides', [])[$projectId]
            ?? (self::boqItems()[$projectId] ?? []);
    }

    public static function updateBoqItems(string $projectId, array $items): void
    {
        $overrides = session()->get('project_boq_overrides', []);
        $overrides[$projectId] = array_values($items);
        session()->put('project_boq_overrides', $overrides);
    }

    /**
     * Weekly progress data per project (Kurva S Rencana & Realisasi).
     * Structure: week, start_date, end_date, plan_pct (rencana %), cumulative_plan_pct, actual_pct (realisasi %), cumulative_actual_pct
     */
    public static function weeklyProgress(): array
    {
        return [
            'PRJ-001' => [
                ['week' => 1, 'start_date' => '2026-01-12', 'end_date' => '2026-01-18', 'plan_pct' => 0.50, 'cumulative_plan_pct' => 0.50, 'actual_pct' => 0.45, 'cumulative_actual_pct' => 0.45],
                ['week' => 2, 'start_date' => '2026-01-19', 'end_date' => '2026-01-25', 'plan_pct' => 1.00, 'cumulative_plan_pct' => 1.50, 'actual_pct' => 0.90, 'cumulative_actual_pct' => 1.35],
                ['week' => 3, 'start_date' => '2026-01-26', 'end_date' => '2026-02-01', 'plan_pct' => 1.50, 'cumulative_plan_pct' => 3.00, 'actual_pct' => 1.40, 'cumulative_actual_pct' => 2.75],
                ['week' => 4, 'start_date' => '2026-02-02', 'end_date' => '2026-02-08', 'plan_pct' => 2.00, 'cumulative_plan_pct' => 5.00, 'actual_pct' => 1.85, 'cumulative_actual_pct' => 4.60],
                ['week' => 5, 'start_date' => '2026-02-09', 'end_date' => '2026-02-15', 'plan_pct' => 2.50, 'cumulative_plan_pct' => 7.50, 'actual_pct' => 2.40, 'cumulative_actual_pct' => 7.00],
                ['week' => 6, 'start_date' => '2026-02-16', 'end_date' => '2026-02-22', 'plan_pct' => 3.00, 'cumulative_plan_pct' => 10.50, 'actual_pct' => 2.80, 'cumulative_actual_pct' => 9.80],
                ['week' => 7, 'start_date' => '2026-02-23', 'end_date' => '2026-03-01', 'plan_pct' => 3.50, 'cumulative_plan_pct' => 14.00, 'actual_pct' => 3.20, 'cumulative_actual_pct' => 13.00],
                ['week' => 8, 'start_date' => '2026-03-02', 'end_date' => '2026-03-08', 'plan_pct' => 3.80, 'cumulative_plan_pct' => 17.80, 'actual_pct' => 3.70, 'cumulative_actual_pct' => 16.70],
                ['week' => 9, 'start_date' => '2026-03-09', 'end_date' => '2026-03-15', 'plan_pct' => 4.20, 'cumulative_plan_pct' => 22.00, 'actual_pct' => 4.00, 'cumulative_actual_pct' => 20.70],
                ['week' => 10, 'start_date' => '2026-03-16', 'end_date' => '2026-03-22', 'plan_pct' => 4.50, 'cumulative_plan_pct' => 26.50, 'actual_pct' => 4.30, 'cumulative_actual_pct' => 25.00],
                ['week' => 11, 'start_date' => '2026-03-23', 'end_date' => '2026-03-29', 'plan_pct' => 4.80, 'cumulative_plan_pct' => 31.30, 'actual_pct' => 4.50, 'cumulative_actual_pct' => 29.50],
                ['week' => 12, 'start_date' => '2026-03-30', 'end_date' => '2026-04-05', 'plan_pct' => 5.00, 'cumulative_plan_pct' => 36.30, 'actual_pct' => 4.80, 'cumulative_actual_pct' => 34.30],
                ['week' => 13, 'start_date' => '2026-04-06', 'end_date' => '2026-04-12', 'plan_pct' => 4.70, 'cumulative_plan_pct' => 41.00, 'actual_pct' => 4.70, 'cumulative_actual_pct' => 39.00],
                ['week' => 14, 'start_date' => '2026-04-13', 'end_date' => '2026-04-19', 'plan_pct' => 4.50, 'cumulative_plan_pct' => 45.50, 'actual_pct' => 4.20, 'cumulative_actual_pct' => 43.20],
                ['week' => 15, 'start_date' => '2026-04-20', 'end_date' => '2026-04-26', 'plan_pct' => 4.20, 'cumulative_plan_pct' => 49.70, 'actual_pct' => 4.00, 'cumulative_actual_pct' => 47.20],
                ['week' => 16, 'start_date' => '2026-04-27', 'end_date' => '2026-05-03', 'plan_pct' => 3.80, 'cumulative_plan_pct' => 53.50, 'actual_pct' => 3.80, 'cumulative_actual_pct' => 51.00],
                ['week' => 17, 'start_date' => '2026-05-04', 'end_date' => '2026-05-10', 'plan_pct' => 3.50, 'cumulative_plan_pct' => 57.00, 'actual_pct' => 3.50, 'cumulative_actual_pct' => 54.50],
                ['week' => 18, 'start_date' => '2026-05-11', 'end_date' => '2026-05-17', 'plan_pct' => 3.20, 'cumulative_plan_pct' => 60.20, 'actual_pct' => 3.20, 'cumulative_actual_pct' => 57.70],
                ['week' => 19, 'start_date' => '2026-05-18', 'end_date' => '2026-05-24', 'plan_pct' => 3.00, 'cumulative_plan_pct' => 63.20, 'actual_pct' => 3.00, 'cumulative_actual_pct' => 60.70],
                ['week' => 20, 'start_date' => '2026-05-25', 'end_date' => '2026-05-31', 'plan_pct' => 2.80, 'cumulative_plan_pct' => 66.00, 'actual_pct' => 2.80, 'cumulative_actual_pct' => 63.50],
                ['week' => 21, 'start_date' => '2026-06-01', 'end_date' => '2026-06-07', 'plan_pct' => 2.50, 'cumulative_plan_pct' => 68.50, 'actual_pct' => 2.50, 'cumulative_actual_pct' => 66.00],
                ['week' => 22, 'start_date' => '2026-06-08', 'end_date' => '2026-06-14', 'plan_pct' => 2.20, 'cumulative_plan_pct' => 70.70, 'actual_pct' => 2.20, 'cumulative_actual_pct' => 68.20],
                ['week' => 23, 'start_date' => '2026-06-15', 'end_date' => '2026-06-21', 'plan_pct' => 2.00, 'cumulative_plan_pct' => 72.70, 'actual_pct' => 2.00, 'cumulative_actual_pct' => 70.20],
                ['week' => 24, 'start_date' => '2026-06-22', 'end_date' => '2026-06-28', 'plan_pct' => 1.80, 'cumulative_plan_pct' => 74.50, 'actual_pct' => 2.30, 'cumulative_actual_pct' => 72.50],
                ['week' => 25, 'start_date' => '2026-06-29', 'end_date' => '2026-07-05', 'plan_pct' => 1.50, 'cumulative_plan_pct' => 76.00, 'actual_pct' => 2.50, 'cumulative_actual_pct' => 75.00],
            ],
            'PRJ-002' => [
                ['week' => 1, 'start_date' => '2026-02-01', 'end_date' => '2026-02-07', 'plan_pct' => 0.25, 'cumulative_plan_pct' => 0.25, 'actual_pct' => 0.20, 'cumulative_actual_pct' => 0.20],
                ['week' => 2, 'start_date' => '2026-02-08', 'end_date' => '2026-02-14', 'plan_pct' => 0.75, 'cumulative_plan_pct' => 1.00, 'actual_pct' => 0.60, 'cumulative_actual_pct' => 0.80],
                ['week' => 3, 'start_date' => '2026-02-15', 'end_date' => '2026-02-21', 'plan_pct' => 1.00, 'cumulative_plan_pct' => 2.00, 'actual_pct' => 0.85, 'cumulative_actual_pct' => 1.65],
                ['week' => 4, 'start_date' => '2026-02-22', 'end_date' => '2026-02-28', 'plan_pct' => 1.50, 'cumulative_plan_pct' => 3.50, 'actual_pct' => 1.20, 'cumulative_actual_pct' => 2.85],
                ['week' => 5, 'start_date' => '2026-03-01', 'end_date' => '2026-03-07', 'plan_pct' => 2.00, 'cumulative_plan_pct' => 5.50, 'actual_pct' => 1.65, 'cumulative_actual_pct' => 4.50],
                ['week' => 6, 'start_date' => '2026-03-08', 'end_date' => '2026-03-14', 'plan_pct' => 2.50, 'cumulative_plan_pct' => 8.00, 'actual_pct' => 2.00, 'cumulative_actual_pct' => 6.50],
                ['week' => 7, 'start_date' => '2026-03-15', 'end_date' => '2026-03-21', 'plan_pct' => 3.00, 'cumulative_plan_pct' => 11.00, 'actual_pct' => 2.50, 'cumulative_actual_pct' => 9.00],
                ['week' => 8, 'start_date' => '2026-03-22', 'end_date' => '2026-03-28', 'plan_pct' => 3.50, 'cumulative_plan_pct' => 14.50, 'actual_pct' => 2.80, 'cumulative_actual_pct' => 11.80],
                ['week' => 9, 'start_date' => '2026-03-29', 'end_date' => '2026-04-04', 'plan_pct' => 3.80, 'cumulative_plan_pct' => 18.30, 'actual_pct' => 3.20, 'cumulative_actual_pct' => 15.00],
                ['week' => 10, 'start_date' => '2026-04-05', 'end_date' => '2026-04-11', 'plan_pct' => 4.00, 'cumulative_plan_pct' => 22.30, 'actual_pct' => 3.50, 'cumulative_actual_pct' => 18.50],
                ['week' => 11, 'start_date' => '2026-04-12', 'end_date' => '2026-04-18', 'plan_pct' => 4.20, 'cumulative_plan_pct' => 26.50, 'actual_pct' => 3.50, 'cumulative_actual_pct' => 22.00],
                ['week' => 12, 'start_date' => '2026-04-19', 'end_date' => '2026-04-25', 'plan_pct' => 4.50, 'cumulative_plan_pct' => 31.00, 'actual_pct' => 3.50, 'cumulative_actual_pct' => 25.50],
                ['week' => 13, 'start_date' => '2026-04-26', 'end_date' => '2026-05-02', 'plan_pct' => 4.50, 'cumulative_plan_pct' => 35.50, 'actual_pct' => 3.50, 'cumulative_actual_pct' => 29.00],
                ['week' => 14, 'start_date' => '2026-05-03', 'end_date' => '2026-05-09', 'plan_pct' => 4.20, 'cumulative_plan_pct' => 39.70, 'actual_pct' => 3.50, 'cumulative_actual_pct' => 32.50],
                ['week' => 15, 'start_date' => '2026-05-10', 'end_date' => '2026-05-16', 'plan_pct' => 4.00, 'cumulative_plan_pct' => 43.70, 'actual_pct' => 3.50, 'cumulative_actual_pct' => 36.00],
                ['week' => 16, 'start_date' => '2026-05-17', 'end_date' => '2026-05-23', 'plan_pct' => 3.80, 'cumulative_plan_pct' => 47.50, 'actual_pct' => 3.00, 'cumulative_actual_pct' => 39.00],
                ['week' => 17, 'start_date' => '2026-05-24', 'end_date' => '2026-05-30', 'plan_pct' => 3.50, 'cumulative_plan_pct' => 51.00, 'actual_pct' => 3.00, 'cumulative_actual_pct' => 42.00],
            ],
            'PRJ-003' => [
                ['week' => 1, 'start_date' => '2025-11-10', 'end_date' => '2025-11-16', 'plan_pct' => 1.00, 'cumulative_plan_pct' => 1.00, 'actual_pct' => 1.20, 'cumulative_actual_pct' => 1.20],
                ['week' => 2, 'start_date' => '2025-11-17', 'end_date' => '2025-11-23', 'plan_pct' => 2.00, 'cumulative_plan_pct' => 3.00, 'actual_pct' => 2.30, 'cumulative_actual_pct' => 3.50],
                ['week' => 3, 'start_date' => '2025-11-24', 'end_date' => '2025-11-30', 'plan_pct' => 3.00, 'cumulative_plan_pct' => 6.00, 'actual_pct' => 3.50, 'cumulative_actual_pct' => 7.00],
                ['week' => 4, 'start_date' => '2025-12-01', 'end_date' => '2025-12-07', 'plan_pct' => 4.00, 'cumulative_plan_pct' => 10.00, 'actual_pct' => 4.50, 'cumulative_actual_pct' => 11.50],
                ['week' => 5, 'start_date' => '2025-12-08', 'end_date' => '2025-12-14', 'plan_pct' => 5.00, 'cumulative_plan_pct' => 15.00, 'actual_pct' => 5.50, 'cumulative_actual_pct' => 17.00],
            ],
            'PRJ-004' => [
                ['week' => 1, 'start_date' => '2026-03-01', 'end_date' => '2026-03-07', 'plan_pct' => 0.50, 'cumulative_plan_pct' => 0.50, 'actual_pct' => 0.30, 'cumulative_actual_pct' => 0.30],
                ['week' => 2, 'start_date' => '2026-03-08', 'end_date' => '2026-03-14', 'plan_pct' => 1.00, 'cumulative_plan_pct' => 1.50, 'actual_pct' => 0.50, 'cumulative_actual_pct' => 0.80],
                ['week' => 3, 'start_date' => '2026-03-15', 'end_date' => '2026-03-21', 'plan_pct' => 1.50, 'cumulative_plan_pct' => 3.00, 'actual_pct' => 0.80, 'cumulative_actual_pct' => 1.60],
                ['week' => 4, 'start_date' => '2026-03-22', 'end_date' => '2026-03-28', 'plan_pct' => 2.00, 'cumulative_plan_pct' => 5.00, 'actual_pct' => 1.20, 'cumulative_actual_pct' => 2.80],
                ['week' => 5, 'start_date' => '2026-03-29', 'end_date' => '2026-04-04', 'plan_pct' => 2.50, 'cumulative_plan_pct' => 7.50, 'actual_pct' => 1.50, 'cumulative_actual_pct' => 4.30],
            ],
            'PRJ-005' => [
                ['week' => 1, 'start_date' => '2025-06-01', 'end_date' => '2025-06-07', 'plan_pct' => 1.00, 'cumulative_plan_pct' => 1.00, 'actual_pct' => 1.00, 'cumulative_actual_pct' => 1.00],
                ['week' => 2, 'start_date' => '2025-06-08', 'end_date' => '2025-06-14', 'plan_pct' => 2.00, 'cumulative_plan_pct' => 3.00, 'actual_pct' => 2.10, 'cumulative_actual_pct' => 3.10],
                ['week' => 3, 'start_date' => '2025-06-15', 'end_date' => '2025-06-21', 'plan_pct' => 3.00, 'cumulative_plan_pct' => 6.00, 'actual_pct' => 3.00, 'cumulative_actual_pct' => 6.10],
            ],
            'PRJ-006' => [
                ['week' => 1, 'start_date' => '2026-02-20', 'end_date' => '2026-02-26', 'plan_pct' => 0.80, 'cumulative_plan_pct' => 0.80, 'actual_pct' => 0.70, 'cumulative_actual_pct' => 0.70],
                ['week' => 2, 'start_date' => '2026-02-27', 'end_date' => '2026-03-05', 'plan_pct' => 1.20, 'cumulative_plan_pct' => 2.00, 'actual_pct' => 1.10, 'cumulative_actual_pct' => 1.80],
                ['week' => 3, 'start_date' => '2026-03-06', 'end_date' => '2026-03-12', 'plan_pct' => 1.80, 'cumulative_plan_pct' => 3.80, 'actual_pct' => 1.70, 'cumulative_actual_pct' => 3.50],
                ['week' => 4, 'start_date' => '2026-03-13', 'end_date' => '2026-03-19', 'plan_pct' => 2.20, 'cumulative_plan_pct' => 6.00, 'actual_pct' => 2.00, 'cumulative_actual_pct' => 5.50],
                ['week' => 5, 'start_date' => '2026-03-20', 'end_date' => '2026-03-26', 'plan_pct' => 2.80, 'cumulative_plan_pct' => 8.80, 'actual_pct' => 2.50, 'cumulative_actual_pct' => 8.00],
                ['week' => 6, 'start_date' => '2026-03-27', 'end_date' => '2026-04-02', 'plan_pct' => 3.20, 'cumulative_plan_pct' => 12.00, 'actual_pct' => 3.00, 'cumulative_actual_pct' => 11.00],
            ],
        ];
    }

    public static function weeklyProgressFor(string $projectId): array
    {
        return self::weeklyProgress()[$projectId] ?? [];
    }
}
