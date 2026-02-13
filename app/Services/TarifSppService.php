<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstTarifSpp;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TarifSppService
{
    public function getAllTarifSpp(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = MstTarifSpp::query()->with('kelas');

        if (!empty($filters['kelas_id'])) {
            $query->where('mst_kelas_id', $filters['kelas_id']);
        }

        if (!empty($filters['tahun_ajaran'])) {
            $query->where('tahun_ajaran', $filters['tahun_ajaran']);
        }

        return $query->orderBy('tahun_ajaran', 'desc')->orderBy('mst_kelas_id')->cursorPaginate($perPage);
    }

    public function getTarifSppById(int $id): ?MstTarifSpp
    {
        return MstTarifSpp::with('kelas')->find($id);
    }

    public function createTarifSpp(array $data): MstTarifSpp
    {
        return DB::transaction(function () use ($data) {
            $tarifSpp = MstTarifSpp::create([
                'mst_kelas_id' => $data['mst_kelas_id'],
                'tahun_ajaran' => $data['tahun_ajaran'],
                'nominal' => $data['nominal'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            Log::info('Tarif SPP created', ['tarif_spp_id' => $tarifSpp->id]);
            return $tarifSpp;
        });
    }

    public function updateTarifSpp(int $id, array $data): MstTarifSpp
    {
        return DB::transaction(function () use ($id, $data) {
            $tarifSpp = MstTarifSpp::findOrFail($id);
            $tarifSpp->update([
                'mst_kelas_id' => $data['mst_kelas_id'] ?? $tarifSpp->mst_kelas_id,
                'tahun_ajaran' => $data['tahun_ajaran'] ?? $tarifSpp->tahun_ajaran,
                'nominal' => $data['nominal'] ?? $tarifSpp->nominal,
                'keterangan' => $data['keterangan'] ?? $tarifSpp->keterangan,
            ]);

            Log::info('Tarif SPP updated', ['tarif_spp_id' => $id]);
            return $tarifSpp;
        });
    }

    public function deleteTarifSpp(int $id): bool
    {
        $tarifSpp = MstTarifSpp::find($id);
        if (!$tarifSpp) {
            return false;
        }

        $result = $tarifSpp->delete();
        Log::info('Tarif SPP deleted', ['tarif_spp_id' => $id]);
        return $result;
    }

    public function getTarifSppByKelas(int $kelasId, string $tahunAjaran): ?MstTarifSpp
    {
        return MstTarifSpp::where('mst_kelas_id', $kelasId)
            ->where('tahun_ajaran', $tahunAjaran)
            ->first();
    }
}
