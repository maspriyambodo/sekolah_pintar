<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\Spk;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpkHasilResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mst_siswa_id' => $this->mst_siswa_id,
            'total_skor' => (float) $this->total_skor,
            'peringkat' => $this->peringkat,
            'periode' => $this->periode,
            'siswa' => $this->whenLoaded('siswa', fn () => [
                'id' => $this->siswa->id,
                'nis' => $this->siswa->nis,
                'nama' => $this->siswa->nama,
                'kelas' => $this->siswa->kelas ? [
                    'id' => $this->siswa->kelas->id,
                    'nama_kelas' => $this->siswa->kelas->nama_kelas,
                ] : null,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
