<?php

declare(strict_types=1);

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SysActivityLog extends Model
{
    use HasFactory;

    protected $table = 'sys_activity_logs';

    public $timestamps = false;

    protected $fillable = [
        'sys_user_id',
        'action',
        'module',
        'reference_table',
        'reference_id',
        'description',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'reference_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(SysUser::class, 'sys_user_id');
    }
}
