<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstGolongan;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GolonganService
{
    public function getAllGolongan(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = MstGolongan::query();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('pangkat', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('golongan_ruang', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('jabatan', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('golongan_ruang')->cursorPaginate($perPage);
    }

    public function getGolonganById(int $id): ?MstGolongan
    {
        return MstGolongan::find($id);
    }

    public function createGolongan(array $data): MstGolongan
    {
        return DB::transaction(function () use ($data) {
            $golongan = MstGolongan::create([
                'pangkat' => $data['pangkat'],
                'golongan_ruang' => $data['golongan_ruang'],
                'jabatan' => $data['jabatan'] ?? null,
            ]);

            Log::info('Golongan created', ['golongan_id' => $golongan->id]);
            return $golongan;
        });
    }

    public function updateGolongan(int $id, array $data): MstGolongan
    {
        return DB::transaction(function () use ($id, $data) {
            $golongan = MstGolongan::findOrFail($id);
            $golongan->update([
                'pangkat' => $data['pangkat'] ?? $golongan->pangkat,
                'golongan_ruang' => $data['golongan_ruang'] ?? $golongan->golongan_ruang,
                'jabatan' => $data['jabatan'] ?? $golongan->jabatan,
            ]);

            Log::info('Golongan updated', ['golongan_id' => $id]);
            return $golongan;
        });
    }

    public function deleteGolongan(int $id): bool
    {
        $golongan = MstGolongan::find($id);
        if (!$golongan) {
            return false;
        }

        $result = $golongan->delete();
        Log::info('Golongan deleted', ['golongan_id' => $id]);
        return $result;
    }

    public function getAllGolonganList(): Collection
    {
        return MstGolongan::orderBy('golongan_ruang')->get();
    }
}
