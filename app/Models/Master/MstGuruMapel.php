<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MstGuruMapel extends Model
{
    use HasFactory;

    protected $table = 'mst_guru_mapel';

    protected $fillable = [
        'mst_guru_id',
        'mst_mapel_id',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(MstGuru::class, 'mst_guru_id');
    }

    public function mapel(): BelongsTo
    {
        return $this->belongsTo(MstMapel::class, 'mst_mapel_id');
    }
}
