<?php

namespace App\Support;

class ApprovalMockData
{
    /**
     * Return all approval submissions.
     * Status: PENDING, APPROVED, REJECTED
     */
    public static function all(): array
    {
        return [
            [
                'submission_id'     => 'APP-2026-041',
                'project_id'        => 'PRJ-2026-001',
                'project_name'      => 'Grand Horizon Commercial Tower',
                'category'          => 'Progress Payment',
                'submitted_by_name' => 'Budi Santoso, ST',
                'submitted_by_role' => 'Project Manager',
                'submission_date'   => '2026-09-11',
                'status'            => 'PENDING',
                'reject_reason'     => null,
                'detail'            => [
                    'week'       => 36,
                    'plan_pct'   => 78.50,
                    'actual_pct' => 77.20,
                    'executor'   => 'Budi Santoso, ST',
                    'note'       => null,
                ],
            ],
            [
                'submission_id'     => 'APP-2026-040',
                'project_id'        => 'PRJ-2026-002',
                'project_name'      => 'GANDARI Industrial Logistics Park Ph.II',
                'category'          => 'Financial Addendum',
                'submitted_by_name' => 'Hendra Gunawan, M.T.',
                'submitted_by_role' => 'Project Manager',
                'submission_date'   => '2026-09-10',
                'status'            => 'PENDING',
                'reject_reason'     => null,
                'detail'            => [
                    'expense_type' => 'Pengadaan Material Tambahan (Baja Tulangan)',
                    'amount'       => 2750000000,
                    'date'         => '2026-09-09',
                    'note'         => 'Kebutuhan tambahan akibat perubahan spesifikasi teknis dari owner.',
                ],
            ],
            [
                'submission_id'     => 'APP-2026-038',
                'project_id'        => 'PRJ-2026-003',
                'project_name'      => 'Jabodetabek Metro Line Extension',
                'category'          => 'Scope Change',
                'submitted_by_name' => 'Ir. Irfan Wijaya',
                'submitted_by_role' => 'Site Engineer',
                'submission_date'   => '2026-09-08',
                'status'            => 'PENDING',
                'reject_reason'     => null,
                'detail'            => [
                    'item'      => 'Volume Galian Tunnel Segmen C',
                    'old_value' => '85.000 m³',
                    'new_value' => '96.500 m³',
                    'reason'    => 'Temuan kondisi tanah liat yang tidak terduga di Segmen C mengharuskan perluasan dimensi galian untuk menjaga stabilitas dinding terowongan.',
                ],
            ],
            [
                'submission_id'     => 'APP-2026-036',
                'project_id'        => 'PRJ-2026-005',
                'project_name'      => 'Nusantara Green Hospital Complex',
                'category'          => 'Weekly Progress',
                'submitted_by_name' => 'Siti Rahma, S.T., M.Sc.',
                'submitted_by_role' => 'Site Supervisor',
                'submission_date'   => '2026-09-06',
                'status'            => 'PENDING',
                'reject_reason'     => null,
                'detail'            => [
                    'week'       => 28,
                    'plan_pct'   => 62.00,
                    'actual_pct' => 58.50,
                    'executor'   => 'Siti Rahma, S.T., M.Sc.',
                    'note'       => 'Realisasi di bawah rencana karena curah hujan tinggi selama 3 hari menyebabkan penghentian pekerjaan beton.',
                ],
            ],
            [
                'submission_id'     => 'APP-2026-031',
                'project_id'        => 'PRJ-2026-004',
                'project_name'      => 'Surabaya Smart City Flyover',
                'category'          => 'Progress Payment',
                'submitted_by_name' => 'Budi Santoso, ST',
                'submitted_by_role' => 'Project Manager',
                'submission_date'   => '2026-09-01',
                'status'            => 'APPROVED',
                'reject_reason'     => null,
                'detail'            => [
                    'week'       => 28,
                    'plan_pct'   => 55.80,
                    'actual_pct' => 56.20,
                    'executor'   => 'Andi Pratama',
                    'note'       => null,
                ],
            ],
            [
                'submission_id'     => 'APP-2026-028',
                'project_id'        => 'PRJ-2025-089',
                'project_name'      => 'Coastal Highway Bridge Revitalization',
                'category'          => 'Financial Addendum',
                'submitted_by_name' => 'Ahmad Dahlan, S.T.',
                'submitted_by_role' => 'Project Manager',
                'submission_date'   => '2026-08-28',
                'status'            => 'REJECTED',
                'reject_reason'     => 'Dokumen pendukung tidak lengkap. Surat penawaran supplier dan berita acara survey harga pasar belum dilampirkan.',
                'detail'            => [
                    'expense_type' => 'Pekerjaan Waterproofing Tambahan Lantai Basement',
                    'amount'       => 480000000,
                    'date'         => '2026-08-27',
                    'note'         => 'Kebutuhan waterproofing tambahan ditemukan saat inspeksi rutin basement Blok B.',
                ],
            ],
        ];
    }

    /**
     * Find a single submission by its ID.
     */
    public static function find(string $submissionId): ?array
    {
        foreach (self::all() as $item) {
            if ($item['submission_id'] === $submissionId) {
                return $item;
            }
        }
        return null;
    }
}
