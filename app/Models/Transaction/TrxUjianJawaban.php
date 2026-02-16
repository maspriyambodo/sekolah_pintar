<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\MstSoal;
use App\Models\Master\MstSoalOpsi;

class TrxUjianJawaban extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_ujian_jawaban';

    protected $fillable = [
        'trx_ujian_user_id',
        'mst_soal_id',
        'mst_soal_opsi_id',
        'jawaban_teks',
        'is_benar',
        'ragu_ragu',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_benar' => 'boolean',
        'ragu_ragu' => 'boolean',
    ];

    public function ujianUser(): BelongsTo
    {
        return $this->belongsTo(TrxUjianUser::class, 'trx_ujian_user_id');
    }

    public function soal(): BelongsTo
    {
        return $this->belongsTo(MstSoal::class, 'mst_soal_id');
    }

    public function opsi(): BelongsTo
    {
        return $this->belongsTo(MstSoalOpsi::class, 'mst_soal_opsi_id');
    }
}