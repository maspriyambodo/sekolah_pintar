<?php

declare(strict_types=1);

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SysMenu extends Model
{
    use HasFactory;

    protected $table = 'sys_menus';

    protected $fillable = [
        'parent_id',
        'sys_permission_id',
        'nama_menu',
        'url',
        'icon',
        'urutan',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(SysMenu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(SysMenu::class, 'parent_id')->orderBy('urutan');
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(SysPermission::class, 'sys_permission_id');
    }

    public function subMenus(): HasMany
    {
        return $this->hasMany(SysMenu::class, 'parent_id')->where('is_active', true)->orderBy('urutan');
    }
}