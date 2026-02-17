<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\System\SysReference;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PresensiResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $statusPresensi = SysReference::getByKode('status_presensi', (string) $this->status);
        
        return [
            'id' => $this->id,
            'mst_guru_mapel_id' => $this->mst_guru_mapel_id,
            'mst_siswa_id' => $this->mst_siswa_id,
            'tanggal' => $this->tanggal?->format('Y-m-d'),
            'jam_masuk' => $this->jam_masuk ? $this->jam_masuk->format('H:i:s') : null,
            'status' => $this->status,
            'status_label' => $statusPresensi?->nama ?? $this->status,
            'keterangan' => $this->keterangan,
            'guru_mapel' => $this->whenLoaded('guruMapel', fn () => [
                'id' => $this->guruMapel->id,
                'guru' => $this->guruMapel->guru ? [
                    'id' => $this->guruMapel->guru->id,
                    'nama' => $this->guruMapel->guru->nama,
                ] : null,
                'mapel' => $this->guruMapel->mapel ? [
                    'id' => $this->guruMapel->mapel->id,
                    'kode' => $this->guruMapel->mapel->kode_mapel,
                    'nama' => $this->guruMapel->mapel->nama_mapel,
                ] : null,
            ]),
            'siswa' => $this->whenLoaded('siswa', fn () => [
                'id' => $this->siswa->id,
                'nis' => $this->siswa->nis,
                'nama' => $this->siswa->nama,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
