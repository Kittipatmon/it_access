<?php

namespace App\Repositories;

use App\Models\RequestForm;
use Illuminate\Database\Eloquent\Collection;

class RequestFormRepository
{
    public function getAll(): Collection
    {
        return RequestForm::with(['user', 'steps'])->latest()->get();
    }

    public function findById(int $id): ?RequestForm
    {
        return RequestForm::with(['user', 'steps.approver', 'histories.user'])->find($id);
    }

    public function findByRequestNo(string $requestNo): ?RequestForm
    {
        return RequestForm::with(['user', 'steps.approver', 'histories.user'])->where('request_no', $requestNo)->first();
    }

    public function create(array $data): RequestForm
    {
        return RequestForm::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $request = RequestForm::find($id);
        if ($request) {
            return $request->update($data);
        }
        return false;
    }

    public function getByUserId(int $userId): Collection
    {
        return RequestForm::where('user_id', $userId)->latest()->get();
    }

    public function getInvolvedRequests(int $userId): Collection
    {
        return RequestForm::where('user_id', $userId)
            ->orWhereHas('steps', function ($query) use ($userId) {
                $query->where('approver_id', $userId);
            })
            ->with(['user', 'steps.approver'])
            ->latest()
            ->get();
    }
}
