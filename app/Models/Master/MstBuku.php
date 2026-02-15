<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
use App\Models\Transaction\TrxPeminjamanBuku;

class MstBuku extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'mst_buku';

    protected $fillable = [
        'isbn',
        'judul',
        'penulis',
        'penerbit',
        'tahun',
        'stok',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'stok' => 'integer',
    ];

    public function peminjaman(): HasMany
    {
        return $this->hasMany(TrxPeminjamanBuku::class, 'mst_buku_id');
    }

    public function isAvailable(): bool
    {
        // Status 1 = dipinjam (sesuaikan dengan sys_references)
        $dipinjam = $this->peminjaman()->where('status', 1)->count();
        return $this->stok > $dipinjam;
    }

    public function availableStock(): int
    {
        // Status 1 = dipinjam (sesuaikan dengan sys_references)
        $dipinjam = $this->peminjaman()->where('status', 1)->count();
        return max(0, $this->stok - $dipinjam);
    }
}
