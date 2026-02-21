<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
use App\Models\Transaction\TrxOrganisasiAnggota;

class MstOrganisasiJabatan extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'mst_organisasi_jabatan';

    protected $fillable = [
        'nama',
        'deskripsi',
        'urutan',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function anggota(): HasMany
    {
        return $this->hasMany(TrxOrganisasiAnggota::class, 'jabatan_id');
    }

    public function anggotaAktif(): HasMany
    {
        return $this->hasMany(TrxOrganisasiAnggota::class, 'jabatan_id')
            ->where('status', 'aktif');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan', 'asc');
    }
}
