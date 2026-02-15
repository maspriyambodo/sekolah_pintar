<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\MstWaliMurid;

class TrxBkWali extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_bk_wali';

    protected $fillable = [
        'trx_bk_kasus_id',
        'mst_wali_murid_id',
        'peran',
        'deleted_at',
    ];

    protected $casts = [
        'peran' => 'string',
        'created_at' => 'datetime',
    ];

    public function kasus(): BelongsTo
    {
        return $this->belongsTo(TrxBkKasus::class, 'trx_bk_kasus_id');
    }

    public function waliMurid(): BelongsTo
    {
        return $this->belongsTo(MstWaliMurid::class, 'mst_wali_murid_id');
    }
}
