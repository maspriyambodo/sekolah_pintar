<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\System\SysPermission;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionService
{
    public function getAllPermissions(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = SysPermission::query();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('slug', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('module', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('name')->cursorPaginate($perPage);
    }

    public function getPermissionById(int $id): ?SysPermission
    {
        return SysPermission::find($id);
    }

    public function createPermission(array $data): SysPermission
    {
        return DB::transaction(function () use ($data) {
            $permission = SysPermission::create([
                'name' => $data['name'],
                'slug' => $data['slug'] ?? \Illuminate\Support\Str::slug($data['name']),
                'module' => $data['module'] ?? null,
                'description' => $data['description'] ?? null,
            ]);

            Log::info('Permission created', ['permission_id' => $permission->id]);
            return $permission;
        });
    }

    public function updatePermission(int $id, array $data): SysPermission
    {
        return DB::transaction(function () use ($id, $data) {
            $permission = SysPermission::findOrFail($id);
            $permission->update([
                'name' => $data['name'] ?? $permission->name,
                'slug' => $data['slug'] ?? $permission->slug,
                'module' => $data['module'] ?? $permission->module,
                'description' => $data['description'] ?? $permission->description,
            ]);

            Log::info('Permission updated', ['permission_id' => $id]);
            return $permission;
        });
    }

    public function deletePermission(int $id): bool
    {
        $permission = SysPermission::find($id);
        if (!$permission) {
            return false;
        }

        $result = $permission->delete();
        Log::info('Permission deleted', ['permission_id' => $id]);
        return $result;
    }
}
