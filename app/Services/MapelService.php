<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstMapel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MapelService
{
    public function getAllMapel(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = MstMapel::query();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nama_mapel', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('kode_mapel', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('nama_mapel')->cursorPaginate($perPage);
    }

    public function getMapelById(int $id): ?MstMapel
    {
        return MstMapel::with('gurus')->find($id);
    }

    public function createMapel(array $data): MstMapel
    {
        return DB::transaction(function () use ($data) {
            $mapel = MstMapel::create([
                'kode_mapel' => $data['kode'],
                'nama_mapel' => $data['nama'],
            ]);

            Log::info('Mapel created', ['mapel_id' => $mapel->id]);
            return $mapel;
        });
    }

    public function updateMapel(int $id, array $data): MstMapel
    {
        return DB::transaction(function () use ($id, $data) {
            $mapel = MstMapel::findOrFail($id);
            $mapel->update([
                'kode_mapel' => $data['kode'] ?? $mapel->kode_mapel,
                'nama_mapel' => $data['nama'] ?? $mapel->nama_mapel,
            ]);

            Log::info('Mapel updated', ['mapel_id' => $id]);
            return $mapel;
        });
    }

    public function deleteMapel(int $id): bool
    {
        $mapel = MstMapel::find($id);
        if (!$mapel) {
            return false;
        }

        $result = $mapel->delete();
        Log::info('Mapel deleted', ['mapel_id' => $id]);
        return $result;
    }

    public function getGurusByMapel(int $id): array
    {
        $mapel = MstMapel::with('guru')->find($id);

        if (!$mapel) {
            return [];
        }

        return [
            'mapel' => [
                'id' => $mapel->id,
                'kode' => $mapel->kode_mapel,
                'nama' => $mapel->nama_mapel,
            ],
            'guru' => $mapel->guru->map(function ($g) {
                return [
                    'id' => $g->id,
                    'nama' => $g->nama,
                    'nip' => $g->nip,
                ];
            }),
        ];
    }
}
