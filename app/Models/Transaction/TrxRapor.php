<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\MstSiswa;

class TrxRapor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_rapor';

    protected $fillable = [
        'mst_siswa_id',
        'semester',
        'tahun_ajaran',
        'total_nilai',
        'rata_rata',
        'deleted_at',
    ];

    protected $casts = [
        'semester' => 'string',
        'total_nilai' => 'decimal:2',
        'rata_rata' => 'decimal:2',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(MstSiswa::class, 'mst_siswa_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(TrxRaporDetail::class, 'trx_rapor_id');
    }

    public function ranking(): HasOne
    {
        return $this->hasOne(TrxRanking::class, 'trx_rapor_id');
    }
}
