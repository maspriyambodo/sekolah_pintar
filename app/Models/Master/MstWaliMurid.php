<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\System\SysUser;
use App\Models\Transaction\TrxBkWali;

class MstWaliMurid extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mst_wali_murid';

    protected $fillable = [
        'sys_user_id',
        'nama',
        'no_hp',
        'alamat',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(SysUser::class, 'sys_user_id');
    }

    public function bkWali(): HasMany
    {
        return $this->hasMany(TrxBkWali::class, 'mst_wali_murid_id');
    }
}
