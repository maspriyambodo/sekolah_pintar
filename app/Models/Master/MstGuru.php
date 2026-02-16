<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
use App\Models\System\SysUser;
use App\Models\Transaction\TrxAbsensiGuru;
use App\Models\Transaction\TrxBkKasus;

class MstGuru extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'mst_guru';

    protected $fillable = [
        'sys_user_id',
        'nip',
        'nama',
        'nuptk',
        'email',
        'pendidikan_terakhir',
        'jabatan',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'jenis_kelamin' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(SysUser::class, 'sys_user_id');
    }

    public function mapels(): BelongsToMany
    {
        return $this->belongsToMany(MstMapel::class, 'mst_guru_mapel', 'mst_guru_id', 'mst_mapel_id');
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(TrxAbsensiGuru::class, 'mst_guru_id');
    }

    public function bkKasus(): HasMany
    {
        return $this->hasMany(TrxBkKasus::class, 'mst_guru_id');
    }

    public function kelasWali(): HasMany
    {
        return $this->hasMany(MstKelas::class, 'wali_guru_id');
    }
}
