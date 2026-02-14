<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Transaction\TrxRanking;
use App\Models\Transaction\TrxUjian;

class MstKelas extends Model
{
    use HasFactory;

    protected $table = 'mst_kelas';

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'tahun_ajaran',
        'wali_guru_id',
    ];

    protected $casts = [
        'tingkat' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function waliGuru(): BelongsTo
    {
        return $this->belongsTo(MstGuru::class, 'wali_guru_id');
    }

    public function siswa(): HasMany
    {
        return $this->hasMany(MstSiswa::class, 'mst_kelas_id');
    }

    public function ujian(): HasMany
    {
        return $this->hasMany(TrxUjian::class, 'mst_kelas_id');
    }

    public function ranking(): HasMany
    {
        return $this->hasMany(TrxRanking::class, 'mst_kelas_id');
    }
}
