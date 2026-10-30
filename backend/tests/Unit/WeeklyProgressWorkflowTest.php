<?php

namespace Tests\Unit;

use App\Support\WeeklyProgressWorkflow as Workflow;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WeeklyProgressWorkflowTest extends TestCase
{
    public function test_draft_can_be_submitted_by_pelaksana(): void
    {
        $this->assertTrue(Workflow::canTransition(Workflow::DRAFT, Workflow::PENDING_APPROVAL, Workflow::PELAKSANA));
    }

    public function test_admin_can_approve_or_reject_pending_with_reason(): void
    {
        $this->assertTrue(Workflow::canTransition(Workflow::PENDING_APPROVAL, Workflow::APPROVED, Workflow::ADMIN));
        $decision = Workflow::transition(Workflow::PENDING_APPROVAL, Workflow::REJECTED, Workflow::ADMIN, '  Lengkapi alasan nol.  ');
        $this->assertSame(Workflow::REJECTED, $decision['to']);
        $this->assertSame('  Lengkapi alasan nol.  ', $decision['reason']);
    }

    public function test_rejected_requires_new_revision_instead_of_direct_edit(): void
    {
        $this->assertFalse(Workflow::canEdit(Workflow::REJECTED, Workflow::PELAKSANA));
        $this->assertFalse(Workflow::canTransition(Workflow::REJECTED, Workflow::DRAFT, Workflow::PELAKSANA));
        $transition = Workflow::transition(Workflow::REJECTED, Workflow::DRAFT, Workflow::PELAKSANA, newRevision: true);
        $this->assertTrue($transition['requires_new_revision']);
        $this->assertSame(Workflow::DRAFT, $transition['to']);
    }

    #[DataProvider('forbiddenTransitions')]
    public function test_forbidden_transitions_are_denied(string $from, string $to, string $actor, ?string $reason = null, bool $revision = false): void
    {
        $this->assertFalse(Workflow::canTransition($from, $to, $actor, $reason, $revision));
        $this->expectException(InvalidArgumentException::class);
        Workflow::transition($from, $to, $actor, $reason, $revision);
    }

    public static function forbiddenTransitions(): array
    {
        return [
            [Workflow::DRAFT, Workflow::APPROVED, Workflow::ADMIN],
            [Workflow::APPROVED, Workflow::DRAFT, Workflow::PELAKSANA, null, true],
            [Workflow::APPROVED, Workflow::REJECTED, Workflow::ADMIN, 'Correction'],
            [Workflow::APPROVED, Workflow::PENDING_APPROVAL, Workflow::ADMIN],
            [Workflow::PENDING_APPROVAL, Workflow::APPROVED, Workflow::PELAKSANA],
            [Workflow::PENDING_APPROVAL, Workflow::REJECTED, Workflow::PELAKSANA, 'Reason'],
            [Workflow::PENDING_APPROVAL, Workflow::DRAFT, Workflow::PELAKSANA],
            [Workflow::PENDING_APPROVAL, Workflow::REJECTED, Workflow::ADMIN],
            [Workflow::PENDING_APPROVAL, Workflow::REJECTED, Workflow::ADMIN, '   '],
            [Workflow::PENDING_APPROVAL, Workflow::PENDING_APPROVAL, Workflow::ADMIN],
            [Workflow::DRAFT, Workflow::PENDING_APPROVAL, Workflow::ADMIN],
            [Workflow::DRAFT, Workflow::PENDING_APPROVAL, Workflow::PELAKSANA, null, true],
            [Workflow::REJECTED, Workflow::DRAFT, Workflow::ADMIN, null, true],
            ['UNKNOWN', Workflow::DRAFT, Workflow::PELAKSANA],
            [Workflow::DRAFT, 'UNKNOWN', Workflow::PELAKSANA],
            [Workflow::PENDING_APPROVAL, Workflow::APPROVED, 'UNKNOWN'],
        ];
    }

    public function test_only_pelaksana_draft_is_editable(): void
    {
        $this->assertTrue(Workflow::canEdit(Workflow::DRAFT, Workflow::PELAKSANA));
        foreach ([Workflow::PENDING_APPROVAL, Workflow::REJECTED, Workflow::APPROVED, 'UNKNOWN'] as $status) {
            $this->assertFalse(Workflow::canEdit($status, Workflow::PELAKSANA));
        }
        $this->assertFalse(Workflow::canEdit(Workflow::DRAFT, Workflow::ADMIN));
        $this->assertFalse(Workflow::canEdit(Workflow::DRAFT, 'UNKNOWN'));
    }
}
