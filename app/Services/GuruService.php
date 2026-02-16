<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstGuru;
use App\Models\Transaction\TrxAbsensiGuru;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuruService
{
    public function getAllGuru(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = MstGuru::query()->with('user');

        if (!empty($filters['jenis_kelamin'])) {
            $query->where('jenis_kelamin', $filters['jenis_kelamin']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nama', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('nip', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('nuptk', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('nama')->cursorPaginate($perPage);
    }

    public function getGuruById(int $id): ?MstGuru
    {
        return MstGuru::with(['user', 'mapel', 'waliKelas'])->find($id);
    }

    public function createGuru(array $data): MstGuru
    {
        return DB::transaction(function () use ($data) {
            $guru = MstGuru::create([
                'sys_user_id' => $data['sys_user_id'] ?? null,
                'nip' => $data['nip'] ?? null,
                'nuptk' => $data['nuptk'] ?? null,
                'nama' => $data['nama'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
                'alamat' => $data['alamat'] ?? null,
                'no_hp' => $data['no_hp'] ?? null,
                'email' => $data['email'] ?? null,
                'pendidikan_terakhir' => $data['pendidikan_terakhir'] ?? null,
            ]);

            if (!empty($data['mapel_ids'])) {
                $guru->mapel()->sync($data['mapel_ids']);
            }

            Log::info('Guru created', ['guru_id' => $guru->id]);
            return $guru;
        });
    }

    public function updateGuru(int $id, array $data): MstGuru
    {
        return DB::transaction(function () use ($id, $data) {
            $guru = MstGuru::findOrFail($id);
            $guru->update([
                'nip' => $data['nip'] ?? $guru->nip,
                'nuptk' => $data['nuptk'] ?? $guru->nuptk,
                'nama' => $data['nama'] ?? $guru->nama,
                'jenis_kelamin' => $data['jenis_kelamin'] ?? $guru->jenis_kelamin,
                'tanggal_lahir' => $data['tanggal_lahir'] ?? $guru->tanggal_lahir,
                'alamat' => $data['alamat'] ?? $guru->alamat,
                'no_hp' => $data['no_hp'] ?? $guru->no_hp,
                'email' => $data['email'] ?? $guru->email,
                'pendidikan_terakhir' => $data['pendidikan_terakhir'] ?? $guru->pendidikan_terakhir,
            ]);

            if (!empty($data['mapel_ids'])) {
                $guru->mapel()->sync($data['mapel_ids']);
            }

            Log::info('Guru updated', ['guru_id' => $id]);
            return $guru;
        });
    }

    public function deleteGuru(int $id): bool
    {
        $guru = MstGuru::find($id);
        if (!$guru) {
            return false;
        }

        $result = $guru->delete();
        Log::info('Guru deleted', ['guru_id' => $id]);
        return $result;
    }

    public function getGuruByMapel(int $mapelId): Collection
    {
        return MstGuru::whereHas('mapel', function ($q) use ($mapelId) {
            $q->where('mst_mapel.id', $mapelId);
        })->get();
    }

    public function getAbsensiSummary(int $id, string $startDate, string $endDate): array
    {
        $absensi = TrxAbsensiGuru::where('mst_guru_id', $id)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get();

        return [
            'hadir' => $absensi->where('status', 'hadir')->count(),
            'izin' => $absensi->where('status', 'izin')->count(),
            'sakit' => $absensi->where('status', 'sakit')->count(),
            'alpha' => $absensi->where('status', 'alpha')->count(),
            'total' => $absensi->count(),
            'periode' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ];
    }
}
