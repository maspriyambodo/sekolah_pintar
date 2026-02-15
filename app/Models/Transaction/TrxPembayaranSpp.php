<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use App\Models\Master\MstSiswa;
use App\Models\Master\MstTarifSpp;
use App\Models\System\SysUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class TrxPembayaranSpp extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'trx_pembayaran_spp';

    protected $fillable = [
        'mst_siswa_id',
        'mst_tarif_spp_id',
        'bulan',
        'tahun',
        'tanggal_bayar',
        'jumlah_bayar',
        'status',
        'metode_pembayaran',
        'keterangan',
        'petugas_id',
        'deleted_at',
    ];

    protected $casts = [
        'bulan' => 'integer',
        'tahun' => 'integer',
        'tanggal_bayar' => 'date',
        'jumlah_bayar' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(MstSiswa::class, 'mst_siswa_id');
    }

    public function tarifSpp(): BelongsTo
    {
        return $this->belongsTo(MstTarifSpp::class, 'mst_tarif_spp_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(SysUser::class, 'petugas_id');
    }

    public function getNamaBulanAttribute(): string
    {
        $bulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $bulan[$this->bulan] ?? '-';
    }
}
