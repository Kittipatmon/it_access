<?php

namespace App\Repositories;

use App\Models\ApprovalStep;
use App\Models\ApprovalHistory;
use Illuminate\Database\Eloquent\Collection;

class ApprovalRepository
{
    public function createStep(array $data): ApprovalStep
    {
        return ApprovalStep::create($data);
    }

    public function createHistory(array $data): ApprovalHistory
    {
        return ApprovalHistory::create($data);
    }

    public function getPendingStepsByApprover(int $approverId): Collection
    {
        return ApprovalStep::where('approver_id', $approverId)
            ->where('status', 'pending')
            ->with('requestForm.user')
            ->get();
    }

    public function findStepById(int $id): ?ApprovalStep
    {
        return ApprovalStep::with('requestForm')->find($id);
    }

    public function updateStep(int $id, array $data): bool
    {
        $step = ApprovalStep::find($id);
        if ($step) {
            return $step->update($data);
        }
        return false;
    }
}
