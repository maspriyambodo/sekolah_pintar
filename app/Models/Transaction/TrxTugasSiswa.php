<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrxTugasSiswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_tugas_siswa';

    protected $fillable = [
        'mst_tugas_id',
        'mst_siswa_id',
        'jawaban_teks',
        'file_siswa',
        'waktu_kumpul',
        'nilai',
        'catatan_guru',
        'status_kumpul',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'waktu_kumpul' => 'datetime',
        'nilai' => 'decimal:2',
        'status_kumpul' => 'integer',
    ];

    public function tugas(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Master\MstTugas::class, 'mst_tugas_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Master\MstSiswa::class, 'mst_siswa_id');
    }
}
