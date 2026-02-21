<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstEkstrakurikuler;
use App\Models\Master\MstSiswa;
use App\Models\Transaction\TrxEkstrakurikulerSiswa;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EkstrakurikulerSiswaService
{
    public function getAllPendaftaran(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = TrxEkstrakurikulerSiswa::query()
            ->with(['ekstrakurikuler', 'siswa']);

        if (!empty($filters['ekstrakurikuler_id'])) {
            $query->where('ekstrakurikuler_id', $filters['ekstrakurikuler_id']);
        }

        if (!empty($filters['siswa_id'])) {
            $query->where('siswa_id', $filters['siswa_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('tanggal_daftar', 'desc')->cursorPaginate($perPage);
    }

    public function getPendaftaranById(int $id): ?TrxEkstrakurikulerSiswa
    {
        return TrxEkstrakurikulerSiswa::with(['ekstrakurikuler', 'siswa'])->find($id);
    }

    public function daftar(array $data): TrxEkstrakurikulerSiswa
    {
        return DB::transaction(function () use ($data) {
            // Check if already registered
            $exists = TrxEkstrakurikulerSiswa::where('ekstrakurikuler_id', $data['ekstrakurikuler_id'])
                ->where('siswa_id', $data['siswa_id'])
                ->whereNull('deleted_at')
                ->first();

            if ($exists) {
                throw new \Exception('Siswa sudah terdaftar di ekstrakurikuler ini');
            }

            $pendaftaran = TrxEkstrakurikulerSiswa::create([
                'ekstrakurikuler_id' => $data['ekstrakurikuler_id'],
                'siswa_id' => $data['siswa_id'],
                'tanggal_daftar' => $data['tanggal_daftar'] ?? now()->toDateString(),
                'status' => 'aktif',
            ]);

            Log::info('Siswa daftar ekstrakurikuler', [
                'ekstrakurikuler_id' => $data['ekstrakurikuler_id'],
                'siswa_id' => $data['siswa_id'],
            ]);

            return $pendaftaran;
        });
    }

    public function updateStatus(int $id, string $status): TrxEkstrakurikulerSiswa
    {
        return DB::transaction(function () use ($id, $status) {
            $pendaftaran = TrxEkstrakurikulerSiswa::findOrFail($id);
            $pendaftaran->update(['status' => $status]);

            Log::info('Status ekstrakurikuler siswa updated', [
                'id' => $id,
                'status' => $status,
            ]);

            return $pendaftaran;
        });
    }

    public function keluar(int $id): TrxEkstrakurikulerSiswa
    {
        return $this->updateStatus($id, 'keluar');
    }

    public function deletePendaftaran(int $id): bool
    {
        $pendaftaran = TrxEkstrakurikulerSiswa::find($id);
        if (!$pendaftaran) {
            return false;
        }

        $result = $pendaftaran->delete();
        Log::info('Pendaftaran ekstrakurikuler deleted', ['id' => $id]);
        return $result;
    }

    public function getByEkstrakurikuler(int $ekstrakurikulerId): Collection
    {
        return TrxEkstrakurikulerSiswa::with('siswa')
            ->where('ekstrakurikuler_id', $ekstrakurikulerId)
            ->where('status', 'aktif')
            ->get();
    }

    public function getBySiswa(int $siswaId): Collection
    {
        return TrxEkstrakurikulerSiswa::with('ekstrakurikuler')
            ->where('siswa_id', $siswaId)
            ->where('status', 'aktif')
            ->get();
    }

    public function getRiwayatBySiswa(int $siswaId): Collection
    {
        return TrxEkstrakurikulerSiswa::with('ekstrakurikuler')
            ->where('siswa_id', $siswaId)
            ->orderBy('tanggal_daftar', 'desc')
            ->get();
    }

    public function isSiswaTerdaftar(int $siswaId, int $ekstrakurikulerId): bool
    {
        return TrxEkstrakurikulerSiswa::where('siswa_id', $siswaId)
            ->where('ekstrakurikuler_id', $ekstrakurikulerId)
            ->where('status', 'aktif')
            ->exists();
    }
}
