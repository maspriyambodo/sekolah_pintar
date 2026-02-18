<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Ppdb\PpdbDokumen;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PpdbDokumenService
{
    public function getAllDokumen(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = PpdbDokumen::query()->with('pendaftaran');

        if (!empty($filters['ppdb_pendaftar_id'])) {
            $query->where('ppdb_pendaftar_id', $filters['ppdb_pendaftar_id']);
        }

        if (!empty($filters['jenis_dokumen'])) {
            $query->where('jenis_dokumen', $filters['jenis_dokumen']);
        }

        if (isset($filters['verifikasi_status'])) {
            $query->where('verifikasi_status', $filters['verifikasi_status']);
        }

        return $query->orderBy('created_at', 'desc')->cursorPaginate($perPage);
    }

    public function getDokumenById(int $id): ?PpdbDokumen
    {
        return PpdbDokumen::with('pendaftaran')->find($id);
    }

    public function getDokumenByPendaftaran(int $pendaftaranId): Collection
    {
        return PpdbDokumen::where('ppdb_pendaftar_id', $pendaftaranId)->get();
    }

    public function createDokumen(array $data): PpdbDokumen
    {
        return DB::transaction(function () use ($data) {
            $dokumen = PpdbDokumen::create([
                'ppdb_pendaftar_id' => $data['ppdb_pendaftar_id'],
                'jenis_dokumen' => $data['jenis_dokumen'],
                'file_path' => $data['file_path'],
                'verifikasi_status' => $data['verifikasi_status'] ?? false,
                'catatan_admin' => $data['catatan_admin'] ?? null,
            ]);

            Log::info('PPDB Dokumen created', ['dokumen_id' => $dokumen->id]);
            return $dokumen;
        });
    }

    public function updateDokumen(int $id, array $data): PpdbDokumen
    {
        return DB::transaction(function () use ($id, $data) {
            $dokumen = PpdbDokumen::findOrFail($id);
            $updateData = [];

            if (isset($data['jenis_dokumen'])) {
                $updateData['jenis_dokumen'] = $data['jenis_dokumen'];
            }
            if (isset($data['file_path'])) {
                $updateData['file_path'] = $data['file_path'];
            }
            if (isset($data['verifikasi_status'])) {
                $updateData['verifikasi_status'] = $data['verifikasi_status'];
            }
            if (isset($data['catatan_admin'])) {
                $updateData['catatan_admin'] = $data['catatan_admin'];
            }

            $dokumen->update($updateData);

            Log::info('PPDB Dokumen updated', ['dokumen_id' => $id]);
            return $dokumen;
        });
    }

    public function verifyDokumen(int $id, ?string $catatan = null): PpdbDokumen
    {
        $dokumen = PpdbDokumen::findOrFail($id);
        $dokumen->update([
            'verifikasi_status' => true,
            'catatan_admin' => $catatan ?? $dokumen->catatan_admin,
        ]);

        Log::info('PPDB Dokumen verified', ['dokumen_id' => $id]);
        return $dokumen;
    }

    public function rejectDokumen(int $id, string $catatan): PpdbDokumen
    {
        $dokumen = PpdbDokumen::findOrFail($id);
        $dokumen->update([
            'verifikasi_status' => false,
            'catatan_admin' => $catatan,
        ]);

        Log::info('PPDB Dokumen rejected', ['dokumen_id' => $id]);
        return $dokumen;
    }

    public function deleteDokumen(int $id): bool
    {
        $dokumen = PpdbDokumen::find($id);
        if (!$dokumen) {
            return false;
        }

        $result = $dokumen->delete();
        Log::info('PPDB Dokumen deleted', ['dokumen_id' => $id]);
        return $result;
    }

    public function getDokumenByJenis(int $pendaftaranId, string $jenisDokumen): ?PpdbDokumen
    {
        return PpdbDokumen::where('ppdb_pendaftar_id', $pendaftaranId)
            ->where('jenis_dokumen', $jenisDokumen)
            ->first();
    }

    public function getUnverifiedCount(int $pendaftaranId): int
    {
        return PpdbDokumen::where('ppdb_pendaftar_id', $pendaftaranId)
            ->where('verifikasi_status', false)
            ->count();
    }
}
