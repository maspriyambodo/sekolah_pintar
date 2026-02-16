<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstWali;
use App\Repositories\Contracts\WaliRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WaliService
{
    private WaliRepositoryInterface $waliRepository;

    public function __construct(WaliRepositoryInterface $waliRepository)
    {
        $this->waliRepository = $waliRepository;
    }

    public function getAllWali(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $cacheKey = 'wali:list:' . md5(serialize($filters) . $perPage);

        return Cache::tags(['wali'])->remember(
            $cacheKey,
            180, // 3 minutes
            fn () => $this->waliRepository->getAllWithSearch($filters, $perPage)
        );
    }

    public function getWaliById(int $id): ?MstWali
    {
        return $this->waliRepository->getWithRelations($id);
    }

    public function createWali(array $data): MstWali
    {
        return DB::transaction(function () use ($data) {
            $wali = $this->waliRepository->create([
                'sys_user_id' => $data['sys_user_id'] ?? null,
                'nama' => $data['nama'],
                'no_hp' => $data['no_hp'] ?? null,
                'alamat' => $data['alamat'] ?? null,
            ]);

            $this->clearWaliCache();
            Log::info('Wali created', ['wali_id' => $wali->id, 'nama' => $wali->nama]);

            return $wali;
        });
    }

    public function updateWali(int $id, array $data): MstWali
    {
        return DB::transaction(function () use ($id, $data) {
            $wali = $this->waliRepository->update($id, [
                'sys_user_id' => $data['sys_user_id'] ?? null,
                'nama' => $data['nama'] ?? null,
                'no_hp' => $data['no_hp'] ?? null,
                'alamat' => $data['alamat'] ?? null,
            ]);

            $this->clearWaliCache($id);
            Log::info('Wali updated', ['wali_id' => $id]);

            return $wali;
        });
    }

    public function deleteWali(int $id): bool
    {
        $wali = $this->waliRepository->find($id);
        if (!$wali) {
            return false;
        }

        $result = $this->waliRepository->delete($id);

        if ($result) {
            $this->clearWaliCache($id);
            Log::info('Wali deleted', ['wali_id' => $id]);
        }

        return $result;
    }

    public function getSiswaByWali(int $waliId): Collection
    {
        return $this->waliRepository->getSiswaByWali($waliId);
    }

    private function clearWaliCache(?int $waliId = null): void
    {
        Cache::tags(['wali'])->flush();

        if ($waliId) {
            Cache::tags(['wali'])->forget("wali:{$waliId}");
            Cache::tags(['wali'])->forget("wali:{$waliId}:full");
        }
    }
}
