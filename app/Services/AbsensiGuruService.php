<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaction\TrxAbsensiGuru;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbsensiGuruService
{
    public function getAllAbsensi(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = TrxAbsensiGuru::query()->with('guru');

        if (!empty($filters['guru_id'])) {
            $query->where('guru_id', $filters['guru_id']);
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

    public function getAbsensiById(int $id): ?TrxAbsensiGuru
    {
        return TrxAbsensiGuru::with('guru')->find($id);
    }

    public function createAbsensi(array $data): TrxAbsensiGuru
    {
        return DB::transaction(function () use ($data) {
            $absensi = TrxAbsensiGuru::create([
                'guru_id' => $data['guru_id'],
                'tanggal' => $data['tanggal'],
                'status' => $data['status'],
                'keterangan' => $data['keterangan'] ?? null,
                'jam_masuk' => $data['jam_masuk'] ?? null,
                'jam_keluar' => $data['jam_keluar'] ?? null,
            ]);

            Log::info('Absensi guru created', ['absensi_id' => $absensi->id]);
            return $absensi;
        });
    }

    public function updateAbsensi(int $id, array $data): TrxAbsensiGuru
    {
        return DB::transaction(function () use ($id, $data) {
            $absensi = TrxAbsensiGuru::findOrFail($id);
            $absensi->update([
                'guru_id' => $data['guru_id'] ?? $absensi->guru_id,
                'tanggal' => $data['tanggal'] ?? $absensi->tanggal,
                'status' => $data['status'] ?? $absensi->status,
                'keterangan' => $data['keterangan'] ?? $absensi->keterangan,
                'jam_masuk' => $data['jam_masuk'] ?? $absensi->jam_masuk,
                'jam_keluar' => $data['jam_keluar'] ?? $absensi->jam_keluar,
            ]);

            Log::info('Absensi guru updated', ['absensi_id' => $id]);
            return $absensi;
        });
    }

    public function deleteAbsensi(int $id): bool
    {
        $absensi = TrxAbsensiGuru::find($id);
        if (!$absensi) {
            return false;
        }

        $result = $absensi->delete();
        Log::info('Absensi guru deleted', ['absensi_id' => $id]);
        return $result;
    }

    public function getAbsensiByGuru(int $guruId): Collection
    {
        return TrxAbsensiGuru::where('guru_id', $guruId)
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    public function getAbsensiSummary(int $guruId): array
    {
        $absensi = TrxAbsensiGuru::where('guru_id', $guruId)->get();

        return [
            'hadir' => $absensi->where('status', 'hadir')->count(),
            'izin' => $absensi->where('status', 'izin')->count(),
            'sakit' => $absensi->where('status', 'sakit')->count(),
            'alpha' => $absensi->where('status', 'alpha')->count(),
            'total' => $absensi->count(),
        ];
    }
}
