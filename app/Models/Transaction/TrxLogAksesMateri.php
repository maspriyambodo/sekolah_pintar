<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrxLogAksesMateri extends Model
{
    use HasFactory;

    protected $table = 'trx_log_akses_materi';

    protected $fillable = [
        'mst_materi_id',
        'mst_siswa_id',
        'waktu_akses',
        'durasi_detik',
        'perangkat',
    ];

    protected $casts = [
        'waktu_akses' => 'datetime',
        'durasi_detik' => 'integer',
    ];

    public function materi(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Master\MstMateri::class, 'mst_materi_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Master\MstSiswa::class, 'mst_siswa_id');
    }
}
