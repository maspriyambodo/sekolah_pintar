<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsensiGuruResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'guru' => $this->whenLoaded('guru', fn () => [
                'id' => $this->guru->id,
                'nama' => $this->guru->nama,
                'nip' => $this->guru->nip,
            ]),
            'tanggal' => $this->tanggal?->format('Y-m-d'),
            'status' => $this->status,
            'keterangan' => $this->keterangan,
            'jam_masuk' => $this->jam_masuk,
            'jam_keluar' => $this->jam_keluar,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
