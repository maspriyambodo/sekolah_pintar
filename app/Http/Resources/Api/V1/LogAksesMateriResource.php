<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LogAksesMateriResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mst_materi_id' => $this->mst_materi_id,
            'mst_siswa_id' => $this->mst_siswa_id,
            'waktu_akses' => $this->waktu_akses?->toIso8601String(),
            'durasi_detik' => $this->durasi_detik,
            'durasi_label' => $this->formatDurasi($this->durasi_detik),
            'perangkat' => $this->perangkat,
            'materi' => $this->whenLoaded('materi', fn () => [
                'id' => $this->materi->id,
                'judul' => $this->materi->judul,
            ]),
            'siswa' => $this->whenLoaded('siswa', fn () => [
                'id' => $this->siswa->id,
                'nis' => $this->siswa->nis,
                'nama' => $this->siswa->nama,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }

    private function formatDurasi(?int $detik): string
    {
        if ($detik === null || $detik === 0) {
            return '0 detik';
        }

        $jam = intdiv($detik, 3600);
        $menit = intdiv($detik % 3600, 60);
        $detikSisa = $detik % 60;

        $parts = [];
        if ($jam > 0) {
            $parts[] = $jam . ' jam';
        }
        if ($menit > 0) {
            $parts[] = $menit . ' menit';
        }
        if ($detikSisa > 0 || empty($parts)) {
            $parts[] = $detikSisa . ' detik';
        }

        return implode(' ', $parts);
    }
}
