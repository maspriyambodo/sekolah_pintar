<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KelasResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_kelas' => $this->nama_kelas,
            'tingkat' => $this->tingkat,
            'tahun_ajaran' => $this->tahun_ajaran,
            'kapasitas' => $this->kapasitas,
            'wali_guru' => $this->whenLoaded('waliGuru', fn () => [
                'id' => $this->waliGuru->id,
                'nama' => $this->waliGuru->nama,
            ]),
            'jumlah_siswa' => $this->whenLoaded('siswa', fn () => $this->siswa->count()),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
