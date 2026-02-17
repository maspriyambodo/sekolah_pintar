<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MstMateri extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mst_materi';

    protected $fillable = [
        'mst_guru_mapel_id',
        'judul',
        'deskripsi',
        'file_materi',
        'link_video',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'status' => 'integer',
    ];

    public function guruMapel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MstGuruMapel::class, 'mst_guru_mapel_id');
    }

    public function logAkses(): HasMany
    {
        return $this->hasMany(\App\Models\Transaction\TrxLogAksesMateri::class, 'mst_materi_id');
    }
}
