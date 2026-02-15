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
        'deleted_at',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'stok' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function peminjaman(): HasMany
    {
        return $this->hasMany(TrxPeminjamanBuku::class, 'mst_buku_id');
    }

    public function isAvailable(): bool
    {
        $dipinjam = $this->peminjaman()->where('status', 'dipinjam')->count();
        return $this->stok > $dipinjam;
    }

    public function availableStock(): int
    {
        $dipinjam = $this->peminjaman()->where('status', 'dipinjam')->count();
        return max(0, $this->stok - $dipinjam);
    }
}
