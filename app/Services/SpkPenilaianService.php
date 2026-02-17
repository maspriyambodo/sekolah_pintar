<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Spk\SpkPenilaian;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpkPenilaianService
{
    public function getAllPenilaian(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = SpkPenilaian::query()->with(['siswa', 'kriteria']);

        if (!empty($filters['mst_siswa_id'])) {
            $query->where('mst_siswa_id', $filters['mst_siswa_id']);
        }

        if (!empty($filters['spk_kriteria_id'])) {
            $query->where('spk_kriteria_id', $filters['spk_kriteria_id']);
        }

        if (!empty($filters['tahun_ajaran'])) {
            $query->where('tahun_ajaran', $filters['tahun_ajaran']);
        }

        return $query->orderBy('created_at', 'desc')->cursorPaginate($perPage);
    }

    public function getPenilaianById(int $id): ?SpkPenilaian
    {
        return SpkPenilaian::with(['siswa', 'kriteria'])->find($id);
    }

    public function getPenilaianBySiswa(int $siswaId, ?string $tahunAjaran = null): Collection
    {
        $query = SpkPenilaian::query()->with(['kriteria'])
            ->where('mst_siswa_id', $siswaId);

        if ($tahunAjaran) {
            $query->where('tahun_ajaran', $tahunAjaran);
        }

        return $query->orderBy('spk_kriteria_id', 'asc')->get();
    }

    public function getPenilaianByKriteria(int $kriteriaId, ?string $tahunAjaran = null): Collection
    {
        $query = SpkPenilaian::query()->with(['siswa'])
            ->where('spk_kriteria_id', $kriteriaId);

        if ($tahunAjaran) {
            $query->where('tahun_ajaran', $tahunAjaran);
        }

        return $query->orderBy('nilai', 'desc')->get();
    }

    public function createPenilaian(array $data): SpkPenilaian
    {
        return DB::transaction(function () use ($data) {
            $penilaian = SpkPenilaian::create([
                'mst_siswa_id' => $data['mst_siswa_id'],
                'spk_kriteria_id' => $data['spk_kriteria_id'],
                'nilai' => $data['nilai'],
                'tahun_ajaran' => $data['tahun_ajaran'] ?? null,
            ]);

            Log::info('SPK Penilaian created', ['penilaian_id' => $penilaian->id]);
            return $penilaian;
        });
    }

    public function updatePenilaian(int $id, array $data): SpkPenilaian
    {
        return DB::transaction(function () use ($id, $data) {
            $penilaian = SpkPenilaian::findOrFail($id);

            $penilaian->update([
                'nilai' => $data['nilai'] ?? $penilaian->nilai,
                'tahun_ajaran' => $data['tahun_ajaran'] ?? $penilaian->tahun_ajaran,
            ]);

            Log::info('SPK Penilaian updated', ['penilaian_id' => $id]);
            return $penilaian;
        });
    }

    public function deletePenilaian(int $id): bool
    {
        $penilaian = SpkPenilaian::find($id);
        if (!$penilaian) {
            return false;
        }

        $result = $penilaian->delete();
        Log::info('SPK Penilaian deleted', ['penilaian_id' => $id]);
        return $result;
    }

    public function upsertPenilaian(array $data): SpkPenilaian
    {
        return DB::transaction(function () use ($data) {
            $existing = SpkPenilaian::where('mst_siswa_id', $data['mst_siswa_id'])
                ->where('spk_kriteria_id', $data['spk_kriteria_id'])
                ->where('tahun_ajaran', $data['tahun_ajaran'] ?? null)
                ->first();

            if ($existing) {
                $existing->update(['nilai' => $data['nilai']]);
                Log::info('SPK Penilaian updated', ['penilaian_id' => $existing->id]);
                return $existing;
            }

            $penilaian = SpkPenilaian::create([
                'mst_siswa_id' => $data['mst_siswa_id'],
                'spk_kriteria_id' => $data['spk_kriteria_id'],
                'nilai' => $data['nilai'],
                'tahun_ajaran' => $data['tahun_ajaran'] ?? null,
            ]);

            Log::info('SPK Penilaian created', ['penilaian_id' => $penilaian->id]);
            return $penilaian;
        });
    }
}
