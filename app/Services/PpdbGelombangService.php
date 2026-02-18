<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Ppdb\PpdbGelombang;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PpdbGelombangService
{
    public function getAllGelombang(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = PpdbGelombang::query()->with('sekolah');

        if (!empty($filters['mst_sekolah_id'])) {
            $query->where('mst_sekolah_id', $filters['mst_sekolah_id']);
        }

        if (!empty($filters['tahun_ajaran'])) {
            $query->where('tahun_ajaran', $filters['tahun_ajaran']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('tgl_mulai', 'desc')->cursorPaginate($perPage);
    }

    public function getGelombangById(int $id): ?PpdbGelombang
    {
        return PpdbGelombang::with('sekolah', 'pendaftars')->find($id);
    }

    public function getActiveGelombang(int $sekolahId): Collection
    {
        $now = now()->toDateString();
        return PpdbGelombang::where('mst_sekolah_id', $sekolahId)
            ->where('is_active', true)
            ->where('tgl_mulai', '<=', $now)
            ->where('tgl_selesai', '>=', $now)
            ->orderBy('tgl_mulai', 'asc')
            ->get();
    }

    public function createGelombang(array $data): PpdbGelombang
    {
        return DB::transaction(function () use ($data) {
            $gelombang = PpdbGelombang::create([
                'mst_sekolah_id' => $data['mst_sekolah_id'],
                'nama_gelombang' => $data['nama_gelombang'],
                'tahun_ajaran' => $data['tahun_ajaran'],
                'tgl_mulai' => $data['tgl_mulai'],
                'tgl_selesai' => $data['tgl_selesai'],
                'biaya_pendaftaran' => $data['biaya_pendaftaran'] ?? 0,
                'is_active' => $data['is_active'] ?? true,
            ]);

            Log::info('PPDB Gelombang created', ['gelombang_id' => $gelombang->id]);
            return $gelombang;
        });
    }

    public function updateGelombang(int $id, array $data): PpdbGelombang
    {
        return DB::transaction(function () use ($id, $data) {
            $gelombang = PpdbGelombang::findOrFail($id);
            $gelombang->update([
                'nama_gelombang' => $data['nama_gelombang'] ?? $gelombang->nama_gelombang,
                'tahun_ajaran' => $data['tahun_ajaran'] ?? $gelombang->tahun_ajaran,
                'tgl_mulai' => $data['tgl_mulai'] ?? $gelombang->tgl_mulai,
                'tgl_selesai' => $data['tgl_selesai'] ?? $gelombang->tgl_selesai,
                'biaya_pendaftaran' => $data['biaya_pendaftaran'] ?? $gelombang->biaya_pendaftaran,
                'is_active' => $data['is_active'] ?? $gelombang->is_active,
            ]);

            Log::info('PPDB Gelombang updated', ['gelombang_id' => $id]);
            return $gelombang;
        });
    }

    public function deleteGelombang(int $id): bool
    {
        $gelombang = PpdbGelombang::find($id);
        if (!$gelombang) {
            return false;
        }

        $result = $gelombang->delete();
        Log::info('PPDB Gelombang deleted', ['gelombang_id' => $id]);
        return $result;
    }

    public function activateGelombang(int $id): PpdbGelombang
    {
        $gelombang = PpdbGelombang::findOrFail($id);
        $gelombang->update(['is_active' => true]);
        
        Log::info('PPDB Gelombang activated', ['gelombang_id' => $id]);
        return $gelombang;
    }

    public function deactivateGelombang(int $id): PpdbGelombang
    {
        $gelombang = PpdbGelombang::findOrFail($id);
        $gelombang->update(['is_active' => false]);
        
        Log::info('PPDB Gelombang deactivated', ['gelombang_id' => $id]);
        return $gelombang;
    }
}
