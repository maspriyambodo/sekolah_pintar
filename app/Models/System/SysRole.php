<?php

declare(strict_types=1);

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SysRole extends Model
{
    use HasFactory;

    protected $table = 'sys_roles';

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(SysUser::class, 'sys_user_roles', 'sys_role_id', 'sys_user_id')
            ->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(SysPermission::class, 'sys_role_permissions', 'sys_role_id', 'sys_permission_id')
            ->withTimestamps();
    }

    public function hasPermission(string $permissionCode): bool
    {
        return $this->permissions()->where('code', $permissionCode)->exists();
    }
}
