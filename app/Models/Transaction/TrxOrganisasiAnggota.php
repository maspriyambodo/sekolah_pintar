<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\MstOrganisasi;
use App\Models\Master\MstOrganisasiJabatan;
use App\Models\Master\MstSiswa;

class TrxOrganisasiAnggota extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_organisasi_anggota';

    protected $fillable = [
        'organisasi_id',
        'siswa_id',
        'jabatan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function organisasi(): BelongsTo
    {
        return $this->belongsTo(MstOrganisasi::class, 'organisasi_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(MstSiswa::class, 'siswa_id');
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(MstOrganisasiJabatan::class, 'jabatan_id');
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeByOrganisasi($query, int $organisasiId)
    {
        return $query->where('organisasi_id', $organisasiId);
    }

    public function scopeBySiswa($query, int $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }
}
