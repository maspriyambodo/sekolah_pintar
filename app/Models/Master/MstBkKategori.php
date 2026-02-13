<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Transaction\TrxBkKasus;

class MstBkKategori extends Model
{
    use HasFactory;

    protected $table = 'mst_bk_kategori';

    protected $fillable = [
        'nama',
    ];

    public function bkKasus(): HasMany
    {
        return $this->hasMany(TrxBkKasus::class, 'mst_bk_kategori_id');
    }
}
