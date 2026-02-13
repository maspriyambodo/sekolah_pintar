<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrxBkSesi extends Model
{
    use HasFactory;

    protected $table = 'trx_bk_sesi';

    protected $fillable = [
        'trx_bk_kasus_id',
        'tanggal',
        'metode',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'metode' => 'string',
    ];

    public function kasus(): BelongsTo
    {
        return $this->belongsTo(TrxBkKasus::class, 'trx_bk_kasus_id');
    }
}
