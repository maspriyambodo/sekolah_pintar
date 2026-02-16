<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Master\MstWali;
use App\Repositories\Contracts\WaliRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;

class WaliRepository extends BaseRepository implements WaliRepositoryInterface
{
    public function __construct(MstWali $model)
    {
        parent::__construct($model);
    }

    public function getAllWithSearch(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = $this->model->newQuery();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        return $query->cursorPaginate($perPage);
    }

    public function getWithRelations(int $id): ?MstWali
    {
        return $this->model->with(['user', 'siswa'])->find($id);
    }

    public function getSiswaByWali(int $waliId): Collection
    {
        $wali = $this->model->with('siswa')->find($waliId);
        
        if (!$wali) {
            return new Collection();
        }
        
        return $wali->siswa;
    }
}
