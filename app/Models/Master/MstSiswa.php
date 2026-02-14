<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\System\SysUser;
use App\Models\Transaction\TrxAbsensiSiswa;
use App\Models\Transaction\TrxBkKasus;
use App\Models\Transaction\TrxNilai;
use App\Models\Transaction\TrxPeminjamanBuku;
use App\Models\Transaction\TrxRapor;

class MstSiswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mst_siswa';

    protected $fillable = [
        'sys_user_id',
        'nis',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'mst_kelas_id',
        'status',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'status' => 'string',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(SysUser::class, 'sys_user_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(MstKelas::class, 'mst_kelas_id');
    }

    public function wali(): BelongsToMany
    {
        return $this->belongsToMany(MstWali::class, 'mst_siswa_wali', 'mst_siswa_id', 'mst_wali_id')
            ->withPivot('hubungan');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(TrxAbsensiSiswa::class, 'mst_siswa_id');
    }

    public function bkKasus(): HasMany
    {
        return $this->hasMany(TrxBkKasus::class, 'mst_siswa_id');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(TrxNilai::class, 'mst_siswa_id');
    }

    public function peminjamanBuku(): HasMany
    {
        return $this->hasMany(TrxPeminjamanBuku::class, 'mst_siswa_id');
    }

    public function rapor(): HasMany
    {
        return $this->hasMany(TrxRapor::class, 'mst_siswa_id');
    }
}
