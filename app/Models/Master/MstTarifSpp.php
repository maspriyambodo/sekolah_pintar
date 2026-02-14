<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

class MstTarifSpp extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'mst_tarif_spp';

    protected $fillable = [
        'mst_kelas_id',
        'tahun_ajaran',
        'nominal',
        'keterangan',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(MstKelas::class, 'mst_kelas_id');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(\App\Models\Transaction\TrxPembayaranSpp::class, 'mst_tarif_spp_id');
    }
}
