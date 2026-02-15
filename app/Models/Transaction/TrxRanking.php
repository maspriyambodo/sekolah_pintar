<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\MstKelas;

class TrxRanking extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_ranking';

    protected $fillable = [
        'trx_rapor_id',
        'mst_kelas_id',
        'peringkat',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'peringkat' => 'integer',
    ];

    public function rapor(): BelongsTo
    {
        return $this->belongsTo(TrxRapor::class, 'trx_rapor_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(MstKelas::class, 'mst_kelas_id');
    }
}
