<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrxBkHasil extends Model
{
    use HasFactory;

    protected $table = 'trx_bk_hasil';

    protected $fillable = [
        'trx_bk_kasus_id',
        'hasil',
        'rekomendasi',
    ];

    public function kasus(): BelongsTo
    {
        return $this->belongsTo(TrxBkKasus::class, 'trx_bk_kasus_id');
    }
}
