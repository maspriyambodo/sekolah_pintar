<?php

declare(strict_types=1);

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SysPermission extends Model
{
    use HasFactory;

    protected $table = 'sys_permissions';

    protected $fillable = [
        'code',
        'name',
        'module',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(SysRole::class, 'sys_role_permissions', 'sys_permission_id', 'sys_role_id')
            ->withTimestamps();
    }
}
