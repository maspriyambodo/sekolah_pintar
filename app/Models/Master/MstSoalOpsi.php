<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MstSoalOpsi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mst_soal_opsi';

    protected $fillable = [
        'mst_soal_id',
        'teks_opsi',
        'is_jawaban',
        'urutan',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_jawaban' => 'boolean',
    ];

    public function soal(): BelongsTo
    {
        return $this->belongsTo(MstSoal::class, 'mst_soal_id');
    }
}