<?php

declare(strict_types=1);

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SysReference extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sys_references';

    protected $fillable = [
        'kategori',
        'kode',
        'nama',
        'urutan',
    ];

    protected $casts = [
        'urutan' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get references by kategori
     */
    public static function getByKategori(string $kategori)
    {
        return static::where('kategori', $kategori)
            ->orderBy('urutan', 'asc')
            ->orderBy('nama', 'asc')
            ->get();
    }

    /**
     * Get reference by kategori and kode
     */
    public static function getByKode(string $kategori, string $kode)
    {
        return static::where('kategori', $kategori)
            ->where('kode', $kode)
            ->first();
    }
}
