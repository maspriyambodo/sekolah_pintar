<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstKelas;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KelasService
{
    public function getAllKelas(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = MstKelas::query()->with(['waliGuru', 'siswa']);

        if (!empty($filters['tingkat'])) {
            $query->where('tingkat', $filters['tingkat']);
        }

        if (!empty($filters['tahun_ajaran'])) {
            $query->where('tahun_ajaran', $filters['tahun_ajaran']);
        }

        if (!empty($filters['wali_guru_id'])) {
            $query->where('wali_guru_id', $filters['wali_guru_id']);
        }

        if (!empty($filters['search'])) {
            $query->where('nama_kelas', 'like', '%' . $filters['search'] . '%');
        }

        return $query->orderBy('tingkat')->orderBy('nama_kelas')->cursorPaginate($perPage);
    }

    public function getKelasById(int $id): ?MstKelas
    {
        return MstKelas::with(['waliGuru', 'siswa'])->find($id);
    }

    public function createKelas(array $data): MstKelas
    {
        return DB::transaction(function () use ($data) {
            $kelas = MstKelas::create([
                'nama_kelas' => $data['nama_kelas'],
                'tingkat' => $data['tingkat'],
                'tahun_ajaran' => $data['tahun_ajaran'],
                'wali_guru_id' => $data['wali_guru_id'] ?? null,
                'kapasitas' => $data['kapasitas'] ?? 30,
            ]);

            Log::info('Kelas created', ['kelas_id' => $kelas->id]);
            return $kelas;
        });
    }

    public function updateKelas(int $id, array $data): MstKelas
    {
        return DB::transaction(function () use ($id, $data) {
            $kelas = MstKelas::findOrFail($id);
            $kelas->update([
                'nama_kelas' => $data['nama_kelas'] ?? $kelas->nama_kelas,
                'tingkat' => $data['tingkat'] ?? $kelas->tingkat,
                'tahun_ajaran' => $data['tahun_ajaran'] ?? $kelas->tahun_ajaran,
                'wali_guru_id' => $data['wali_guru_id'] ?? $kelas->wali_guru_id,
                'kapasitas' => $data['kapasitas'] ?? $kelas->kapasitas,
            ]);

            Log::info('Kelas updated', ['kelas_id' => $id]);
            return $kelas;
        });
    }

    public function deleteKelas(int $id): bool
    {
        $kelas = MstKelas::find($id);
        if (!$kelas) {
            return false;
        }

        $result = $kelas->delete();
        Log::info('Kelas deleted', ['kelas_id' => $id]);
        return $result;
    }

    public function getSiswaByKelas(int $id): array
    {
        $kelas = MstKelas::with('siswa')->find($id);

        if (!$kelas) {
            return [];
        }

        return [
            'kelas' => [
                'id' => $kelas->id,
                'nama_kelas' => $kelas->nama_kelas,
                'tingkat' => $kelas->tingkat,
            ],
            'siswa' => $kelas->siswa->map(function ($s) {
                return [
                    'id' => $s->id,
                    'nis' => $s->nis,
                    'nama' => $s->nama,
                    'jenis_kelamin' => $s->jenis_kelamin,
                    'status' => $s->status,
                ];
            }),
        ];
    }

    public function getKelasByTingkat(int $tingkat): Collection
    {
        return MstKelas::where('tingkat', $tingkat)
            ->with('waliGuru')
            ->orderBy('nama_kelas')
            ->get();
    }
}
