<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WaliResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'no_hp' => $this->no_hp,
            'alamat' => $this->alamat,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'email' => $this->user->email,
                'is_active' => $this->user->is_active,
            ]),
            'siswa' => $this->whenLoaded('siswa', fn () => $this->siswa->map(fn ($s) => [
                'id' => $s->id,
                'nama' => $s->nama,
                'nis' => $s->nis,
                'hubungan' => $s->pivot?->hubungan,
            ])),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
