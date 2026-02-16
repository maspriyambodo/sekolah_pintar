<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstSiswa;
use App\Models\System\SysReference;
use App\Repositories\Contracts\SiswaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SiswaService
{
    private SiswaRepositoryInterface $siswaRepository;

    public function __construct(SiswaRepositoryInterface $siswaRepository)
    {
        $this->siswaRepository = $siswaRepository;
    }

    public function getAllSiswa(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $cacheKey = 'siswa:list:' . md5(serialize($filters) . $perPage);

        return Cache::tags(['siswa'])->remember(
            $cacheKey,
            180, // 3 minutes
            fn () => $this->siswaRepository->paginateWithFilters($filters, $perPage)
        );
    }

    public function getSiswaById(int $id): ?MstSiswa
    {
        return $this->siswaRepository->getWithRelations($id);
    }

    public function getSiswaByNis(string $nis): ?MstSiswa
    {
        return $this->siswaRepository->findByNis($nis);
    }

    public function getSiswaByKelas(int $kelasId): Collection
    {
        return $this->siswaRepository->getByKelas($kelasId);
    }

    public function createSiswa(array $data): MstSiswa
    {
        return DB::transaction(function () use ($data) {
            $siswa = $this->siswaRepository->create([
                'sys_user_id' => $data['sys_user_id'],
                'nis' => $data['nis'],
                'nama' => $data['nama'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
                'alamat' => $data['alamat'] ?? null,
                'mst_kelas_id' => $data['mst_kelas_id'] ?? null,
                'status' => $data['status'] ?? 'aktif',
            ]);

            $this->clearSiswaCache();
            Log::info('Siswa created', ['siswa_id' => $siswa->id, 'nis' => $siswa->nis]);

            return $siswa;
        });
    }

    public function updateSiswa(int $id, array $data): MstSiswa
    {
        return DB::transaction(function () use ($id, $data) {
            $siswa = $this->siswaRepository->update($id, [
                'nis' => $data['nis'] ?? null,
                'nama' => $data['nama'] ?? null,
                'jenis_kelamin' => $data['jenis_kelamin'] ?? null,
                'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
                'alamat' => $data['alamat'] ?? null,
                'mst_kelas_id' => $data['mst_kelas_id'] ?? null,
                'status' => $data['status'] ?? null,
            ]);

            $this->clearSiswaCache($id, $data['nis'] ?? null);
            Log::info('Siswa updated', ['siswa_id' => $id]);

            return $siswa;
        });
    }

    public function deleteSiswa(int $id): bool
    {
        $siswa = $this->siswaRepository->find($id);
        if (!$siswa) {
            return false;
        }

        $result = $this->siswaRepository->delete($id);

        if ($result) {
            $this->clearSiswaCache($id, $siswa->nis);
            Log::info('Siswa deleted', ['siswa_id' => $id]);
        }

        return $result;
    }

    public function getAbsensiSummary(int $siswaId, string $startDate, string $endDate): array
    {
        return $this->siswaRepository->getAbsensiSummary($siswaId, $startDate, $endDate);
    }

    public function getNilaiRataRata(int $siswaId, string $semester, string $tahunAjaran): ?float
    {
        return $this->siswaRepository->getNilaiRataRata($siswaId, $semester, $tahunAjaran);
    }

    public function naikKelas(int $siswaId, int $newKelasId): MstSiswa
    {
        return DB::transaction(function () use ($siswaId, $newKelasId) {
            $siswa = $this->siswaRepository->update($siswaId, [
                'mst_kelas_id' => $newKelasId,
            ]);

            $this->clearSiswaCache($siswaId, $siswa->nis);
            Log::info('Siswa naik kelas', ['siswa_id' => $siswaId, 'new_kelas_id' => $newKelasId]);

            return $siswa;
        });
    }

    public function lulus(int $siswaId): MstSiswa
    {
        $statusLulus = SysReference::where('kategori', 'status_siswa')->where('nama', 'Lulus')->first()?->kode;
        if (!$statusLulus) {
            throw new \Exception('Status lulus not found in sys_reference');
        }
        return DB::transaction(function () use ($siswaId, $statusLulus) {
            $siswa = $this->siswaRepository->update($siswaId, [
                'status' => $statusLulus,
                'mst_kelas_id' => null,
            ]);

            $this->clearSiswaCache($siswaId, $siswa->nis);
            Log::info('Siswa lulus', ['siswa_id' => $siswaId]);

            return $siswa;
        });
    }

    private function clearSiswaCache(?int $siswaId = null, ?string $nis = null): void
    {
        Cache::tags(['siswa'])->flush();

        if ($siswaId) {
            Cache::tags(['siswa'])->forget("siswa:{$siswaId}");
            Cache::tags(['siswa'])->forget("siswa:{$siswaId}:full");
        }

        if ($nis) {
            Cache::tags(['siswa'])->forget("siswa:nis:{$nis}");
        }
    }
}
