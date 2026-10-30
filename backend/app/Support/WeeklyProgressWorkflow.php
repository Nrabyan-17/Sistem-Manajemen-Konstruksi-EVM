<?php

namespace App\Support;

use InvalidArgumentException;

/**
 * Rules only. Actor categories are explicit caller inputs, NOT authorization.
 * Never trust a browser-supplied actor category. Real authorization, assignment,
 * transactions and creation of revision records belong to later integration.
 */
final class WeeklyProgressWorkflow
{
    public const DRAFT = 'DRAFT';
    public const PENDING_APPROVAL = 'PENDING_APPROVAL';
    public const REJECTED = 'REJECTED';
    public const APPROVED = 'APPROVED';
    public const PELAKSANA = 'PELAKSANA_LAPANGAN';
    public const ADMIN = 'ADMIN_PROYEK';

    public static function canEdit(string $status, string $actorCategory): bool
    {
        return $status === self::DRAFT && $actorCategory === self::PELAKSANA;
    }

    public static function canTransition(string $from, string $to, string $actorCategory, ?string $reason = null, bool $newRevision = false): bool
    {
        try {
            self::transition($from, $to, $actorCategory, $reason, $newRevision);

            return true;
        } catch (InvalidArgumentException) {
            return false;
        }
    }

    /** Returns a transition description, never mutates a report or creates a revision. */
    public static function transition(string $from, string $to, string $actorCategory, ?string $reason = null, bool $newRevision = false): array
    {
        $revision = $from === self::REJECTED && $to === self::DRAFT;
        $allowed = match (true) {
            $from === self::DRAFT && $to === self::PENDING_APPROVAL => $actorCategory === self::PELAKSANA,
            $from === self::PENDING_APPROVAL && in_array($to, [self::APPROVED, self::REJECTED], true) => $actorCategory === self::ADMIN,
            $revision => $actorCategory === self::PELAKSANA && $newRevision,
            default => false,
        };
        if (!$allowed || ($newRevision && !$revision)) {
            throw new InvalidArgumentException('Transisi atau kategori aktor tidak diizinkan. Laporan ditolak memerlukan revisi baru.');
        }
        if ($reason !== null && $reason !== '' && !WeeklyProgressReason::hasMeaningfulText($reason)) {
            throw WeeklyProgressValidationException::forField('reason', 'Alasan harus berisi teks bermakna, bukan hanya spasi atau karakter tak terlihat.');
        }
        if ($to === self::REJECTED && ($reason === null || $reason === '')) {
            throw WeeklyProgressValidationException::forField('reason', 'Alasan penolakan wajib diisi.');
        }

        return ['from' => $from, 'to' => $to, 'actor_category' => $actorCategory, 'reason' => $reason, 'requires_new_revision' => $revision];
    }
}
