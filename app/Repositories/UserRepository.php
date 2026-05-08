<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository
{
    public function getAll(): Collection
    {
        return User::all();
    }

    public function getPaginated(int $perPage = 20, ?string $search = null): LengthAwarePaginator
    {
        $query = User::with('department_rel')
            ->orderBy('firstname');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('emp_code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function findByEmployeeCode(string $code): ?User
    {
        return User::where('emp_code', $code)->first();
    }

    public function update(int $id, array $data): bool
    {
        $user = User::find($id);
        if ($user) {
            return $user->update($data);
        }
        return false;
    }

    public function getActiveUsers(): Collection
    {
        return User::where('status', 'active')
            ->select('id', 'firstname', 'lastname', 'dept_id')
            ->with('department_rel')
            ->orderBy('firstname')
            ->get();
    }
}
