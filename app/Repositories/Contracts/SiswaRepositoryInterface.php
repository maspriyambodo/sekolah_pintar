<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Master\MstSiswa;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;

interface SiswaRepositoryInterface extends BaseRepositoryInterface
{
    public function findByNis(string $nis): ?MstSiswa;

    public function getByKelas(int $kelasId): Collection;

    public function getByStatus(string $status): Collection;

    public function getWithRelations(int $id): ?MstSiswa;

    public function paginateWithFilters(array $filters = [], int $perPage = 15): CursorPaginator;

    public function getAbsensiSummary(int $siswaId, string $startDate, string $endDate): array;

    public function getNilaiRataRata(int $siswaId, string $semester, string $tahunAjaran): ?float;
}
