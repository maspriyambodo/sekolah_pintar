<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\System\SysReference;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PeminjamanBukuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $statusPinjam = SysReference::getByKode('status_pinjam', (string) $this->status);
        return [
            'id' => $this->id,
            'siswa' => $this->whenLoaded('siswa', fn () => [
                'id' => $this->siswa->id,
                'nama' => $this->siswa->nama,
                'nis' => $this->siswa->nis,
            ]),
            'buku' => $this->whenLoaded('buku', fn () => [
                'id' => $this->buku->id,
                'judul' => $this->buku->judul,
                'isbn' => $this->buku->isbn,
            ]),
            'tanggal_pinjam' => $this->tanggal_pinjam?->format('Y-m-d'),
            'tanggal_jatuh_tempo' => $this->tanggal_jatuh_tempo?->format('Y-m-d'),
            'tanggal_kembali' => $this->tanggal_kembali?->format('Y-m-d'),
            'status' => $statusPinjam?->nama ?? $this->status,
            'keterangan' => $this->keterangan,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
