<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Master\MstSiswa;
use App\Repositories\Contracts\SiswaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\Cache;

class SiswaRepository extends BaseRepository implements SiswaRepositoryInterface
{
    public function __construct(MstSiswa $model)
    {
        parent::__construct($model);
    }

    public function findByNis(string $nis): ?MstSiswa
    {
        return Cache::tags(['siswa'])->remember(
            "siswa:nis:{$nis}",
            600,
            fn () => $this->model->where('nis', $nis)->first()
        );
    }

    public function getByKelas(int $kelasId): Collection
    {
        return Cache::tags(['siswa'])->remember(
            "siswa:kelas:{$kelasId}",
            300,
            fn () => $this->model
                ->with(['kelas', 'user'])
                ->where('mst_kelas_id', $kelasId)
                ->where('status', 'aktif')
                ->get()
        );
    }

    public function getByStatus(string $status): Collection
    {
        return $this->model
            ->with(['kelas'])
            ->where('status', $status)
            ->get();
    }

    public function getWithRelations(int $id): ?MstSiswa
    {
        return Cache::tags(['siswa'])->remember(
            "siswa:{$id}:full",
            300,
            fn () => $this->model
                ->with([
                    'kelas',
                    'kelas.waliGuru',
                    'user',
                    'wali',
                    'absensi' => fn ($q) => $q->latest('tanggal')->limit(30),
                    'nilai' => fn ($q) => $q->latest()->limit(50),
                ])
                ->find($id)
        );
    }

    public function paginateWithFilters(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = $this->model->with(['kelas', 'user'])->select([
            'id',
            'sys_user_id',
            'nis',
            'nama',
            'jenis_kelamin',
            'mst_kelas_id',
            'status',
        ]);

        if (!empty($filters['kelas_id'])) {
            $query->where('mst_kelas_id', $filters['kelas_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['jenis_kelamin'])) {
            $query->where('jenis_kelamin', $filters['jenis_kelamin']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        return $query->cursorPaginate($perPage);
    }

    public function getAbsensiSummary(int $siswaId, string $startDate, string $endDate): array
    {
        $siswa = $this->model->with(['absensi' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        }])->find($siswaId);

        if (!$siswa) {
            return [];
        }

        $absensi = $siswa->absensi;

        return [
            'hadir' => $absensi->where('status', 'hadir')->count(),
            'izin' => $absensi->where('status', 'izin')->count(),
            'sakit' => $absensi->where('status', 'sakit')->count(),
            'alpha' => $absensi->where('status', 'alpha')->count(),
            'total' => $absensi->count(),
        ];
    }

    public function getNilaiRataRata(int $siswaId, string $semester, string $tahunAjaran): ?float
    {
        $siswa = $this->model->with(['rapor' => function ($query) use ($semester, $tahunAjaran) {
            $query->where('semester', $semester)->where('tahun_ajaran', $tahunAjaran);
        }])->find($siswaId);

        return $siswa?->rapor->first()?->rata_rata;
    }
}
