<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\MstGuru;

class TrxAbsensiGuru extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_absensi_guru';

    protected $fillable = [
        'mst_guru_id',
        'tanggal',
        'status',
        'keterangan',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => 'integer',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(MstGuru::class, 'mst_guru_id');
    }
}
