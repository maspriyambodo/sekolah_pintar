<?php

declare(strict_types=1);

namespace App\Models\Ppdb;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\MstSekolah;

class PpdbPendaftaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ppdb_pendaftar';

    protected $fillable = [
        'mst_sekolah_id',
        'ppdb_gelombang_id',
        'no_pendaftaran',
        'nama_lengkap',
        'email',
        'password',
        'nisn',
        'jenis_kelamin',
        'telp_hp',
        'asal_sekolah',
        'status_pendaftaran',
        'pilihan_jurusan_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'mst_sekolah_id' => 'integer',
        'ppdb_gelombang_id' => 'integer',
        'pilihan_jurusan_id' => 'integer',
        'jenis_kelamin' => 'string',
        'status_pendaftaran' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(MstSekolah::class, 'mst_sekolah_id');
    }

    public function gelombang(): BelongsTo
    {
        return $this->belongsTo(PpdbGelombang::class, 'ppdb_gelombang_id');
    }

    public function dokumens(): HasMany
    {
        return $this->hasMany(PpdbDokumen::class, 'ppdb_pendaftar_id');
    }

    public function isVerified(): bool
    {
        return $this->status_pendaftaran === 'terverifikasi';
    }

    public function isAccepted(): bool
    {
        return $this->status_pendaftaran === 'diterima';
    }
}
