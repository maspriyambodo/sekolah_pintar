<?php

declare(strict_types=1);

namespace App\Models\Transaction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrxForum extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'trx_forum';

    protected $fillable = [
        'mst_guru_mapel_id',
        'sys_user_id',
        'parent_id',
        'judul',
        'pesan',
        'file_lampiran',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function guruMapel(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Master\MstGuruMapel::class, 'mst_guru_mapel_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\System\SysUser::class, 'sys_user_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(TrxForum::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(TrxForum::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    public function topik(): HasMany
    {
        return $this->hasMany(TrxForum::class, 'parent_id')->with('replies');
    }
}
