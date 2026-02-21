<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstEkstrakurikuler;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EkstrakurikulerService
{
    public function getAllEkstrakurikuler(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = MstEkstrakurikuler::query()->with('pembina');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['pembina_guru_id'])) {
            $query->where('pembina_guru_id', $filters['pembina_guru_id']);
        }

        if (!empty($filters['hari'])) {
            $query->where('hari', $filters['hari']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nama', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('kode', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('nama')->cursorPaginate($perPage);
    }

    public function getEkstrakurikulerById(int $id): ?MstEkstrakurikuler
    {
        return MstEkstrakurikuler::with(['pembina', 'siswaAktif.siswa'])->find($id);
    }

    public function getEkstrakurikulerByKode(string $kode): ?MstEkstrakurikuler
    {
        return MstEkstrakurikuler::where('kode', $kode)->first();
    }

    public function createEkstrakurikuler(array $data): MstEkstrakurikuler
    {
        return DB::transaction(function () use ($data) {
            $ekstrakurikuler = MstEkstrakurikuler::create([
                'kode' => $data['kode'],
                'nama' => $data['nama'],
                'deskripsi' => $data['deskripsi'] ?? null,
                'pembina_guru_id' => $data['pembina_guru_id'] ?? null,
                'hari' => $data['hari'] ?? null,
                'jam_mulai' => $data['jam_mulai'] ?? null,
                'jam_selesai' => $data['jam_selesai'] ?? null,
                'lokasi' => $data['lokasi'] ?? null,
                'status' => $data['status'] ?? 'aktif',
            ]);

            Log::info('Ekstrakurikuler created', ['ekstrakurikuler_id' => $ekstrakurikuler->id]);
            return $ekstrakurikuler;
        });
    }

    public function updateEkstrakurikuler(int $id, array $data): MstEkstrakurikuler
    {
        return DB::transaction(function () use ($id, $data) {
            $ekstrakurikuler = MstEkstrakurikuler::findOrFail($id);
            $ekstrakurikuler->update([
                'kode' => $data['kode'] ?? $ekstrakurikuler->kode,
                'nama' => $data['nama'] ?? $ekstrakurikuler->nama,
                'deskripsi' => $data['deskripsi'] ?? $ekstrakurikuler->deskripsi,
                'pembina_guru_id' => $data['pembina_guru_id'] ?? $ekstrakurikuler->pembina_guru_id,
                'hari' => $data['hari'] ?? $ekstrakurikuler->hari,
                'jam_mulai' => $data['jam_mulai'] ?? $ekstrakurikuler->jam_mulai,
                'jam_selesai' => $data['jam_selesai'] ?? $ekstrakurikuler->jam_selesai,
                'lokasi' => $data['lokasi'] ?? $ekstrakurikuler->lokasi,
                'status' => $data['status'] ?? $ekstrakurikuler->status,
            ]);

            Log::info('Ekstrakurikuler updated', ['ekstrakurikuler_id' => $id]);
            return $ekstrakurikuler;
        });
    }

    public function deleteEkstrakurikuler(int $id): bool
    {
        $ekstrakurikuler = MstEkstrakurikuler::find($id);
        if (!$ekstrakurikuler) {
            return false;
        }

        $result = $ekstrakurikuler->delete();
        Log::info('Ekstrakurikuler deleted', ['ekstrakurikuler_id' => $id]);
        return $result;
    }

    public function getByPembina(int $pembinaGuruId): Collection
    {
        return MstEkstrakurikuler::where('pembina_guru_id', $pembinaGuruId)
            ->where('status', 'aktif')
            ->get();
    }

    public function getAktif(): Collection
    {
        return MstEkstrakurikuler::where('status', 'aktif')->get();
    }

    public function getStatistik(int $id): array
    {
        $ekstrakurikuler = MstEkstrakurikuler::withCount(['siswa as total_siswa', 'siswaAktif as total_siswa_aktif'])->find($id);

        if (!$ekstrakurikuler) {
            return [];
        }

        return [
            'total_siswa' => $ekstrakurikuler->total_siswa ?? 0,
            'total_siswa_aktif' => $ekstrakurikuler->total_siswa_aktif ?? 0,
            'total_siswa_keluar' => ($ekstrakurikuler->total_siswa ?? 0) - ($ekstrakurikuler->total_siswa_aktif ?? 0),
        ];
    }
}
