<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\System\SysUser;

class MstWali extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mst_wali';

    protected $fillable = [
        'sys_user_id',
        'nama',
        'no_hp',
        'alamat',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(SysUser::class, 'sys_user_id');
    }

    public function siswa(): BelongsToMany
    {
        return $this->belongsToMany(MstSiswa::class, 'mst_siswa_wali', 'mst_wali_id', 'mst_siswa_id')
            ->withPivot('hubungan');
    }
}
