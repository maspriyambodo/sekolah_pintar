<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\System\SysReference;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuruResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $jenisKelamin = SysReference::getByKode('jenis_kelamin', (string) $this->jenis_kelamin);
        $pendidikanTerakhir = SysReference::getByKode('pendidikan_terakhir', (string) $this->pendidikan_terakhir);
        return [
            'id' => $this->id,
            'nip' => $this->nip,
            'nuptk' => $this->nuptk,
            'nama' => $this->nama,
            'jenis_kelamin' => $jenisKelamin?->nama ?? $this->jenis_kelamin,
            'tanggal_lahir' => $this->tanggal_lahir?->format('Y-m-d'),
            'alamat' => $this->alamat,
            'no_hp' => $this->no_hp,
            'email' => $this->email,
            'pendidikan_terakhir' => $pendidikanTerakhir?->nama ?? $this->pendidikan_terakhir,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'email' => $this->user->email,
            ]),
            'mapel' => $this->whenLoaded('mapel', fn () => $this->mapel->map(fn ($m) => [
                'id' => $m->id,
                'kode' => $m->kode_mapel,
                'nama' => $m->nama_mapel,
            ])),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
