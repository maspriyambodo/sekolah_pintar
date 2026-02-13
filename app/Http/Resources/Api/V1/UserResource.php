<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'is_active' => $this->is_active,
            'roles' => $this->whenLoaded('roles', fn () => $this->roles->map(fn ($role) => [
                'id' => $role->id,
                'code' => $role->code,
                'name' => $role->name,
                'permissions' => $role->whenLoaded('permissions', fn () => $role->permissions->map(fn ($perm) => [
                    'id' => $perm->id,
                    'code' => $perm->code,
                    'name' => $perm->name,
                    'module' => $perm->module,
                ])),
            ])),
            'profile' => $this->when(
                $this->relationLoaded('guru') || $this->relationLoaded('siswa') || $this->relationLoaded('wali'),
                function () {
                    return match ($this->role) {
                        'guru' => $this->whenLoaded('guru', fn () => [
                            'nip' => $this->guru->nip,
                            'nama' => $this->guru->nama,
                            'jenis_kelamin' => $this->guru->jenis_kelamin,
                            'no_hp' => $this->guru->no_hp,
                        ]),
                        'siswa' => $this->whenLoaded('siswa', fn () => [
                            'nis' => $this->siswa->nis,
                            'nama' => $this->siswa->nama,
                            'jenis_kelamin' => $this->siswa->jenis_kelamin,
                            'kelas' => $this->when($this->siswa->relationLoaded('kelas'), fn () => [
                                'id' => $this->siswa->kelas?->id,
                                'nama_kelas' => $this->siswa->kelas?->nama_kelas,
                            ]),
                        ]),
                        'wali' => $this->whenLoaded('wali', fn () => [
                            'nama' => $this->wali->nama,
                            'no_hp' => $this->wali->no_hp,
                        ]),
                        default => null,
                    };
                }
            ),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
