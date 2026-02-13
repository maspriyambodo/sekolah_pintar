<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaction\TrxRanking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RankingService
{
    public function getAllRanking(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = TrxRanking::query()->with(['siswa', 'kelas']);

        if (!empty($filters['kelas_id'])) {
            $query->where('mst_kelas_id', $filters['kelas_id']);
        }

        if (!empty($filters['semester'])) {
            $query->where('semester', $filters['semester']);
        }

        if (!empty($filters['tahun_ajaran'])) {
            $query->where('tahun_ajaran', $filters['tahun_ajaran']);
        }

        return $query->orderBy('peringkat')->cursorPaginate($perPage);
    }

    public function getRankingById(int $id): ?TrxRanking
    {
        return TrxRanking::with(['siswa', 'kelas'])->find($id);
    }

    public function createRanking(array $data): TrxRanking
    {
        return DB::transaction(function () use ($data) {
            $ranking = TrxRanking::create([
                'mst_siswa_id' => $data['mst_siswa_id'],
                'mst_kelas_id' => $data['mst_kelas_id'],
                'semester' => $data['semester'],
                'tahun_ajaran' => $data['tahun_ajaran'],
                'rata_rata_nilai' => $data['rata_rata_nilai'],
                'peringkat' => $data['peringkat'],
            ]);

            Log::info('Ranking created', ['ranking_id' => $ranking->id]);
            return $ranking;
        });
    }

    public function updateRanking(int $id, array $data): TrxRanking
    {
        return DB::transaction(function () use ($id, $data) {
            $ranking = TrxRanking::findOrFail($id);
            $ranking->update([
                'mst_siswa_id' => $data['mst_siswa_id'] ?? $ranking->mst_siswa_id,
                'mst_kelas_id' => $data['mst_kelas_id'] ?? $ranking->mst_kelas_id,
                'semester' => $data['semester'] ?? $ranking->semester,
                'tahun_ajaran' => $data['tahun_ajaran'] ?? $ranking->tahun_ajaran,
                'rata_rata_nilai' => $data['rata_rata_nilai'] ?? $ranking->rata_rata_nilai,
                'peringkat' => $data['peringkat'] ?? $ranking->peringkat,
            ]);

            Log::info('Ranking updated', ['ranking_id' => $id]);
            return $ranking;
        });
    }

    public function deleteRanking(int $id): bool
    {
        $ranking = TrxRanking::find($id);
        if (!$ranking) {
            return false;
        }

        $result = $ranking->delete();
        Log::info('Ranking deleted', ['ranking_id' => $id]);
        return $result;
    }

    public function getRankingByKelas(int $kelasId): Collection
    {
        return TrxRanking::where('mst_kelas_id', $kelasId)
            ->with('siswa')
            ->orderBy('peringkat')
            ->get();
    }

    public function generateRanking(int $kelasId, string $semester, string $tahunAjaran): Collection
    {
        return DB::transaction(function () use ($kelasId, $semester, $tahunAjaran) {
            // Hapus ranking lama untuk kelas, semester, dan tahun ajaran ini
            TrxRanking::where('mst_kelas_id', $kelasId)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran)
                ->delete();

            // Ambil rata-rata nilai per siswa dan buat ranking baru
            $rankingData = DB::table('trx_nilai')
                ->join('trx_ujian', 'trx_nilai.trx_ujian_id', '=', 'trx_ujian.id')
                ->join('mst_siswa', 'trx_nilai.mst_siswa_id', '=', 'mst_siswa.id')
                ->where('mst_siswa.mst_kelas_id', $kelasId)
                ->where('trx_ujian.semester', $semester)
                ->where('trx_ujian.tahun_ajaran', $tahunAjaran)
                ->select(
                    'trx_nilai.mst_siswa_id',
                    DB::raw('AVG(trx_nilai.nilai) as rata_rata')
                )
                ->groupBy('trx_nilai.mst_siswa_id')
                ->orderByDesc('rata_rata')
                ->get();

            $peringkat = 1;
            foreach ($rankingData as $data) {
                TrxRanking::create([
                    'mst_siswa_id' => $data->mst_siswa_id,
                    'mst_kelas_id' => $kelasId,
                    'semester' => $semester,
                    'tahun_ajaran' => $tahunAjaran,
                    'rata_rata_nilai' => round($data->rata_rata, 2),
                    'peringkat' => $peringkat++,
                ]);
            }

            Log::info('Ranking generated', [
                'kelas_id' => $kelasId,
                'semester' => $semester,
                'tahun_ajaran' => $tahunAjaran,
            ]);

            return TrxRanking::where('mst_kelas_id', $kelasId)
                ->where('semester', $semester)
                ->where('tahun_ajaran', $tahunAjaran)
                ->with('siswa')
                ->orderBy('peringkat')
                ->get();
        });
    }
}
