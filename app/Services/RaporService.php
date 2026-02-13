<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaction\TrxRapor;
use App\Models\Transaction\TrxRaporDetail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RaporService
{
    public function getAllRapor(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = TrxRapor::query()->with(['siswa', 'kelas']);

        if (!empty($filters['siswa_id'])) {
            $query->where('mst_siswa_id', $filters['siswa_id']);
        }

        if (!empty($filters['semester'])) {
            $query->where('semester', $filters['semester']);
        }

        if (!empty($filters['tahun_ajaran'])) {
            $query->where('tahun_ajaran', $filters['tahun_ajaran']);
        }

        return $query->orderBy('created_at', 'desc')->cursorPaginate($perPage);
    }

    public function getRaporById(int $id): ?TrxRapor
    {
        return TrxRapor::with(['siswa', 'kelas', 'detail.mapel'])->find($id);
    }

    public function createRapor(array $data): TrxRapor
    {
        return DB::transaction(function () use ($data) {
            $rapor = TrxRapor::create([
                'mst_siswa_id' => $data['mst_siswa_id'],
                'mst_kelas_id' => $data['mst_kelas_id'],
                'semester' => $data['semester'],
                'tahun_ajaran' => $data['tahun_ajaran'],
                'catatan_wali' => $data['catatan_wali'] ?? null,
                'sakit' => $data['sakit'] ?? 0,
                'izin' => $data['izin'] ?? 0,
                'tanpa_keterangan' => $data['tanpa_keterangan'] ?? 0,
            ]);

            // Create rapor details if provided
            if (!empty($data['details'])) {
                foreach ($data['details'] as $detail) {
                    TrxRaporDetail::create([
                        'trx_rapor_id' => $rapor->id,
                        'mst_mapel_id' => $detail['mst_mapel_id'],
                        'nilai_pengetahuan' => $detail['nilai_pengetahuan'],
                        'nilai_keterampilan' => $detail['nilai_keterampilan'] ?? null,
                        'nilai_akhir' => $detail['nilai_akhir'] ?? null,
                        'predikat' => $detail['predikat'] ?? null,
                        'deskripsi' => $detail['deskripsi'] ?? null,
                    ]);
                }
            }

            Log::info('Rapor created', ['rapor_id' => $rapor->id]);
            return $rapor;
        });
    }

    public function updateRapor(int $id, array $data): TrxRapor
    {
        return DB::transaction(function () use ($id, $data) {
            $rapor = TrxRapor::findOrFail($id);
            $rapor->update([
                'mst_siswa_id' => $data['mst_siswa_id'] ?? $rapor->mst_siswa_id,
                'mst_kelas_id' => $data['mst_kelas_id'] ?? $rapor->mst_kelas_id,
                'semester' => $data['semester'] ?? $rapor->semester,
                'tahun_ajaran' => $data['tahun_ajaran'] ?? $rapor->tahun_ajaran,
                'catatan_wali' => $data['catatan_wali'] ?? $rapor->catatan_wali,
                'sakit' => $data['sakit'] ?? $rapor->sakit,
                'izin' => $data['izin'] ?? $rapor->izin,
                'tanpa_keterangan' => $data['tanpa_keterangan'] ?? $rapor->tanpa_keterangan,
            ]);

            Log::info('Rapor updated', ['rapor_id' => $id]);
            return $rapor;
        });
    }

    public function deleteRapor(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $rapor = TrxRapor::find($id);
            if (!$rapor) {
                return false;
            }

            // Delete details first
            TrxRaporDetail::where('trx_rapor_id', $id)->delete();

            $result = $rapor->delete();
            Log::info('Rapor deleted', ['rapor_id' => $id]);
            return $result;
        });
    }

    public function getRaporBySiswa(int $siswaId): Collection
    {
        return TrxRapor::where('mst_siswa_id', $siswaId)
            ->with(['kelas', 'detail.mapel'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getRaporDetail(int $id): array
    {
        $rapor = TrxRapor::with(['siswa', 'kelas', 'detail.mapel'])->find($id);

        if (!$rapor) {
            return [];
        }

        return [
            'id' => $rapor->id,
            'siswa' => [
                'id' => $rapor->siswa->id,
                'nis' => $rapor->siswa->nis,
                'nama' => $rapor->siswa->nama,
            ],
            'kelas' => [
                'id' => $rapor->kelas->id,
                'nama_kelas' => $rapor->kelas->nama_kelas,
            ],
            'semester' => $rapor->semester,
            'tahun_ajaran' => $rapor->tahun_ajaran,
            'catatan_wali' => $rapor->catatan_wali,
            'kehadiran' => [
                'sakit' => $rapor->sakit,
                'izin' => $rapor->izin,
                'tanpa_keterangan' => $rapor->tanpa_keterangan,
            ],
            'detail' => $rapor->detail->map(function ($d) {
                return [
                    'mapel' => [
                        'id' => $d->mapel->id,
                        'kode' => $d->mapel->kode,
                        'nama' => $d->mapel->nama,
                    ],
                    'nilai_pengetahuan' => $d->nilai_pengetahuan,
                    'nilai_keterampilan' => $d->nilai_keterampilan,
                    'nilai_akhir' => $d->nilai_akhir,
                    'predikat' => $d->predikat,
                    'deskripsi' => $d->deskripsi,
                ];
            }),
        ];
    }
}
