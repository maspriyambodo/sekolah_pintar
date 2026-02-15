<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\MstBuku;
use App\Models\Master\MstSiswa;

class TrxPeminjamanBuku extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_peminjaman_buku';

    protected $fillable = [
        'mst_buku_id',
        'mst_siswa_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'status' => 'integer',
    ];

    public function buku(): BelongsTo
    {
        return $this->belongsTo(MstBuku::class, 'mst_buku_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(MstSiswa::class, 'mst_siswa_id');
    }

    public function isOverdue(): bool
    {
        // Status 1 = dipinjam (sesuaikan dengan sys_references)
        if ($this->status !== 1 || !$this->tanggal_pinjam) {
            return false;
        }
        return now()->diffInDays($this->tanggal_pinjam) > 7;
    }

    public function getDendaAttribute(): float
    {
        if (!$this->isOverdue()) {
            return 0;
        }
        $hariTerlambat = now()->diffInDays($this->tanggal_pinjam) - 7;
        return $hariTerlambat * 1000; // Rp 1.000 per hari
    }
}
