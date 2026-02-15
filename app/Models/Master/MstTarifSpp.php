<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class MstTarifSpp extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $table = 'mst_tarif_spp';

    protected $fillable = [
        'mst_kelas_id',
        'tahun_ajaran',
        'nominal',
        'keterangan',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
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
