<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\MstMapel;

class TrxRaporDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_rapor_detail';

    protected $fillable = [
        'trx_rapor_id',
        'mst_mapel_id',
        'nilai_akhir',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'nilai_akhir' => 'decimal:2',
    ];

    public function rapor(): BelongsTo
    {
        return $this->belongsTo(TrxRapor::class, 'trx_rapor_id');
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(MstMapel::class, 'mst_mapel_id');
    }
}
