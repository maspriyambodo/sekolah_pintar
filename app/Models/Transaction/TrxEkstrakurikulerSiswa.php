<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Master\MstEkstrakurikuler;
use App\Models\Master\MstSiswa;

class TrxEkstrakurikulerSiswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_ekstrakurikuler_siswa';

    protected $fillable = [
        'ekstrakurikuler_id',
        'siswa_id',
        'tanggal_daftar',
        'status',
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function ekstrakurikuler(): BelongsTo
    {
        return $this->belongsTo(MstEkstrakurikuler::class, 'ekstrakurikuler_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(MstSiswa::class, 'siswa_id');
    }

    public function isAktif(): bool
    {
        return $this->status === 'aktif';
    }
}
