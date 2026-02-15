<?php

declare(strict_types=1);

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\LogsActivity;

class SysUserRole extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'sys_user_roles';

    protected $fillable = [
        'sys_user_id',
        'sys_role_id',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(SysUser::class, 'sys_user_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(SysRole::class, 'sys_role_id');
    }
}
