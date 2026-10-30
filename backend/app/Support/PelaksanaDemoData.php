<?php

namespace App\Support;

/** Isolated demonstration fixture. Never a fallback for operational data. */
final class PelaksanaDemoData
{
    public static function projects(): array
    {
        return [
            ['id' => 'DEMO-01', 'code' => 'DEMO-01', 'name' => 'Pembangunan Gedung Operasional', 'location' => 'Jakarta', 'progress' => 32.5],
            ['id' => 'DEMO-02', 'code' => 'DEMO-02', 'name' => 'Peningkatan Jalan Kawasan', 'location' => 'Bekasi', 'progress' => 18.0],
        ];
    }

    public static function reports(): array
    {
        return [
            ['id' => 'DEMO-R4', 'project_id' => 'DEMO-01', 'project_name' => 'Pembangunan Gedung Operasional', 'week' => 5, 'period' => '29 Jun – 05 Jul 2026', 'status' => 'REJECTED', 'revision' => 1, 'reason' => 'Lengkapi alasan progres nol pada pekerjaan struktur.'],
            ['id' => 'DEMO-R3', 'project_id' => 'DEMO-02', 'project_name' => 'Peningkatan Jalan Kawasan', 'week' => 4, 'period' => '22 – 28 Jun 2026', 'status' => 'PENDING_APPROVAL', 'revision' => 1, 'reason' => ''],
            ['id' => 'DEMO-R2', 'project_id' => 'DEMO-01', 'project_name' => 'Pembangunan Gedung Operasional', 'week' => 4, 'period' => '22 – 28 Jun 2026', 'status' => 'APPROVED', 'revision' => 1, 'reason' => ''],
            ['id' => 'DEMO-R1', 'project_id' => 'DEMO-02', 'project_name' => 'Peningkatan Jalan Kawasan', 'week' => 5, 'period' => '29 Jun – 05 Jul 2026', 'status' => 'DRAFT', 'revision' => 1, 'reason' => ''],
        ];
    }

    public static function context(string $projectId = 'DEMO-01'): array
    {
        $project = collect(self::projects())->firstWhere('id', $projectId);
        if (!$project) {
            return [];
        }

        return [
            'id' => $project['id'], 'name' => $project['name'], 'week' => 5,
            'start' => '2026-06-29', 'end' => '2026-07-05', 'status' => 'DRAFT',
            'baseline' => 'DEMO · RAB Kontrak / TS Kontrak',
            'items' => [
                ['id' => 'DEMO-I1', 'category' => 'Pekerjaan Persiapan', 'name' => 'Mobilisasi dan persiapan lokasi', 'volume' => 1.0, 'unit' => 'Ls', 'weight' => 10.0, 'target' => 60.0, 'previous' => 20.0],
                ['id' => 'DEMO-I2', 'category' => 'Pekerjaan Tanah', 'name' => 'Galian dan pemadatan tanah', 'volume' => 450.0, 'unit' => 'm³', 'weight' => 30.0, 'target' => 65.0, 'previous' => 50.0],
                ['id' => 'DEMO-I3', 'category' => 'Pekerjaan Struktur', 'name' => 'Pengecoran beton pondasi', 'volume' => 180.0, 'unit' => 'm³', 'weight' => 60.0, 'target' => 100.0, 'previous' => 100.0],
            ],
        ];
    }
}
