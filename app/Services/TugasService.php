<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstTugas;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TugasService
{
    public function getAllTugas(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = MstTugas::query()->with(['guruMapel.guru', 'guruMapel.mapel', 'kelas']);

        if (!empty($filters['mst_guru_mapel_id'])) {
            $query->where('mst_guru_mapel_id', $filters['mst_guru_mapel_id']);
        }

        if (!empty($filters['mst_kelas_id'])) {
            $query->where('mst_kelas_id', $filters['mst_kelas_id']);
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

        return $query->orderBy('tenggat_waktu', 'desc')->cursorPaginate($perPage);
    }

    public function getTugasById(int $id): ?MstTugas
    {
        return MstTugas::with(['guruMapel.guru', 'guruMapel.mapel', 'kelas'])->find($id);
    }

    public function getTugasByKelas(int $kelasId, array $filters = []): Collection
    {
        $query = MstTugas::query()
            ->with(['guruMapel.guru', 'guruMapel.mapel'])
            ->where('mst_kelas_id', $kelasId)
            ->where('status', 1);

        if (!empty($filters['mst_guru_mapel_id'])) {
            $query->where('mst_guru_mapel_id', $filters['mst_guru_mapel_id']);
        }

        return $query->orderBy('tenggat_waktu', 'desc')->get();
    }

    public function getTugasByGuruMapel(int $guruMapelId, array $filters = []): Collection
    {
        $query = MstTugas::query()->with(['kelas'])
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

        return $query->orderBy('tenggat_waktu', 'desc')->get();
    }

    public function createTugas(array $data): MstTugas
    {
        return DB::transaction(function () use ($data) {
            $tugas = MstTugas::create([
                'mst_guru_mapel_id' => $data['mst_guru_mapel_id'],
                'mst_kelas_id' => $data['mst_kelas_id'],
                'judul' => $data['judul'],
                'deskripsi' => $data['deskripsi'] ?? null,
                'file_lampiran' => $data['file_lampiran'] ?? null,
                'tenggat_waktu' => $data['tenggat_waktu'],
                'status' => $data['status'] ?? 1,
            ]);

            Log::info('Tugas created', ['tugas_id' => $tugas->id]);
            return $tugas;
        });
    }

    public function updateTugas(int $id, array $data): MstTugas
    {
        return DB::transaction(function () use ($id, $data) {
            $tugas = MstTugas::findOrFail($id);
            $tugas->update([
                'mst_guru_mapel_id' => $data['mst_guru_mapel_id'] ?? $tugas->mst_guru_mapel_id,
                'mst_kelas_id' => $data['mst_kelas_id'] ?? $tugas->mst_kelas_id,
                'judul' => $data['judul'] ?? $tugas->judul,
                'deskripsi' => $data['deskripsi'] ?? $tugas->deskripsi,
                'file_lampiran' => $data['file_lampiran'] ?? $tugas->file_lampiran,
                'tenggat_waktu' => $data['tenggat_waktu'] ?? $tugas->tenggat_waktu,
                'status' => $data['status'] ?? $tugas->status,
            ]);

            Log::info('Tugas updated', ['tugas_id' => $id]);
            return $tugas;
        });
    }

    public function deleteTugas(int $id): bool
    {
        $tugas = MstTugas::find($id);
        if (!$tugas) {
            return false;
        }

        $result = $tugas->delete();
        Log::info('Tugas deleted', ['tugas_id' => $id]);
        return $result;
    }
}
