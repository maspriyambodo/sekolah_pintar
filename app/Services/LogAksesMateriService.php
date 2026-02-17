<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaction\TrxLogAksesMateri;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogAksesMateriService
{
    public function getAllLogAkses(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = TrxLogAksesMateri::query()->with(['materi', 'siswa']);

        if (!empty($filters['mst_materi_id'])) {
            $query->where('mst_materi_id', $filters['mst_materi_id']);
        }

        if (!empty($filters['mst_siswa_id'])) {
            $query->where('mst_siswa_id', $filters['mst_siswa_id']);
        }

        if (!empty($filters['tanggal'])) {
            $query->whereDate('waktu_akses', $filters['tanggal']);
        }

        if (!empty($filters['tanggal_awal']) && !empty($filters['tanggal_akhir'])) {
            $query->whereBetween('waktu_akses', [$filters['tanggal_awal'], $filters['tanggal_akhir']]);
        }

        return $query->orderBy('waktu_akses', 'desc')->cursorPaginate($perPage);
    }

    public function getLogAksesById(int $id): ?TrxLogAksesMateri
    {
        return TrxLogAksesMateri::with(['materi', 'siswa'])->find($id);
    }

    public function getLogAksesByMateri(int $materiId, array $filters = []): Collection
    {
        $query = TrxLogAksesMateri::query()->with(['siswa'])
            ->where('mst_materi_id', $materiId);

        if (!empty($filters['tanggal'])) {
            $query->whereDate('waktu_akses', $filters['tanggal']);
        }

        if (!empty($filters['tanggal_awal']) && !empty($filters['tanggal_akhir'])) {
            $query->whereBetween('waktu_akses', [$filters['tanggal_awal'], $filters['tanggal_akhir']]);
        }

        return $query->orderBy('waktu_akses', 'desc')->get();
    }

    public function getLogAksesBySiswa(int $siswaId, array $filters = []): Collection
    {
        $query = TrxLogAksesMateri::query()->with(['materi'])
            ->where('mst_siswa_id', $siswaId);

        if (!empty($filters['mst_materi_id'])) {
            $query->where('mst_materi_id', $filters['mst_materi_id']);
        }

        if (!empty($filters['tanggal'])) {
            $query->whereDate('waktu_akses', $filters['tanggal']);
        }

        if (!empty($filters['tanggal_awal']) && !empty($filters['tanggal_akhir'])) {
            $query->whereBetween('waktu_akses', [$filters['tanggal_awal'], $filters['tanggal_akhir']]);
        }

        return $query->orderBy('waktu_akses', 'desc')->get();
    }

    public function getTotalDurasiBySiswa(int $siswaId, string $tanggalAwal, string $tanggalAkhir): int
    {
        return TrxLogAksesMateri::where('mst_siswa_id', $siswaId)
            ->whereBetween('waktu_akses', [$tanggalAwal, $tanggalAkhir])
            ->sum('durasi_detik');
    }

    public function getMateriPopular(int $limit = 10): Collection
    {
        return TrxLogAksesMateri::select('mst_materi_id', DB::raw('COUNT(*) as total_akses'), DB::raw('SUM(durasi_detik) as total_durasi'))
            ->with(['materi'])
            ->groupBy('mst_materi_id')
            ->orderByDesc('total_akses')
            ->limit($limit)
            ->get();
    }

    public function createLogAkses(array $data): TrxLogAksesMateri
    {
        return DB::transaction(function () use ($data) {
            $log = TrxLogAksesMateri::create([
                'mst_materi_id' => $data['mst_materi_id'],
                'mst_siswa_id' => $data['mst_siswa_id'],
                'waktu_akses' => $data['waktu_akses'] ?? now(),
                'durasi_detik' => $data['durasi_detik'] ?? 0,
                'perangkat' => $data['perangkat'] ?? null,
            ]);

            Log::info('Log akses materi created', ['log_id' => $log->id]);
            return $log;
        });
    }

    public function updateDurasi(int $id, int $durasiDetik): TrxLogAksesMateri
    {
        return DB::transaction(function () use ($id, $durasiDetik) {
            $log = TrxLogAksesMateri::findOrFail($id);

            $log->update([
                'durasi_detik' => $durasiDetik,
            ]);

            Log::info('Log akses materi updated', ['log_id' => $id, 'durasi_detik' => $durasiDetik]);
            return $log;
        });
    }
}
