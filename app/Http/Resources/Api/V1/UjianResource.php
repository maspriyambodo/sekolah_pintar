<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\System\SysReference;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UjianResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $semester = SysReference::getByKode('kategori_semester', (string) $this->semester);
        return [
            'id' => $this->id,
            'mapel' => $this->whenLoaded('mapel', fn () => [
                'id' => $this->mapel->id,
                'kode' => $this->mapel->kode,
                'nama' => $this->mapel->nama,
            ]),
            'kelas' => $this->whenLoaded('kelas', fn () => [
                'id' => $this->kelas->id,
                'nama_kelas' => $this->kelas->nama_kelas,
            ]),
            'jenis' => $this->jenis,
            'nama' => $this->nama,
            'tanggal' => $this->tanggal?->format('Y-m-d'),
            'semester' => $semester?->nama ?? $this->semester,
            'tahun_ajaran' => $this->tahun_ajaran,
            'keterangan' => $this->keterangan,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
