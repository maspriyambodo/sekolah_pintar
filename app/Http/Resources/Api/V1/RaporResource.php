<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\System\SysReference;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RaporResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $semester = SysReference::getByKode('kategori_semester', (string) $this->semester);
        return [
            'id' => $this->id,
            'siswa' => $this->whenLoaded('siswa', fn () => [
                'id' => $this->siswa->id,
                'nama' => $this->siswa->nama,
                'nis' => $this->siswa->nis,
            ]),
            'kelas' => $this->whenLoaded('kelas', fn () => [
                'id' => $this->kelas->id,
                'nama_kelas' => $this->kelas->nama_kelas,
            ]),
            'semester' => $semester?->nama ?? $this->semester,
            'tahun_ajaran' => $this->tahun_ajaran,
            'catatan_wali' => $this->catatan_wali,
            'kehadiran' => [
                'sakit' => $this->sakit,
                'izin' => $this->izin,
                'tanpa_keterangan' => $this->tanpa_keterangan,
            ],
            'detail' => $this->whenLoaded('detail', fn () => $this->detail->map(fn ($d) => [
                'id' => $d->id,
                'mapel' => [
                    'id' => $d->mapel->id,
                    'kode' => $d->mapel->kode,
                    'nama' => $d->mapel->nama,
                ],
                'nilai_pengetahuan' => $d->nilai_pengetahuan,
                'nilai_keterampilan' => $d->nilai_keterampilan,
                'nilai_akhir' => $d->nilai_akhir,
                'predikat' => $d->predikat,
                'deskripsi' => $d->deskripsi,
            ])),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
