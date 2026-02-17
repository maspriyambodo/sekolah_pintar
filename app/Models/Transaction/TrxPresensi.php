<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrxPresensi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_presensi';

    protected $fillable = [
        'mst_guru_mapel_id',
        'mst_siswa_id',
        'tanggal',
        'jam_masuk',
        'status',
        'keterangan',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam_masuk' => 'datetime:H:i:s',
        'status' => 'integer',
    ];

    public function guruMapel(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Master\MstGuruMapel::class, 'mst_guru_mapel_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Master\MstSiswa::class, 'mst_siswa_id');
    }
}
