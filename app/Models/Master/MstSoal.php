<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MstSoal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mst_soal';

    protected $fillable = [
        'mst_mapel_id',
        'pertanyaan',
        'tipe',
        'tingkat_kesulitan',
        'media_path',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'tipe' => 'string',
        'tingkat_kesulitan' => 'string',
    ];

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(MstMapel::class, 'mst_mapel_id');
    }

    public function opsi(): HasMany
    {
        return $this->hasMany(MstSoalOpsi::class, 'mst_soal_id')->orderBy('urutan');
    }
}