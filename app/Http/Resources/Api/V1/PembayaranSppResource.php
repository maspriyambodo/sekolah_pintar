<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PembayaranSppResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'siswa' => $this->whenLoaded('siswa', fn () => [
                'id' => $this->siswa->id,
                'nama' => $this->siswa->nama,
                'nis' => $this->siswa->nis,
            ]),
            'tarif_spp' => $this->whenLoaded('tarifSpp', fn () => [
                'id' => $this->tarifSpp->id,
                'nominal' => $this->tarifSpp->nominal,
                'kelas' => $this->when($this->tarifSpp->relationLoaded('kelas'), fn () => [
                    'id' => $this->tarifSpp->kelas?->id,
                    'nama_kelas' => $this->tarifSpp->kelas?->nama_kelas,
                ]),
            ]),
            'bulan' => $this->bulan,
            'nama_bulan' => $this->nama_bulan,
            'tahun' => $this->tahun,
            'tanggal_bayar' => $this->tanggal_bayar?->format('Y-m-d'),
            'jumlah_bayar' => $this->jumlah_bayar,
            'status' => $this->status,
            'metode_pembayaran' => $this->metode_pembayaran,
            'keterangan' => $this->keterangan,
            'petugas' => $this->whenLoaded('petugas', fn () => [
                'id' => $this->petugas->id,
                'name' => $this->petugas->name,
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
