<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MstSiswaWali extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mst_siswa_wali';

    protected $fillable = [
        'mst_siswa_id',
        'mst_wali_id',
        'hubungan',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'hubungan' => 'integer',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(MstSiswa::class, 'mst_siswa_id');
    }

    public function wali(): BelongsTo
    {
        return $this->belongsTo(MstWali::class, 'mst_wali_id');
    }
}
