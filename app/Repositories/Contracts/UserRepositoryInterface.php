<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\System\SysUser;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByEmail(string $email): ?SysUser;

    public function findActiveByEmail(string $email): ?SysUser;

    public function getUsersWithRoles(): Collection;

    public function getUsersByRole(string $roleCode): Collection;

    public function assignRole(int $userId, int $roleId): void;

    public function removeRole(int $userId, int $roleId): void;

    public function syncRoles(int $userId, array $roleIds): void;
}
