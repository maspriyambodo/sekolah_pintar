<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Master\MstKelas;
use App\Models\Master\MstMapel;

class TrxUjian extends Model
{
    use HasFactory;

    protected $table = 'trx_ujian';

    protected $fillable = [
        'mst_mapel_id',
        'mst_kelas_id',
        'jenis',
        'semester',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jenis' => 'string',
        'semester' => 'string',
    ];

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(MstMapel::class, 'mst_mapel_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(MstKelas::class, 'mst_kelas_id');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(TrxNilai::class, 'trx_ujian_id');
    }
}
