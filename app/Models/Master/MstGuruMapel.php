<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MstGuruMapel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mst_guru_mapel';

    protected $fillable = [
        'mst_guru_id',
        'mst_mapel_id',
        'created_at',
        'updated_at',
        'deleted_at',
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
