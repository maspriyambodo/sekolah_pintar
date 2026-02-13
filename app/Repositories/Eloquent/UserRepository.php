<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\System\SysUser;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(SysUser $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?SysUser
    {
        return Cache::tags(['users'])->remember(
            "user:email:{$email}",
            300, // 5 minutes
            fn () => $this->model->where('email', $email)->first()
        );
    }

    public function findActiveByEmail(string $email): ?SysUser
    {
        return $this->model
            ->where('email', $email)
            ->where('is_active', true)
            ->first();
    }

    public function getUsersWithRoles(): Collection
    {
        return $this->model->with(['roles', 'roles.permissions'])->get();
    }

    public function getUsersByRole(string $roleCode): Collection
    {
        return $this->model
            ->whereHas('roles', function ($query) use ($roleCode) {
                $query->where('code', $roleCode);
            })
            ->get();
    }

    public function assignRole(int $userId, int $roleId): void
    {
        $user = $this->find($userId);
        if ($user) {
            $user->roles()->attach($roleId);
            $this->clearUserCache($user);
        }
    }

    public function removeRole(int $userId, int $roleId): void
    {
        $user = $this->find($userId);
        if ($user) {
            $user->roles()->detach($roleId);
            $this->clearUserCache($user);
        }
    }

    public function syncRoles(int $userId, array $roleIds): void
    {
        $user = $this->find($userId);
        if ($user) {
            $user->roles()->sync($roleIds);
            $this->clearUserCache($user);
        }
    }

    private function clearUserCache(SysUser $user): void
    {
        Cache::tags(['users'])->forget("user:email:{$user->email}");
        Cache::tags(['users'])->forget("user:{$user->id}");
    }
}
