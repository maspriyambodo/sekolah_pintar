<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\MstSiswa;

class TrxAbsensiSiswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_absensi_siswa';

    protected $fillable = [
        'mst_siswa_id',
        'tanggal',
        'status',
        'keterangan',
        'deleted_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => 'string',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(MstSiswa::class, 'mst_siswa_id');
    }
}
