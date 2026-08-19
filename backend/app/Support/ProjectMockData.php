<?php

namespace App\Support;

class ProjectMockData
{
    public static function all(): array
    {
        return [
            [
                'project_id' => 'PRJ-001', 'project_name' => 'Grand Horizon Tower', 'spk_number' => 'SPK-2026-001',
                'client' => 'PT ABC', 'project_manager' => 'Andi Pratama', 'location' => 'Jakarta',
                'contract_value' => 12500000000, 'start_date' => '2026-01-12', 'bast_date' => '2026-12-30',
                'remaining_days' => 45, 'progress' => 75, 'cpi' => 1.04, 'spi' => 0.98,
                'eac' => 9800000000, 'status' => 'ON TRACK',
            ],
            [
                'project_id' => 'PRJ-002', 'project_name' => 'Industrial Park Phase II', 'spk_number' => 'SPK-2026-002',
                'client' => 'PT Mitra Logistik Nusantara', 'project_manager' => 'Budi Santoso', 'location' => 'Bekasi',
                'contract_value' => 18000000000, 'start_date' => '2026-02-01', 'bast_date' => '2027-04-15',
                'remaining_days' => 120, 'progress' => 42, 'cpi' => 0.88, 'spi' => 0.85,
                'eac' => 15200000000, 'status' => 'AT RISK',
            ],
            [
                'project_id' => 'PRJ-003', 'project_name' => 'Coastal Bridge Revitalization', 'spk_number' => 'SPK-2025-089',
                'client' => 'Dinas Pekerjaan Umum', 'project_manager' => 'Dimas Wijaya', 'location' => 'Surabaya',
                'contract_value' => 9200000000, 'start_date' => '2025-11-10', 'bast_date' => '2026-08-15',
                'remaining_days' => 12, 'progress' => 92, 'cpi' => 1.12, 'spi' => 1.05,
                'eac' => 8400000000, 'status' => 'ON TRACK',
            ],
            [
                'project_id' => 'PRJ-004', 'project_name' => 'Metro Line Extension', 'spk_number' => 'SPK-2026-003',
                'client' => 'PT Kereta Api Indonesia (Persero)', 'project_manager' => 'Rizky Ramadhan', 'location' => 'Bandung',
                'contract_value' => 24000000000, 'start_date' => '2026-03-01', 'bast_date' => '2027-02-28',
                'remaining_days' => 240, 'progress' => 28, 'cpi' => 0.75, 'spi' => 0.72,
                'eac' => 21500000000, 'status' => 'CRITICAL',
            ],
            [
                'project_id' => 'PRJ-005', 'project_name' => 'Bintang High-Rise Data Center', 'spk_number' => 'SPK-2025-044',
                'client' => 'PT Telematika Nusantara', 'project_manager' => 'Fajar Nugroho', 'location' => 'Jakarta',
                'contract_value' => 8000000000, 'start_date' => '2025-06-01', 'bast_date' => '2026-05-30',
                'remaining_days' => 0, 'progress' => 100, 'cpi' => 1.02, 'spi' => 1.00,
                'eac' => 7900000000, 'status' => 'COMPLETED',
            ],
            [
                'project_id' => 'PRJ-006', 'project_name' => 'Surabaya Smart City Flyover', 'spk_number' => 'SPK-2026-004',
                'client' => 'Pemerintah Kota Surabaya', 'project_manager' => 'Andi Pratama', 'location' => 'Surabaya',
                'contract_value' => 11000000000, 'start_date' => '2026-02-20', 'bast_date' => '2026-12-20',
                'remaining_days' => 180, 'progress' => 58, 'cpi' => 1.01, 'spi' => 0.99,
                'eac' => 10600000000, 'status' => 'ON TRACK',
            ],
        ];
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
                ['date' => '2026-08-03', 'user' => 'Andi Pratama', 'action' => 'Contract Value Updated', 'old' => 'Rp 10.5B', 'new' => 'Rp 11.0B'],
                ['date' => '2026-02-20', 'user' => 'System', 'action' => 'Project Created', 'old' => '—', 'new' => 'PRJ-006 initialized'],
            ],
        ];
    }

    public static function historyFor(string $projectId): array
    {
        return self::history()[$projectId] ?? [];
    }
}
