<?php

declare(strict_types=1);

namespace App\Models\Spk;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SpkKriteria extends Model
{
    use HasFactory;

    protected $table = 'spk_kriteria';

    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'bobot',
        'tipe',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'bobot' => 'decimal:2',
    ];

    public function penilaian(): HasMany
    {
        return $this->hasMany(SpkPenilaian::class, 'spk_kriteria_id');
    }
}
