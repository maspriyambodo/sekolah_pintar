<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstBkJenis;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BkJenisService
{
    public function getAllBkJenis(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = MstBkJenis::query();

        if (!empty($filters['search'])) {
            $query->where('nama', 'like', '%' . $filters['search'] . '%')
                ->orWhere('kode', 'like', '%' . $filters['search'] . '%');
        }

        return $query->orderBy('nama')->cursorPaginate($perPage);
    }

    public function getBkJenisById(int $id): ?MstBkJenis
    {
        return MstBkJenis::find($id);
    }

    public function createBkJenis(array $data): MstBkJenis
    {
        return DB::transaction(function () use ($data) {
            $bkJenis = MstBkJenis::create([
                'kode' => $data['kode'],
                'nama' => $data['nama'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            Log::info('BK Jenis created', ['bk_jenis_id' => $bkJenis->id]);
            return $bkJenis;
        });
    }

    public function updateBkJenis(int $id, array $data): MstBkJenis
    {
        return DB::transaction(function () use ($id, $data) {
            $bkJenis = MstBkJenis::findOrFail($id);
            $bkJenis->update([
                'kode' => $data['kode'] ?? $bkJenis->kode,
                'nama' => $data['nama'] ?? $bkJenis->nama,
                'keterangan' => $data['keterangan'] ?? $bkJenis->keterangan,
            ]);

            Log::info('BK Jenis updated', ['bk_jenis_id' => $id]);
            return $bkJenis;
        });
    }

    public function deleteBkJenis(int $id): bool
    {
        $bkJenis = MstBkJenis::find($id);
        if (!$bkJenis) {
            return false;
        }

        $result = $bkJenis->delete();
        Log::info('BK Jenis deleted', ['bk_jenis_id' => $id]);
        return $result;
    }
}
