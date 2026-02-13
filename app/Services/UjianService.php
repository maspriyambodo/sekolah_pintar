<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaction\TrxUjian;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UjianService
{
    public function getAllUjian(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = TrxUjian::query()->with(['mapel', 'kelas']);

        if (!empty($filters['mapel_id'])) {
            $query->where('mst_mapel_id', $filters['mapel_id']);
        }

        if (!empty($filters['kelas_id'])) {
            $query->where('mst_kelas_id', $filters['kelas_id']);
        }

        if (!empty($filters['jenis'])) {
            $query->where('jenis', $filters['jenis']);
        }

        if (!empty($filters['semester'])) {
            $query->where('semester', $filters['semester']);
        }

        return $query->orderBy('tanggal', 'desc')->cursorPaginate($perPage);
    }

    public function getUjianById(int $id): ?TrxUjian
    {
        return TrxUjian::with(['mapel', 'kelas', 'nilai.siswa'])->find($id);
    }

    public function createUjian(array $data): TrxUjian
    {
        return DB::transaction(function () use ($data) {
            $ujian = TrxUjian::create([
                'mst_mapel_id' => $data['mst_mapel_id'],
                'mst_kelas_id' => $data['mst_kelas_id'],
                'jenis' => $data['jenis'],
                'nama' => $data['nama'],
                'tanggal' => $data['tanggal'],
                'semester' => $data['semester'],
                'tahun_ajaran' => $data['tahun_ajaran'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            Log::info('Ujian created', ['ujian_id' => $ujian->id]);
            return $ujian;
        });
    }

    public function updateUjian(int $id, array $data): TrxUjian
    {
        return DB::transaction(function () use ($id, $data) {
            $ujian = TrxUjian::findOrFail($id);
            $ujian->update([
                'mst_mapel_id' => $data['mst_mapel_id'] ?? $ujian->mst_mapel_id,
                'mst_kelas_id' => $data['mst_kelas_id'] ?? $ujian->mst_kelas_id,
                'jenis' => $data['jenis'] ?? $ujian->jenis,
                'nama' => $data['nama'] ?? $ujian->nama,
                'tanggal' => $data['tanggal'] ?? $ujian->tanggal,
                'semester' => $data['semester'] ?? $ujian->semester,
                'tahun_ajaran' => $data['tahun_ajaran'] ?? $ujian->tahun_ajaran,
                'keterangan' => $data['keterangan'] ?? $ujian->keterangan,
            ]);

            Log::info('Ujian updated', ['ujian_id' => $id]);
            return $ujian;
        });
    }

    public function deleteUjian(int $id): bool
    {
        $ujian = TrxUjian::find($id);
        if (!$ujian) {
            return false;
        }

        $result = $ujian->delete();
        Log::info('Ujian deleted', ['ujian_id' => $id]);
        return $result;
    }

    public function getNilaiByUjian(int $id): array
    {
        $ujian = TrxUjian::with(['nilai.siswa', 'mapel', 'kelas'])->find($id);

        if (!$ujian) {
            return [];
        }

        return [
            'ujian' => [
                'id' => $ujian->id,
                'nama' => $ujian->nama,
                'jenis' => $ujian->jenis,
                'mapel' => [
                    'id' => $ujian->mapel->id,
                    'nama' => $ujian->mapel->nama,
                ],
                'kelas' => [
                    'id' => $ujian->kelas->id,
                    'nama_kelas' => $ujian->kelas->nama_kelas,
                ],
            ],
            'nilai' => $ujian->nilai->map(function ($n) {
                return [
                    'id' => $n->id,
                    'siswa' => [
                        'id' => $n->siswa->id,
                        'nis' => $n->siswa->nis,
                        'nama' => $n->siswa->nama,
                    ],
                    'nilai' => $n->nilai,
                    'keterangan' => $n->keterangan,
                ];
            }),
        ];
    }

    public function getUjianByKelas(int $kelasId): Collection
    {
        return TrxUjian::where('mst_kelas_id', $kelasId)
            ->with('mapel')
            ->orderBy('tanggal', 'desc')
            ->get();
    }
}
