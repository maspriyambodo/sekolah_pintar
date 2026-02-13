<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaction\TrxNilai;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NilaiService
{
    public function getAllNilai(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = TrxNilai::query()->with(['siswa', 'ujian']);

        if (!empty($filters['siswa_id'])) {
            $query->where('mst_siswa_id', $filters['siswa_id']);
        }

        if (!empty($filters['ujian_id'])) {
            $query->where('trx_ujian_id', $filters['ujian_id']);
        }

        return $query->orderBy('created_at', 'desc')->cursorPaginate($perPage);
    }

    public function getNilaiById(int $id): ?TrxNilai
    {
        return TrxNilai::with(['siswa', 'ujian'])->find($id);
    }

    public function createNilai(array $data): TrxNilai
    {
        return DB::transaction(function () use ($data) {
            $nilai = TrxNilai::create([
                'mst_siswa_id' => $data['mst_siswa_id'],
                'trx_ujian_id' => $data['trx_ujian_id'],
                'nilai' => $data['nilai'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            Log::info('Nilai created', ['nilai_id' => $nilai->id]);
            return $nilai;
        });
    }

    public function updateNilai(int $id, array $data): TrxNilai
    {
        return DB::transaction(function () use ($id, $data) {
            $nilai = TrxNilai::findOrFail($id);
            $nilai->update([
                'mst_siswa_id' => $data['mst_siswa_id'] ?? $nilai->mst_siswa_id,
                'trx_ujian_id' => $data['trx_ujian_id'] ?? $nilai->trx_ujian_id,
                'nilai' => $data['nilai'] ?? $nilai->nilai,
                'keterangan' => $data['keterangan'] ?? $nilai->keterangan,
            ]);

            Log::info('Nilai updated', ['nilai_id' => $id]);
            return $nilai;
        });
    }

    public function deleteNilai(int $id): bool
    {
        $nilai = TrxNilai::find($id);
        if (!$nilai) {
            return false;
        }

        $result = $nilai->delete();
        Log::info('Nilai deleted', ['nilai_id' => $id]);
        return $result;
    }

    public function getNilaiBySiswa(int $siswaId): Collection
    {
        return TrxNilai::where('mst_siswa_id', $siswaId)
            ->with(['ujian.mapel'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getNilaiByUjian(int $ujianId): Collection
    {
        return TrxNilai::where('trx_ujian_id', $ujianId)
            ->with('siswa')
            ->orderBy('nilai', 'desc')
            ->get();
    }

    public function getRataRataBySiswa(int $siswaId): ?float
    {
        $rataRata = TrxNilai::where('mst_siswa_id', $siswaId)->avg('nilai');
        return $rataRata ? round($rataRata, 2) : null;
    }
}
