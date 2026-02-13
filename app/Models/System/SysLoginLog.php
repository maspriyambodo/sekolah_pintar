<?php

declare(strict_types=1);

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SysLoginLog extends Model
{
    use HasFactory;

    protected $table = 'sys_login_logs';

    public $timestamps = false;

    protected $fillable = [
        'sys_user_id',
        'email',
        'status',
        'ip_address',
        'user_agent',
        'login_at',
    ];

    protected $casts = [
        'login_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(SysUser::class, 'sys_user_id');
    }
}
