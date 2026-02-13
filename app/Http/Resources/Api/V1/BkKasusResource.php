<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BkKasusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'siswa' => $this->whenLoaded('siswa', fn () => [
                'id' => $this->siswa->id,
                'nama' => $this->siswa->nama,
                'nis' => $this->siswa->nis,
            ]),
            'guru' => $this->whenLoaded('guru', fn () => [
                'id' => $this->guru->id,
                'nama' => $this->guru->nama,
            ]),
            'jenis' => $this->whenLoaded('jenis', fn () => [
                'id' => $this->jenis->id,
                'kode' => $this->jenis->kode,
                'nama' => $this->jenis->nama,
            ]),
            'tanggal' => $this->tanggal?->format('Y-m-d'),
            'keterangan' => $this->keterangan,
            'status' => $this->status,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
