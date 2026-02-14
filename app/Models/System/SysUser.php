<?php

declare(strict_types=1);

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use App\Traits\LogsActivity;
use App\Models\Master\MstGuru;
use App\Models\Master\MstSiswa;
use App\Models\Master\MstWali;
use App\Models\Master\MstWaliMurid;

class SysUser extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, SoftDeletes, LogsActivity;

    protected $table = 'sys_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'email' => $this->email,
            'role' => $this->role,
            'name' => $this->name,
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(SysRole::class, 'sys_user_roles', 'sys_user_id', 'sys_role_id')
            ->withTimestamps();
    }

    public function hasRole(string $roleCode): bool
    {
        return $this->roles()->where('code', $roleCode)->exists();
    }

    public function hasPermission(string $permissionCode): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissionCode) {
                $query->where('code', $permissionCode);
            })->exists();
    }

    public function guru(): HasOne
    {
        return $this->hasOne(MstGuru::class, 'sys_user_id');
    }

    public function siswa(): HasOne
    {
        return $this->hasOne(MstSiswa::class, 'sys_user_id');
    }

    public function wali(): HasOne
    {
        return $this->hasOne(MstWali::class, 'sys_user_id');
    }

    public function waliMurid(): HasOne
    {
        return $this->hasOne(MstWaliMurid::class, 'sys_user_id');
    }

    public function loginLogs(): HasMany
    {
        return $this->hasMany(SysLoginLog::class, 'sys_user_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(SysActivityLog::class, 'sys_user_id');
    }
}
