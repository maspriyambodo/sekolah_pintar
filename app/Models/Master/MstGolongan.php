<?php

declare(strict_types=1);

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\LogsActivity;

class MstGolongan extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'mst_golongan';

    protected $fillable = [
        'pangkat',
        'golongan_ruang',
        'jabatan',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
