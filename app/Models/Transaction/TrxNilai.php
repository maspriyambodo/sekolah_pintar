<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
use App\Models\Master\MstSiswa;

class TrxNilai extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'trx_nilai';

    protected $fillable = [
        'trx_ujian_id',
        'mst_siswa_id',
        'nilai',
        'deleted_at',
    ];

    protected $casts = [
        'nilai' => 'decimal:2',
    ];

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(TrxUjian::class, 'trx_ujian_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(MstSiswa::class, 'mst_siswa_id');
    }
}
