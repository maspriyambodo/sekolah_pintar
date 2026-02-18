<?php

declare(strict_types=1);

namespace App\Models\Ppdb;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PpdbDokumen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ppdb_dokumen';

    protected $fillable = [
        'ppdb_pendaftar_id',
        'jenis_dokumen',
        'file_path',
        'verifikasi_status',
        'catatan_admin',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'ppdb_pendaftar_id' => 'integer',
        'verifikasi_status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(PpdbPendaftaran::class, 'ppdb_pendaftar_id');
    }

    public function isVerified(): bool
    {
        return $this->verifikasi_status === true;
    }
}
