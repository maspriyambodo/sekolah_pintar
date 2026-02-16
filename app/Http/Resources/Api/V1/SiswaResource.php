<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Models\System\SysReference;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiswaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $jenisKelamin = SysReference::getByKode('jenis_kelamin', (string) $this->jenis_kelamin);
        $statusSiswa = SysReference::getByKode('status_siswa', (string) $this->status);
        return [
            'id' => $this->id,
            'nis' => $this->nis,
            'nama' => $this->nama,
            'jenis_kelamin' => $jenisKelamin?->nama ?? $this->jenis_kelamin,
            'tanggal_lahir' => $this->tanggal_lahir?->format('Y-m-d'),
            'alamat' => $this->alamat,
            'status' => $statusSiswa?->nama ?? $this->status,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $this->user->id,
                'email' => $this->user->email,
                'is_active' => $this->user->is_active,
            ]),
            'kelas' => $this->whenLoaded('kelas', fn () => [
                'id' => $this->kelas->id,
                'nama_kelas' => $this->kelas->nama_kelas,
                'tingkat' => $this->kelas->tingkat,
                'tahun_ajaran' => $this->kelas->tahun_ajaran,
                'wali_guru' => $this->when($this->kelas->relationLoaded('waliGuru'), fn () => [
                    'id' => $this->kelas->waliGuru?->id,
                    'nama' => $this->kelas->waliGuru?->nama,
                ]),
            ]),
            'wali' => $this->whenLoaded('wali', fn () => $this->wali->map(fn ($w) => [
                'id' => $w->id,
                'nama' => $w->nama,
                'no_hp' => $w->no_hp,
                'hubungan' => $w->pivot?->hubungan,
            ])),
            'absensi_summary' => $this->whenLoaded('absensi', fn () => [
                'hadir' => $this->absensi->where('status', 'hadir')->count(),
                'izin' => $this->absensi->where('status', 'izin')->count(),
                'sakit' => $this->absensi->where('status', 'sakit')->count(),
                'alpha' => $this->absensi->where('status', 'alpha')->count(),
            ]),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
