<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaction\TrxAbsensiSiswa;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbsensiSiswaService
{
    public function getAllAbsensi(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = TrxAbsensiSiswa::query()->with('siswa');

        if (!empty($filters['mst_siswa_id'])) {
            $query->where('mst_siswa_id', $filters['mst_siswa_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['tanggal_from'])) {
            $query->whereDate('tanggal', '>=', $filters['tanggal_from']);
        }

        if (!empty($filters['tanggal_to'])) {
            $query->whereDate('tanggal', '<=', $filters['tanggal_to']);
        }

        return $query->orderBy('tanggal', 'desc')->cursorPaginate($perPage);
    }

    public function getAbsensiById(int $id): ?TrxAbsensiSiswa
    {
        return TrxAbsensiSiswa::with('siswa')->find($id);
    }

    public function createAbsensi(array $data): TrxAbsensiSiswa
    {
        return DB::transaction(function () use ($data) {
            $absensi = TrxAbsensiSiswa::create([
                'mst_siswa_id' => $data['mst_siswa_id'],
                'tanggal' => $data['tanggal'],
                'status' => $data['status'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            Log::info('Absensi siswa created', ['absensi_id' => $absensi->id]);
            return $absensi;
        });
    }

    public function updateAbsensi(int $id, array $data): TrxAbsensiSiswa
    {
        return DB::transaction(function () use ($id, $data) {
            $absensi = TrxAbsensiSiswa::findOrFail($id);
            $absensi->update([
                'mst_siswa_id' => $data['mst_siswa_id'] ?? $absensi->mst_siswa_id,
                'tanggal' => $data['tanggal'] ?? $absensi->tanggal,
                'status' => $data['status'] ?? $absensi->status,
                'keterangan' => $data['keterangan'] ?? $absensi->keterangan,
            ]);

            Log::info('Absensi siswa updated', ['absensi_id' => $id]);
            return $absensi;
        });
    }

    public function deleteAbsensi(int $id): bool
    {
        $absensi = TrxAbsensiSiswa::find($id);
        if (!$absensi) {
            return false;
        }

        $result = $absensi->delete();
        Log::info('Absensi siswa deleted', ['absensi_id' => $id]);
        return $result;
    }

    public function getAbsensiBySiswa(int $siswaId): Collection
    {
        return TrxAbsensiSiswa::where('mst_siswa_id', $siswaId)
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    public function getAbsensiByDateRange(string $startDate, string $endDate): Collection
    {
        return TrxAbsensiSiswa::whereBetween('tanggal', [$startDate, $endDate])
            ->with('siswa')
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    public function getAbsensiSummary(int $siswaId): array
    {
        $absensi = TrxAbsensiSiswa::where('mst_siswa_id', $siswaId)->get();

        return [
            'hadir' => $absensi->where('status', 'hadir')->count(),
            'izin' => $absensi->where('status', 'izin')->count(),
            'sakit' => $absensi->where('status', 'sakit')->count(),
            'alpha' => $absensi->where('status', 'alpha')->count(),
            'total' => $absensi->count(),
        ];
    }
}
