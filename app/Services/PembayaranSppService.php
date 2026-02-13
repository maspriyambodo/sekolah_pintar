<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstTarifSpp;
use App\Models\Transaction\TrxPembayaranSpp;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PembayaranSppService
{
    public function getAllPembayaranSpp(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = TrxPembayaranSpp::query()->with(['siswa', 'tarifSpp.kelas', 'petugas']);

        if (!empty($filters['siswa_id'])) {
            $query->where('mst_siswa_id', $filters['siswa_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['tahun'])) {
            $query->where('tahun', $filters['tahun']);
        }

        if (!empty($filters['bulan'])) {
            $query->where('bulan', $filters['bulan']);
        }

        return $query->orderBy('tanggal_bayar', 'desc')->cursorPaginate($perPage);
    }

    public function getPembayaranSppById(int $id): ?TrxPembayaranSpp
    {
        return TrxPembayaranSpp::with(['siswa', 'tarifSpp.kelas', 'petugas'])->find($id);
    }

    public function createPembayaranSpp(array $data, int $petugasId): TrxPembayaranSpp
    {
        return DB::transaction(function () use ($data, $petugasId) {
            // Check if pembayaran already exists for this siswa, bulan, tahun
            $existing = TrxPembayaranSpp::where('mst_siswa_id', $data['mst_siswa_id'])
                ->where('bulan', $data['bulan'])
                ->where('tahun', $data['tahun'])
                ->whereIn('status', ['lunas', 'pending'])
                ->first();

            if ($existing) {
                throw new \Exception('Pembayaran untuk bulan dan tahun ini sudah ada');
            }

            // Get tarif SPP
            $tarifSpp = MstTarifSpp::findOrFail($data['mst_tarif_spp_id']);

            $pembayaran = TrxPembayaranSpp::create([
                'mst_siswa_id' => $data['mst_siswa_id'],
                'mst_tarif_spp_id' => $data['mst_tarif_spp_id'],
                'bulan' => $data['bulan'],
                'tahun' => $data['tahun'],
                'tanggal_bayar' => $data['tanggal_bayar'] ?? now(),
                'jumlah_bayar' => $data['jumlah_bayar'],
                'status' => $data['status'] ?? 'lunas',
                'metode_pembayaran' => $data['metode_pembayaran'] ?? 'tunai',
                'keterangan' => $data['keterangan'] ?? null,
                'petugas_id' => $petugasId,
            ]);

            Log::info('Pembayaran SPP created', ['pembayaran_id' => $pembayaran->id]);
            return $pembayaran;
        });
    }

    public function updatePembayaranSpp(int $id, array $data): TrxPembayaranSpp
    {
        return DB::transaction(function () use ($id, $data) {
            $pembayaran = TrxPembayaranSpp::findOrFail($id);

            // Jika status sudah lunas, tidak bisa diubah
            if ($pembayaran->status === 'lunas' && isset($data['status']) && $data['status'] !== 'lunas') {
                throw new \Exception('Pembayaran yang sudah lunas tidak dapat diubah statusnya');
            }

            $pembayaran->update([
                'tanggal_bayar' => $data['tanggal_bayar'] ?? $pembayaran->tanggal_bayar,
                'jumlah_bayar' => $data['jumlah_bayar'] ?? $pembayaran->jumlah_bayar,
                'status' => $data['status'] ?? $pembayaran->status,
                'metode_pembayaran' => $data['metode_pembayaran'] ?? $pembayaran->metode_pembayaran,
                'keterangan' => $data['keterangan'] ?? $pembayaran->keterangan,
            ]);

            Log::info('Pembayaran SPP updated', ['pembayaran_id' => $id]);
            return $pembayaran;
        });
    }

    public function deletePembayaranSpp(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $pembayaran = TrxPembayaranSpp::find($id);
            if (!$pembayaran) {
                return false;
            }

            // Jika status lunas, tidak bisa dihapus
            if ($pembayaran->status === 'lunas') {
                throw new \Exception('Pembayaran yang sudah lunas tidak dapat dihapus');
            }

            $result = $pembayaran->delete();
            Log::info('Pembayaran SPP deleted', ['pembayaran_id' => $id]);
            return $result;
        });
    }

    public function getPembayaranBySiswa(int $siswaId): Collection
    {
        return TrxPembayaranSpp::where('mst_siswa_id', $siswaId)
            ->with(['tarifSpp.kelas'])
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();
    }

    public function getStatusPembayaranSiswa(int $siswaId, string $tahunAjaran): array
    {
        $pembayaran = TrxPembayaranSpp::where('mst_siswa_id', $siswaId)
            ->where('tahun', substr($tahunAjaran, 0, 4))
            ->get();

        $lunasBulan = $pembayaran->where('status', 'lunas')->pluck('bulan')->toArray();
        $belumLunasBulan = $pembayaran->where('status', '!=', 'lunas')->pluck('bulan')->toArray();

        return [
            'tahun_ajaran' => $tahunAjaran,
            'total_bulan' => 12,
            'lunas' => count($lunasBulan),
            'belum_lunas' => count($belumLunasBulan),
            'bulan_lunas' => $lunasBulan,
            'bulan_belum_lunas' => array_diff(range(1, 12), $lunasBulan),
        ];
    }

    public function bayarSpp(array $data, int $petugasId): TrxPembayaranSpp
    {
        return $this->createPembayaranSpp($data, $petugasId);
    }
}
