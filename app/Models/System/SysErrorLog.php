<?php

declare(strict_types=1);

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SysErrorLog extends Model
{
    use HasFactory;

    protected $table = 'sys_error_logs';

    public $timestamps = false;

    protected $fillable = [
        'level',
        'message',
        'file',
        'line',
        'trace',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'line' => 'integer',
    ];
}
