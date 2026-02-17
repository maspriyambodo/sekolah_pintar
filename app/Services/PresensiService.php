<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaction\TrxPresensi;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PresensiService
{
    public function getAllPresensi(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = TrxPresensi::query()->with(['guruMapel.guru', 'guruMapel.mapel', 'siswa']);

        if (!empty($filters['mst_guru_mapel_id'])) {
            $query->where('mst_guru_mapel_id', $filters['mst_guru_mapel_id']);
        }

        if (!empty($filters['mst_siswa_id'])) {
            $query->where('mst_siswa_id', $filters['mst_siswa_id']);
        }

        if (!empty($filters['tanggal'])) {
            $query->where('tanggal', $filters['tanggal']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['tanggal_awal']) && !empty($filters['tanggal_akhir'])) {
            $query->whereBetween('tanggal', [$filters['tanggal_awal'], $filters['tanggal_akhir']]);
        }

        return $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->cursorPaginate($perPage);
    }

    public function getPresensiById(int $id): ?TrxPresensi
    {
        return TrxPresensi::with(['guruMapel.guru', 'guruMapel.mapel', 'siswa'])->find($id);
    }

    public function getPresensiBySiswa(int $siswaId, array $filters = []): Collection
    {
        $query = TrxPresensi::query()->with(['guruMapel.guru', 'guruMapel.mapel'])
            ->where('mst_siswa_id', $siswaId);

        if (!empty($filters['mst_guru_mapel_id'])) {
            $query->where('mst_guru_mapel_id', $filters['mst_guru_mapel_id']);
        }

        if (!empty($filters['tanggal'])) {
            $query->where('tanggal', $filters['tanggal']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['tanggal_awal']) && !empty($filters['tanggal_akhir'])) {
            $query->whereBetween('tanggal', [$filters['tanggal_awal'], $filters['tanggal_akhir']]);
        }

        return $query->orderBy('tanggal', 'desc')->get();
    }

    public function getPresensiByGuruMapel(int $guruMapelId, array $filters = []): Collection
    {
        $query = TrxPresensi::query()->with(['siswa'])
            ->where('mst_guru_mapel_id', $guruMapelId);

        if (!empty($filters['mst_siswa_id'])) {
            $query->where('mst_siswa_id', $filters['mst_siswa_id']);
        }

        if (!empty($filters['tanggal'])) {
            $query->where('tanggal', $filters['tanggal']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['tanggal_awal']) && !empty($filters['tanggal_akhir'])) {
            $query->whereBetween('tanggal', [$filters['tanggal_awal'], $filters['tanggal_akhir']]);
        }

        return $query->orderBy('tanggal', 'desc')->get();
    }

    public function getPresensiByDate(string $tanggal, array $filters = []): Collection
    {
        $query = TrxPresensi::query()->with(['guruMapel.guru', 'guruMapel.mapel', 'siswa'])
            ->where('tanggal', $tanggal);

        if (!empty($filters['mst_guru_mapel_id'])) {
            $query->where('mst_guru_mapel_id', $filters['mst_guru_mapel_id']);
        }

        if (!empty($filters['mst_siswa_id'])) {
            $query->where('mst_siswa_id', $filters['mst_siswa_id']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getPresensiSummary(int $siswaId, string $tanggalAwal, string $tanggalAkhir): array
    {
        $presensi = TrxPresensi::where('mst_siswa_id', $siswaId)
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->get();

        // Get status references
        $statusReferences = \App\Models\System\SysReference::where('kode_ref', 'status_presensi')->get()->keyBy('value');

        return [
            'total' => $presensi->count(),
            'hadir' => $presensi->where('status', $statusReferences->firstWhere('nama', 'hadir')?->value ?? 1)->count(),
            'izin' => $presensi->where('status', $statusReferences->firstWhere('nama', 'izin')?->value ?? 2)->count(),
            'sakit' => $presensi->where('status', $statusReferences->firstWhere('nama', 'sakit')?->value ?? 3)->count(),
            'alpha' => $presensi->where('status', $statusReferences->firstWhere('nama', 'alpha')?->value ?? 4)->count(),
            'periode' => [
                'tanggal_awal' => $tanggalAwal,
                'tanggal_akhir' => $tanggalAkhir,
            ],
        ];
    }

    public function createPresensi(array $data): TrxPresensi
    {
        return DB::transaction(function () use ($data) {
            // Check if presensi already exists for this siswa, guru mapel, and tanggal
            $existing = TrxPresensi::where('mst_siswa_id', $data['mst_siswa_id'])
                ->where('mst_guru_mapel_id', $data['mst_guru_mapel_id'])
                ->where('tanggal', $data['tanggal'])
                ->first();

            if ($existing) {
                $existing->update([
                    'jam_masuk' => $data['jam_masuk'] ?? $existing->jam_masuk,
                    'status' => $data['status'] ?? $existing->status,
                    'keterangan' => $data['keterangan'] ?? $existing->keterangan,
                ]);

                Log::info('Presensi updated', ['presensi_id' => $existing->id]);
                return $existing;
            }

            $presensi = TrxPresensi::create([
                'mst_guru_mapel_id' => $data['mst_guru_mapel_id'],
                'mst_siswa_id' => $data['mst_siswa_id'],
                'tanggal' => $data['tanggal'],
                'jam_masuk' => $data['jam_masuk'] ?? null,
                'status' => $data['status'],
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            Log::info('Presensi created', ['presensi_id' => $presensi->id]);
            return $presensi;
        });
    }

    public function updatePresensi(int $id, array $data): TrxPresensi
    {
        return DB::transaction(function () use ($id, $data) {
            $presensi = TrxPresensi::findOrFail($id);

            $presensi->update([
                'jam_masuk' => $data['jam_masuk'] ?? $presensi->jam_masuk,
                'status' => $data['status'] ?? $presensi->status,
                'keterangan' => $data['keterangan'] ?? $presensi->keterangan,
            ]);

            Log::info('Presensi updated', ['presensi_id' => $id]);
            return $presensi;
        });
    }

    public function deletePresensi(int $id): bool
    {
        $presensi = TrxPresensi::find($id);
        if (!$presensi) {
            return false;
        }

        $result = $presensi->delete();
        Log::info('Presensi deleted', ['presensi_id' => $id]);
        return $result;
    }

    public function bulkCreatePresensi(array $data): Collection
    {
        return DB::transaction(function () use ($data) {
            $created = [];

            foreach ($data['presensi'] as $item) {
                $existing = TrxPresensi::where('mst_siswa_id', $item['mst_siswa_id'])
                    ->where('mst_guru_mapel_id', $data['mst_guru_mapel_id'])
                    ->where('tanggal', $data['tanggal'])
                    ->first();

                if ($existing) {
                    $existing->update([
                        'jam_masuk' => $item['jam_masuk'] ?? $existing->jam_masuk,
                        'status' => $item['status'] ?? $existing->status,
                        'keterangan' => $item['keterangan'] ?? $existing->keterangan,
                    ]);
                    $created[] = $existing;
                } else {
                    $presensi = TrxPresensi::create([
                        'mst_guru_mapel_id' => $data['mst_guru_mapel_id'],
                        'mst_siswa_id' => $item['mst_siswa_id'],
                        'tanggal' => $data['tanggal'],
                        'jam_masuk' => $item['jam_masuk'] ?? null,
                        'status' => $item['status'],
                        'keterangan' => $item['keterangan'] ?? null,
                    ]);
                    $created[] = $presensi;
                }
            }

            Log::info('Bulk presensi created', ['count' => count($created)]);
            return collect($created);
        });
    }
}
