<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrxBkLampiran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_bk_lampiran';

    protected $fillable = [
        'trx_bk_kasus_id',
        'file_path',
        'keterangan',
        'deleted_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function kasus(): BelongsTo
    {
        return $this->belongsTo(TrxBkKasus::class, 'trx_bk_kasus_id');
    }
}
