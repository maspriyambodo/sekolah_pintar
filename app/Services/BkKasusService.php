<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaction\TrxBkKasus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BkKasusService
{
    public function getAllKasus(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = TrxBkKasus::query()->with(['siswa', 'guru', 'jenis']);

        if (!empty($filters['siswa_id'])) {
            $query->where('siswa_id', $filters['siswa_id']);
        }

        if (!empty($filters['guru_id'])) {
            $query->where('guru_id', $filters['guru_id']);
        }

        if (!empty($filters['jenis_id'])) {
            $query->where('jenis_id', $filters['jenis_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('tanggal', 'desc')->cursorPaginate($perPage);
    }

    public function getKasusById(int $id): ?TrxBkKasus
    {
        return TrxBkKasus::with(['siswa', 'guru', 'jenis', 'lampiran'])->find($id);
    }

    public function createKasus(array $data): TrxBkKasus
    {
        return DB::transaction(function () use ($data) {
            $kasus = TrxBkKasus::create([
                'siswa_id' => $data['siswa_id'],
                'guru_id' => $data['guru_id'],
                'jenis_id' => $data['jenis_id'],
                'tanggal' => $data['tanggal'],
                'keterangan' => $data['keterangan'],
                'status' => $data['status'] ?? 'open',
            ]);

            Log::info('BK Kasus created', ['kasus_id' => $kasus->id]);
            return $kasus;
        });
    }

    public function updateKasus(int $id, array $data): TrxBkKasus
    {
        return DB::transaction(function () use ($id, $data) {
            $kasus = TrxBkKasus::findOrFail($id);
            $kasus->update([
                'siswa_id' => $data['siswa_id'] ?? $kasus->siswa_id,
                'guru_id' => $data['guru_id'] ?? $kasus->guru_id,
                'jenis_id' => $data['jenis_id'] ?? $kasus->jenis_id,
                'tanggal' => $data['tanggal'] ?? $kasus->tanggal,
                'keterangan' => $data['keterangan'] ?? $kasus->keterangan,
                'status' => $data['status'] ?? $kasus->status,
            ]);

            Log::info('BK Kasus updated', ['kasus_id' => $id]);
            return $kasus;
        });
    }

    public function deleteKasus(int $id): bool
    {
        $kasus = TrxBkKasus::find($id);
        if (!$kasus) {
            return false;
        }

        $result = $kasus->delete();
        Log::info('BK Kasus deleted', ['kasus_id' => $id]);
        return $result;
    }

    public function getKasusBySiswa(int $siswaId): Collection
    {
        return TrxBkKasus::where('siswa_id', $siswaId)
            ->with(['guru', 'jenis'])
            ->orderBy('tanggal', 'desc')
            ->get();
    }
}
