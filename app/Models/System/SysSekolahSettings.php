<?php

declare(strict_types=1);

namespace App\Models\System;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Master\MstSekolah;

class SysSekolahSettings extends Model
{
    protected $table = 'sys_sekolah_settings';

    protected $fillable = [
        'mst_sekolah_id',
        'key',
        'value',
    ];

    protected $casts = [
        'mst_sekolah_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(MstSekolah::class, 'mst_sekolah_id');
    }
}
