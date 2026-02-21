<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
use App\Models\Transaction\TrxOrganisasiAnggota;

class MstOrganisasi extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'mst_organisasi';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'pembina_guru_id',
        'periode_mulai',
        'periode_selesai',
        'status',
    ];

    protected $casts = [
        'periode_mulai' => 'integer',
        'periode_selesai' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function pembina(): BelongsTo
    {
        return $this->belongsTo(MstGuru::class, 'pembina_guru_id');
    }

    public function anggota(): HasMany
    {
        return $this->hasMany(TrxOrganisasiAnggota::class, 'organisasi_id');
    }

    public function anggotaAktif(): HasMany
    {
        return $this->hasMany(TrxOrganisasiAnggota::class, 'organisasi_id')
            ->where('status', 'aktif');
    }

    public function getPeriodeAttribute(): string
    {
        $periode = $this->periode_mulai;
        if ($this->periode_selesai) {
            $periode .= ' - ' . $this->periode_selesai;
        }
        return $periode;
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }

    public function isPeriodeAktif(): bool
    {
        $currentYear = (int) date('Y');
        if ($this->periode_mulai > $currentYear) {
            return false;
        }
        if ($this->periode_selesai && $this->periode_selesai < $currentYear) {
            return false;
        }
        return true;
    }
}
