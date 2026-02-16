<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\System\SysUser;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function getAllUsers(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = SysUser::query()->with('roles');

        if (!empty($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('slug', $filters['role']);
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('email', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('name')->cursorPaginate($perPage);
    }

    public function getUserById(int $id): ?SysUser
    {
        return SysUser::with('roles.permissions')->find($id);
    }

    public function createUser(array $data): SysUser
    {
        return DB::transaction(function () use ($data) {
            $user = SysUser::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'is_active' => $data['is_active'] ?? true,
                'role' => $data['role'] ?? null,
            ]);

            if (!empty($data['role'])) {
                $user->roles()->sync([$data['role']]);
            }

            Log::info('User created', ['user_id' => $user->id]);
            return $user;
        });
    }

    public function updateUser(int $id, array $data): SysUser
    {
        return DB::transaction(function () use ($id, $data) {
            $user = SysUser::findOrFail($id);

            $updateData = [
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
                'is_active' => $data['is_active'] ?? $user->is_active,
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $user->update($updateData);

            Log::info('User updated', ['user_id' => $id]);
            return $user;
        });
    }

    public function deleteUser(int $id): bool
    {
        $user = SysUser::find($id);
        if (!$user) {
            return false;
        }

        $result = $user->delete();
        Log::info('User deleted', ['user_id' => $id]);
        return $result;
    }

    public function toggleActive(int $id): SysUser
    {
        return DB::transaction(function () use ($id) {
            $user = SysUser::findOrFail($id);
            $user->update(['is_active' => !$user->is_active]);

            Log::info('User status toggled', ['user_id' => $id, 'is_active' => $user->is_active]);
            return $user;
        });
    }

    public function assignRoles(int $id, array $roleIds): SysUser
    {
        return DB::transaction(function () use ($id, $roleIds) {
            $user = SysUser::findOrFail($id);
            $user->roles()->sync($roleIds);

            Log::info('Roles assigned to user', ['user_id' => $id]);
            return $user->fresh('roles');
        });
    }
}
