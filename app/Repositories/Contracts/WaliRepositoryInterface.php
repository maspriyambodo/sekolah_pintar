<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Master\MstWali;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;

interface WaliRepositoryInterface extends BaseRepositoryInterface
{
    public function getAllWithSearch(array $filters = [], int $perPage = 15): CursorPaginator;

    public function getWithRelations(int $id): ?MstWali;

    public function getSiswaByWali(int $waliId): Collection;
}
