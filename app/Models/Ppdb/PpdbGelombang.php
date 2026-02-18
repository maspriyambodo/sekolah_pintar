<?php

declare(strict_types=1);

namespace App\Models\Ppdb;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\MstSekolah;
use App\Models\Ppdb\PpdbPendaftaran;

class PpdbGelombang extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ppdb_gelombang';

    protected $fillable = [
        'mst_sekolah_id',
        'nama_gelombang',
        'tahun_ajaran',
        'tgl_mulai',
        'tgl_selesai',
        'biaya_pendaftaran',
        'is_active',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'mst_sekolah_id' => 'integer',
        'tgl_mulai' => 'date',
        'tgl_selesai' => 'date',
        'biaya_pendaftaran' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(MstSekolah::class, 'mst_sekolah_id');
    }

    public function pendaftars(): HasMany
    {
        return $this->hasMany(PpdbPendaftaran::class, 'ppdb_gelombang_id');
    }

    public function isActive(): bool
    {
        $now = now()->toDateString();
        return $this->is_active && 
               $now >= $this->tgl_mulai->toDateString() && 
               $now <= $this->tgl_selesai->toDateString();
    }
}
