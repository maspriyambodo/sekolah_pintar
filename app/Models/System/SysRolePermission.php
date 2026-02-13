<?php

declare(strict_types=1);

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SysRolePermission extends Model
{
    use HasFactory;

    protected $table = 'sys_role_permissions';

    protected $fillable = [
        'sys_role_id',
        'sys_permission_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(SysRole::class, 'sys_role_id');
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(SysPermission::class, 'sys_permission_id');
    }
}
