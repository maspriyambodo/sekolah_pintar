<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrxBkTindakan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_bk_tindakan';

    protected $fillable = [
        'trx_bk_kasus_id',
        'deskripsi_tindakan',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function kasus(): BelongsTo
    {
        return $this->belongsTo(TrxBkKasus::class, 'trx_bk_kasus_id');
    }
}
