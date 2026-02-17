<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Transaction\TrxTugasSiswa;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TugasSiswaService
{
    public function getAllTugasSiswa(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = TrxTugasSiswa::query()->with(['tugas.guruMapel.guru', 'tugas.guruMapel.mapel', 'tugas.kelas', 'siswa']);

        if (!empty($filters['mst_tugas_id'])) {
            $query->where('mst_tugas_id', $filters['mst_tugas_id']);
        }

        if (!empty($filters['mst_siswa_id'])) {
            $query->where('mst_siswa_id', $filters['mst_siswa_id']);
        }

        if (isset($filters['status_kumpl']) && $filters['status_kumpl'] !== '') {
            $query->where('status_kumpl', $filters['status_kumpl']);
        }

        return $query->orderBy('created_at', 'desc')->cursorPaginate($perPage);
    }

    public function getTugasSiswaById(int $id): ?TrxTugasSiswa
    {
        return TrxTugasSiswa::with(['tugas.guruMapel.guru', 'tugas.guruMapel.mapel', 'tugas.kelas', 'siswa'])->find($id);
    }

    public function getTugasSiswaByTugas(int $tugasId, array $filters = []): Collection
    {
        $query = TrxTugasSiswa::query()->with(['siswa'])
            ->where('mst_tugas_id', $tugasId);

        if (isset($filters['status_kumpl']) && $filters['status_kumpl'] !== '') {
            $query->where('status_kumpl', $filters['status_kumpl']);
        }

        return $query->orderBy('waktu_kumpl', 'asc')->get();
    }

    public function getTugasSiswaBySiswa(int $siswaId, array $filters = []): Collection
    {
        $query = TrxTugasSiswa::query()->with(['tugas.guruMapel.guru', 'tugas.guruMapel.mapel', 'tugas.kelas'])
            ->where('mst_siswa_id', $siswaId);

        if (!empty($filters['mst_tugas_id'])) {
            $query->where('mst_tugas_id', $filters['mst_tugas_id']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function getTugasSiswaBySiswaAndTugas(int $siswaId, int $tugasId): ?TrxTugasSiswa
    {
        return TrxTugasSiswa::with(['tugas.guruMapel.guru', 'tugas.guruMapel.mapel', 'tugas.kelas', 'siswa'])
            ->where('mst_siswa_id', $siswaId)
            ->where('mst_tugas_id', $tugasId)
            ->first();
    }

    public function createTugasSiswa(array $data): TrxTugasSiswa
    {
        return DB::transaction(function () use ($data) {
            $tugas = \App\Models\Master\MstTugas::find($data['mst_tugas_id']);
            
            // Calculate status based on deadline
            $statusKumpl = 0; // Belum
            if (!empty($data['waktu_kumpl'])) {
                $waktuKumpl = \Carbon\Carbon::parse($data['waktu_kumpl']);
                $tenggatWaktu = $tugas->tenggat_waktu;
                
                if ($waktuKumpl->greaterThan($tenggatWaktu)) {
                    $statusKumpl = 2; // Terlambat
                } else {
                    $statusKumpl = 1; // Tepat Waktu
                }
            }

            $trxTugasSiswa = TrxTugasSiswa::create([
                'mst_tugas_id' => $data['mst_tugas_id'],
                'mst_siswa_id' => $data['mst_siswa_id'],
                'jawaban_teks' => $data['jawaban_teks'] ?? null,
                'file_siswa' => $data['file_siswa'] ?? null,
                'waktu_kumpl' => $data['waktu_kumpl'] ?? null,
                'nilai' => $data['nilai'] ?? 0,
                'catatan_guru' => $data['catatan_guru'] ?? null,
                'status_kumpl' => $statusKumpl,
            ]);

            Log::info('Tugas siswa created', ['trx_tugas_siswa_id' => $trxTugasSiswa->id]);
            return $trxTugasSiswa;
        });
    }

    public function updateTugasSiswa(int $id, array $data): TrxTugasSiswa
    {
        return DB::transaction(function () use ($id, $data) {
            $trxTugasSiswa = TrxTugasSiswa::findOrFail($id);
            
            // Recalculate status if waktu_kumpl changed
            $waktuKumpl = !empty($data['waktu_kumpl']) 
                ? \Carbon\Carbon::parse($data['waktu_kumpl']) 
                : $trxTugasSiswa->waktu_kumpl;
            
            $tugas = $trxTugasSiswa->tugas;
            $statusKumpl = $trxTugasSiswa->status_kumpl;
            
            if ($waktuKumpl && $tugas) {
                if ($waktuKumpl->greaterThan($tugas->tenggat_waktu)) {
                    $statusKumpl = 2; // Terlambat
                } else {
                    $statusKumpl = 1; // Tepat Waktu
                }
            }

            $trxTugasSiswa->update([
                'jawaban_teks' => $data['jawaban_teks'] ?? $trxTugasSiswa->jawaban_teks,
                'file_siswa' => $data['file_siswa'] ?? $trxTugasSiswa->file_siswa,
                'waktu_kumpl' => $data['waktu_kumpl'] ?? $trxTugasSiswa->waktu_kumpl,
                'nilai' => $data['nilai'] ?? $trxTugasSiswa->nilai,
                'catatan_guru' => $data['catatan_guru'] ?? $trxTugasSiswa->catatan_guru,
                'status_kumpl' => $data['status_kumpl'] ?? $statusKumpl,
            ]);

            Log::info('Tugas siswa updated', ['trx_tugas_siswa_id' => $id]);
            return $trxTugasSiswa;
        });
    }

    public function nilaiTugasSiswa(int $id, array $data): TrxTugasSiswa
    {
        return DB::transaction(function () use ($id, $data) {
            $trxTugasSiswa = TrxTugasSiswa::findOrFail($id);
            
            $trxTugasSiswa->update([
                'nilai' => $data['nilai'],
                'catatan_guru' => $data['catatan_guru'] ?? $trxTugasSiswa->catatan_guru,
            ]);

            Log::info('Tugas siswa graded', ['trx_tugas_siswa_id' => $id, 'nilai' => $data['nilai']]);
            return $trxTugasSiswa;
        });
    }

    public function deleteTugasSiswa(int $id): bool
    {
        $trxTugasSiswa = TrxTugasSiswa::find($id);
        if (!$trxTugasSiswa) {
            return false;
        }

        $result = $trxTugasSiswa->delete();
        Log::info('Tugas siswa deleted', ['trx_tugas_siswa_id' => $id]);
        return $result;
    }
}
