<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\MstBkJenis;
use App\Models\Master\MstBkKategori;
use App\Models\Master\MstGuru;
use App\Models\Master\MstSiswa;

class TrxBkKasus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_bk_kasus';

    protected $fillable = [
        'mst_siswa_id',
        'mst_guru_id',
        'mst_bk_kategori_id',
        'mst_bk_jenis_id',
        'judul_kasus',
        'deskripsi_masalah',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'status' => 'string',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(MstSiswa::class, 'mst_siswa_id');
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(MstGuru::class, 'mst_guru_id');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(MstBkKategori::class, 'mst_bk_kategori_id');
    }

    public function jenis(): BelongsTo
    {
        return $this->belongsTo(MstBkJenis::class, 'mst_bk_jenis_id');
    }

    public function hasil(): HasMany
    {
        return $this->hasMany(TrxBkHasil::class, 'trx_bk_kasus_id');
    }

    public function sesi(): HasMany
    {
        return $this->hasMany(TrxBkSesi::class, 'trx_bk_kasus_id');
    }

    public function tindakan(): HasMany
    {
        return $this->hasMany(TrxBkTindakan::class, 'trx_bk_kasus_id');
    }

    public function lampiran(): HasMany
    {
        return $this->hasMany(TrxBkLampiran::class, 'trx_bk_kasus_id');
    }

    public function wali(): HasMany
    {
        return $this->hasMany(TrxBkWali::class, 'trx_bk_kasus_id');
    }
}
