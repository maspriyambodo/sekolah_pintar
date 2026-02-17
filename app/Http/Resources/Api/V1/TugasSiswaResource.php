<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TugasSiswaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $statusLabels = [
            0 => 'Belum',
            1 => 'Tepat Waktu',
            2 => 'Terlambat',
        ];

        return [
            'id' => $this->id,
            'mst_tugas_id' => $this->mst_tugas_id,
            'mst_siswa_id' => $this->mst_siswa_id,
            'jawaban_teks' => $this->jawaban_teks,
            'file_siswa' => $this->file_siswa,
            'waktu_kumpl' => $this->waktu_kumpl?->toIso8601String(),
            'nilai' => (float) $this->nilai,
            'catatan_guru' => $this->catatan_guru,
            'status_kumpl' => $this->status_kumpl,
            'status_kumpl_label' => $statusLabels[$this->status_kumpl] ?? 'Unknown',
            'tugas' => $this->whenLoaded('tugas', fn () => [
                'id' => $this->tugas->id,
                'judul' => $this->tugas->judul,
                'deskripsi' => $this->tugas->deskripsi,
                'tenggat_waktu' => $this->tugas->tenggat_waktu?->toIso8601String(),
                'guru_mapel' => $this->when($this->tugas->relationLoaded('guruMapel'), fn () => [
                    'id' => $this->tugas->guruMapel->id,
                    'guru' => $this->tugas->guruMapel->guru ? [
                        'id' => $this->tugas->guruMapel->guru->id,
                        'nama' => $this->tugas->guruMapel->guru->nama,
                    ] : null,
                    'mapel' => $this->tugas->guruMapel->mapel ? [
                        'id' => $this->tugas->guruMapel->mapel->id,
                        'kode' => $this->tugas->guruMapel->mapel->kode_mapel,
                        'nama' => $this->tugas->guruMapel->mapel->nama_mapel,
                    ] : null,
                ]),
                'kelas' => $this->when($this->tugas->relationLoaded('kelas'), fn () => [
                    'id' => $this->tugas->kelas->id,
                    'nama' => $this->tugas->kelas->nama_kelas,
                    'level' => $this->tugas->kelas->level,
                ]),
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
