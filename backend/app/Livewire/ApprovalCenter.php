<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Support\ApprovalMockData;

#[Title("Approval Center - EVM Dashboard")]
class ApprovalCenter extends Component
{
    public array $submissions = [];

    public string $search = "";
    public string $statusFilter = "all";
    public string $categoryFilter = "all";

    public ?string $selectedSubmissionId = null;
    public ?array  $selectedSubmission   = null;

    public ?string $confirmApproveId   = null;
    public ?array  $confirmApproveData = null;

    public ?string $rejectSubmissionId   = null;
    public ?array  $rejectSubmissionData = null;
    public string  $rejectReason         = "";

    public function mount(): void
    {
        $this->submissions = ApprovalMockData::all();
    }

    public function showDetail(string $submissionId): void
    {
        $this->selectedSubmissionId = $submissionId;
        $this->selectedSubmission   = $this->findInState($submissionId);
        $this->dispatch("open-detail-modal");
    }

    public function closeDetail(): void
    {
        $this->selectedSubmissionId = null;
        $this->selectedSubmission   = null;
    }

    public function showApproveConfirm(string $submissionId): void
    {
        $this->confirmApproveId   = $submissionId;
        $this->confirmApproveData = $this->findInState($submissionId);
        $this->dispatch("open-approve-modal");
    }

    public function closeApproveConfirm(): void
    {
        $this->confirmApproveId   = null;
        $this->confirmApproveData = null;
    }

    public function confirmApprove(): void
    {
        if (! $this->confirmApproveId) return;

        foreach ($this->submissions as &$sub) {
            if ($sub["submission_id"] === $this->confirmApproveId) {
                $sub["status"] = "APPROVED";
                break;
            }
        }
        unset($sub);

        $id = $this->confirmApproveId;
        $this->closeApproveConfirm();
        $this->closeDetail();
        $this->dispatch("submission-approved", message: "Submission {$id} berhasil di-approve.");
    }

    public function showRejectModal(string $submissionId): void
    {
        $this->rejectSubmissionId   = $submissionId;
        $this->rejectSubmissionData = $this->findInState($submissionId);
        $this->rejectReason         = "";
        $this->dispatch("open-reject-modal");
    }

    public function closeRejectModal(): void
    {
        $this->rejectSubmissionId   = null;
        $this->rejectSubmissionData = null;
        $this->rejectReason         = "";
    }

    public function confirmReject(): void
    {
        $this->validate(
            ["rejectReason" => "required|min:10"],
            [
                "rejectReason.required" => "Alasan penolakan wajib diisi.",
                "rejectReason.min"      => "Alasan penolakan minimal 10 karakter.",
            ]
        );

        foreach ($this->submissions as &$sub) {
            if ($sub["submission_id"] === $this->rejectSubmissionId) {
                $sub["status"]        = "REJECTED";
                $sub["reject_reason"] = $this->rejectReason;
                break;
            }
        }
        unset($sub);

        $id = $this->rejectSubmissionId;
        $this->closeRejectModal();
        $this->closeDetail();
        $this->dispatch("submission-rejected", message: "Submission {$id} berhasil di-reject.");
    }

    public function handleClearFilters(): void
    {
        $this->search         = "";
        $this->statusFilter   = "all";
        $this->categoryFilter = "all";
    }

    public function render()
    {
        $q = strtolower(trim($this->search));

        $filtered = array_values(array_filter($this->submissions, function ($sub) use ($q) {
            $matchesSearch = empty($q)
                || str_contains(strtolower($sub["project_name"]), $q)
                || str_contains(strtolower($sub["submitted_by_name"]), $q)
                || str_contains(strtolower($sub["submission_id"]), $q);

            $matchesStatus   = $this->statusFilter   === "all" || $sub["status"]   === $this->statusFilter;
            $matchesCategory = $this->categoryFilter === "all" || $sub["category"] === $this->categoryFilter;

            return $matchesSearch && $matchesStatus && $matchesCategory;
        }));

        $kpiProgress  = count(array_filter($this->submissions, fn($s) =>
            in_array($s["category"], ["Progress Payment", "Weekly Progress"]) && $s["status"] === "PENDING"
        ));
        $kpiFinancial = count(array_filter($this->submissions, fn($s) =>
            $s["category"] === "Financial Addendum" && $s["status"] === "PENDING"
        ));
        $kpiAddendum  = count(array_filter($this->submissions, fn($s) =>
            $s["category"] === "Scope Change" && $s["status"] === "PENDING"
        ));

        $totalPending = count(array_filter($this->submissions, fn($s) => $s["status"] === "PENDING"));
        $totalAll     = count($this->submissions);

        return view("livewire.approval-center", [
            "filteredSubmissions" => $filtered,
            "kpiProgress"         => $kpiProgress,
            "kpiFinancial"        => $kpiFinancial,
            "kpiAddendum"         => $kpiAddendum,
            "totalPending"        => $totalPending,
            "totalAll"            => $totalAll,
        ])->layout("components.layouts.app");
    }

    private function findInState(string $submissionId): ?array
    {
        foreach ($this->submissions as $sub) {
            if ($sub["submission_id"] === $submissionId) {
                return $sub;
            }
        }
        return null;
    }
}
