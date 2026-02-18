<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstSekolah;
use App\Models\System\SysSekolahSettings;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SekolahService
{
    public function getAllSekolah(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = MstSekolah::query()->with('settings');

        if (!empty($filters['search'])) {
            $query->where('nama_sekolah', 'like', '%' . $filters['search'] . '%')
                ->orWhere('npsn', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['subscription_plan'])) {
            $query->where('subscription_plan', $filters['subscription_plan']);
        }

        return $query->orderBy('nama_sekolah')->cursorPaginate($perPage);
    }

    public function getSekolahById(int $id): ?MstSekolah
    {
        return MstSekolah::with('settings')->find($id);
    }

    public function getSekolahByUuid(string $uuid): ?MstSekolah
    {
        return MstSekolah::with('settings')->where('uuid', $uuid)->first();
    }

    public function createSekolah(array $data): MstSekolah
    {
        return DB::transaction(function () use ($data) {
            $sekolah = MstSekolah::create([
                'uuid' => $data['uuid'] ?? Str::uuid()->toString(),
                'npsn' => $data['npsn'] ?? null,
                'nama_sekolah' => $data['nama_sekolah'],
                'alamat' => $data['alamat'] ?? null,
                'logo_path' => $data['logo_path'] ?? null,
                'is_active' => $data['is_active'] ?? true,
                'subscription_plan' => $data['subscription_plan'] ?? 'free',
            ]);

            Log::info('Sekolah created', ['sekolah_id' => $sekolah->id]);
            return $sekolah;
        });
    }

    public function updateSekolah(int $id, array $data): MstSekolah
    {
        return DB::transaction(function () use ($id, $data) {
            $sekolah = MstSekolah::findOrFail($id);
            $sekolah->update([
                'npsn' => $data['npsn'] ?? $sekolah->npsn,
                'nama_sekolah' => $data['nama_sekolah'] ?? $sekolah->nama_sekolah,
                'alamat' => $data['alamat'] ?? $sekolah->alamat,
                'logo_path' => $data['logo_path'] ?? $sekolah->logo_path,
                'is_active' => $data['is_active'] ?? $sekolah->is_active,
                'subscription_plan' => $data['subscription_plan'] ?? $sekolah->subscription_plan,
            ]);

            Log::info('Sekolah updated', ['sekolah_id' => $id]);
            return $sekolah;
        });
    }

    public function deleteSekolah(int $id): bool
    {
        $sekolah = MstSekolah::find($id);
        if (!$sekolah) {
            return false;
        }

        $result = $sekolah->delete();
        Log::info('Sekolah deleted', ['sekolah_id' => $id]);
        return $result;
    }

    public function getSetting(int $sekolahId, string $key): ?string
    {
        $setting = SysSekolahSettings::where('mst_sekolah_id', $sekolahId)
            ->where('key', $key)
            ->first();

        return $setting?->value;
    }

    public function setSetting(int $sekolahId, string $key, ?string $value): SysSekolahSettings
    {
        return DB::transaction(function () use ($sekolahId, $key, $value) {
            $setting = SysSekolahSettings::updateOrCreate(
                ['mst_sekolah_id' => $sekolahId, 'key' => $key],
                ['value' => $value]
            );

            Log::info('Sekolah setting updated', [
                'sekolah_id' => $sekolahId,
                'key' => $key,
            ]);

            return $setting;
        });
    }

    public function deleteSetting(int $sekolahId, string $key): bool
    {
        $setting = SysSekolahSettings::where('mst_sekolah_id', $sekolahId)
            ->where('key', $key)
            ->first();

        if (!$setting) {
            return false;
        }

        $result = $setting->delete();
        Log::info('Sekolah setting deleted', [
            'sekolah_id' => $sekolahId,
            'key' => $key,
        ]);

        return $result;
    }

    public function getSettingsBySekolah(int $sekolahId): Collection
    {
        return SysSekolahSettings::where('mst_sekolah_id', $sekolahId)->get();
    }
}
