<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstMateri;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MateriService
{
    public function getAllMateri(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = MstMateri::query()->with(['guruMapel.guru', 'guruMapel.mapel']);

        if (!empty($filters['mst_guru_mapel_id'])) {
            $query->where('mst_guru_mapel_id', $filters['mst_guru_mapel_id']);
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('judul', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('deskripsi', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->cursorPaginate($perPage);
    }

    public function getMateriById(int $id): ?MstMateri
    {
        return MstMateri::with(['guruMapel.guru', 'guruMapel.mapel'])->find($id);
    }

    public function getMateriByGuruMapel(int $guruMapelId, array $filters = []): Collection
    {
        $query = MstMateri::query()
            ->where('mst_guru_mapel_id', $guruMapelId);

        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('judul', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('deskripsi', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function createMateri(array $data): MstMateri
    {
        return DB::transaction(function () use ($data) {
            $materi = MstMateri::create([
                'mst_guru_mapel_id' => $data['mst_guru_mapel_id'],
                'judul' => $data['judul'],
                'deskripsi' => $data['deskripsi'] ?? null,
                'file_materi' => $data['file_materi'] ?? null,
                'link_video' => $data['link_video'] ?? null,
                'status' => $data['status'] ?? 1,
            ]);

            Log::info('Materi created', ['materi_id' => $materi->id]);
            return $materi;
        });
    }

    public function updateMateri(int $id, array $data): MstMateri
    {
        return DB::transaction(function () use ($id, $data) {
            $materi = MstMateri::findOrFail($id);

            $materi->update([
                'mst_guru_mapel_id' => $data['mst_guru_mapel_id'] ?? $materi->mst_guru_mapel_id,
                'judul' => $data['judul'] ?? $materi->judul,
                'deskripsi' => $data['deskripsi'] ?? $materi->deskripsi,
                'file_materi' => $data['file_materi'] ?? $materi->file_materi,
                'link_video' => $data['link_video'] ?? $materi->link_video,
                'status' => $data['status'] ?? $materi->status,
            ]);

            Log::info('Materi updated', ['materi_id' => $id]);
            return $materi;
        });
    }

    public function deleteMateri(int $id): bool
    {
        $materi = MstMateri::find($id);
        if (!$materi) {
            return false;
        }

        $result = $materi->delete();
        Log::info('Materi deleted', ['materi_id' => $id]);
        return $result;
    }
}
