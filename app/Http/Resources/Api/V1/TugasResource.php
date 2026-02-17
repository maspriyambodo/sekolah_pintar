<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TugasResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mst_guru_mapel_id' => $this->mst_guru_mapel_id,
            'mst_kelas_id' => $this->mst_kelas_id,
            'judul' => $this->judul,
            'deskripsi' => $this->deskripsi,
            'file_lampiran' => $this->file_lampiran,
            'tenggat_waktu' => $this->tenggat_waktu?->toIso8601String(),
            'status' => $this->status,
            'status_label' => $this->status == 1 ? 'Aktif' : 'Draft/Selesai',
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
            'kelas' => $this->whenLoaded('kelas', fn () => [
                'id' => $this->kelas->id,
                'nama' => $this->kelas->nama_kelas,
                'level' => $this->kelas->level,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
