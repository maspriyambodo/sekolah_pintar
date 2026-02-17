<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MstTugas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mst_tugas';

    protected $fillable = [
        'mst_guru_mapel_id',
        'mst_kelas_id',
        'judul',
        'deskripsi',
        'file_lampiran',
        'tenggat_waktu',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'tenggat_waktu' => 'datetime',
        'status' => 'integer',
    ];

    public function guruMapel(): BelongsTo
    {
        return $this->belongsTo(MstGuruMapel::class, 'mst_guru_mapel_id');
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(MstKelas::class, 'mst_kelas_id');
    }
}
