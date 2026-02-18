<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;
use App\Models\System\SysSekolahSettings;

class MstSekolah extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'mst_sekolah';

    protected $fillable = [
        'uuid',
        'npsn',
        'nama_sekolah',
        'alamat',
        'logo_path',
        'is_active',
        'subscription_plan',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function settings(): HasMany
    {
        return $this->hasMany(SysSekolahSettings::class, 'mst_sekolah_id');
    }
}
