<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Transaction\TrxRaporDetail;
use App\Models\Transaction\TrxUjian;

class MstMapel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mst_mapel';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
    ];

    public function gurus(): BelongsToMany
    {
        return $this->belongsToMany(MstGuru::class, 'mst_guru_mapel', 'mst_mapel_id', 'mst_guru_id');
    }

    public function ujian(): HasMany
    {
        return $this->hasMany(TrxUjian::class, 'mst_mapel_id');
    }

    public function raporDetail(): HasMany
    {
        return $this->hasMany(TrxRaporDetail::class, 'mst_mapel_id');
    }
}
