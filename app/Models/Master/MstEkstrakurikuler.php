<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
use App\Models\Transaction\TrxEkstrakurikulerSiswa;

class MstEkstrakurikuler extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'mst_ekstrakurikuler';

    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'pembina_guru_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'lokasi',
        'status',
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function pembina(): BelongsTo
    {
        return $this->belongsTo(MstGuru::class, 'pembina_guru_id');
    }

    public function siswa(): HasMany
    {
        return $this->hasMany(TrxEkstrakurikulerSiswa::class, 'ekstrakurikuler_id');
    }

    public function siswaAktif(): HasMany
    {
        return $this->hasMany(TrxEkstrakurikulerSiswa::class, 'ekstrakurikuler_id')
            ->where('status', 'aktif');
    }

    public function getJadwalAttribute(): string
    {
        $jadwal = $this->hari ?? '-';
        if ($this->jam_mulai && $this->jam_selesai) {
            $jadwal .= ' (' . $this->jam_mulai->format('H:i') . ' - ' . $this->jam_selesai->format('H:i') . ')';
        }
        return $jadwal;
    }
}
