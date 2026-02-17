<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1\Spk;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpkPenilaianResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mst_siswa_id' => $this->mst_siswa_id,
            'spk_kriteria_id' => $this->spk_kriteria_id,
            'nilai' => (float) $this->nilai,
            'tahun_ajaran' => $this->tahun_ajaran,
            'siswa' => $this->whenLoaded('siswa', fn () => [
                'id' => $this->siswa->id,
                'nis' => $this->siswa->nis,
                'nama' => $this->siswa->nama,
            ]),
            'kriteria' => $this->whenLoaded('kriteria', fn () => [
                'id' => $this->kriteria->id,
                'kode_kriteria' => $this->kriteria->kode_kriteria,
                'nama_kriteria' => $this->kriteria->nama_kriteria,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
