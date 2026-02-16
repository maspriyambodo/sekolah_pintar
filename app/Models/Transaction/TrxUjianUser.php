<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\MstSiswa;

class TrxUjianUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_ujian_user';

    protected $fillable = [
        'trx_ujian_id',
        'mst_siswa_id',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'sisa_waktu',
        'total_benar',
        'total_salah',
        'nilai_akhir',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'status' => 'integer',
        'sisa_waktu' => 'integer',
        'total_benar' => 'integer',
        'total_salah' => 'integer',
        'nilai_akhir' => 'decimal:2',
    ];

    public const STATUS_BELUM_MULAI = 1;
    public const STATUS_MENGERJAKAN = 2;
    public const STATUS_SELESAI = 3;

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(TrxUjian::class, 'trx_ujian_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(MstSiswa::class, 'mst_siswa_id');
    }

    public function jawaban(): HasMany
    {
        return $this->hasMany(TrxUjianJawaban::class, 'trx_ujian_user_id');
    }
}