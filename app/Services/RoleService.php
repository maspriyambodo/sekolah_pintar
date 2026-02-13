<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\System\SysRole;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RoleService
{
    public function getAllRoles(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = SysRole::query();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('slug', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('name')->cursorPaginate($perPage);
    }

    public function getRoleById(int $id): ?SysRole
    {
        return SysRole::with('permissions')->find($id);
    }

    public function createRole(array $data): SysRole
    {
        return DB::transaction(function () use ($data) {
            $role = SysRole::create([
                'name' => $data['name'],
                'slug' => $data['slug'] ?? \Illuminate\Support\Str::slug($data['name']),
                'description' => $data['description'] ?? null,
            ]);

            if (!empty($data['permission_ids'])) {
                $role->permissions()->sync($data['permission_ids']);
            }

            Log::info('Role created', ['role_id' => $role->id]);
            return $role;
        });
    }

    public function updateRole(int $id, array $data): SysRole
    {
        return DB::transaction(function () use ($id, $data) {
            $role = SysRole::findOrFail($id);
            $role->update([
                'name' => $data['name'] ?? $role->name,
                'slug' => $data['slug'] ?? $role->slug,
                'description' => $data['description'] ?? $role->description,
            ]);

            Log::info('Role updated', ['role_id' => $id]);
            return $role;
        });
    }

    public function deleteRole(int $id): bool
    {
        $role = SysRole::find($id);
        if (!$role) {
            return false;
        }

        $result = $role->delete();
        Log::info('Role deleted', ['role_id' => $id]);
        return $result;
    }

    public function getRolePermissions(int $id): array
    {
        $role = SysRole::with('permissions')->find($id);

        if (!$role) {
            return [];
        }

        return [
            'role' => [
                'id' => $role->id,
                'name' => $role->name,
                'slug' => $role->slug,
            ],
            'permissions' => $role->permissions->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'module' => $p->module,
                ];
            }),
        ];
    }

    public function assignPermissions(int $id, array $permissionIds): SysRole
    {
        return DB::transaction(function () use ($id, $permissionIds) {
            $role = SysRole::findOrFail($id);
            $role->permissions()->sync($permissionIds);

            Log::info('Permissions assigned to role', ['role_id' => $id]);
            return $role->fresh('permissions');
        });
    }
}
