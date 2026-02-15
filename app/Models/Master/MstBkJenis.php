<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Transaction\TrxBkKasus;

class MstBkJenis extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mst_bk_jenis';

    protected $fillable = [
        'nama',
        'deleted_at',
    ];

    public function bkKasus(): HasMany
    {
        return $this->hasMany(TrxBkKasus::class, 'mst_bk_jenis_id');
    }
}
