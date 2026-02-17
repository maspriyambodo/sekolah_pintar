<?php

declare(strict_types=1);

namespace App\Models\Spk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpkHasil extends Model
{
    use HasFactory;

    protected $table = 'spk_hasil';

    protected $fillable = [
        'mst_siswa_id',
        'total_skor',
        'peringkat',
        'periode',
        'created_at',
    ];

    protected $casts = [
        'total_skor' => 'decimal:4',
        'peringkat' => 'integer',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Master\MstSiswa::class, 'mst_siswa_id');
    }
}
