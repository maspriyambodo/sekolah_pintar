<?php

declare(strict_types=1);

namespace App\Models\Spk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpkPenilaian extends Model
{
    use HasFactory;

    protected $table = 'spk_penilaian';

    protected $fillable = [
        'mst_siswa_id',
        'spk_kriteria_id',
        'nilai',
        'tahun_ajaran',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Master\MstSiswa::class, 'mst_siswa_id');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(SpkKriteria::class, 'spk_kriteria_id');
    }
}
