<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstBuku;
use App\Models\Transaction\TrxPeminjamanBuku;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PeminjamanBukuService
{
    public function getAllPeminjaman(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = TrxPeminjamanBuku::query()->with(['siswa', 'buku']);

        if (!empty($filters['siswa_id'])) {
            $query->where('mst_siswa_id', $filters['siswa_id']);
        }

        if (!empty($filters['buku_id'])) {
            $query->where('mst_buku_id', $filters['buku_id']);
        }

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'dipinjam') {
                $query->whereNull('tanggal_kembali');
            } elseif ($filters['status'] === 'dikembalikan') {
                $query->whereNotNull('tanggal_kembali');
            }
        }

        return $query->orderBy('tanggal_pinjam', 'desc')->cursorPaginate($perPage);
    }

    public function getPeminjamanById(int $id): ?TrxPeminjamanBuku
    {
        return TrxPeminjamanBuku::with(['siswa', 'buku'])->find($id);
    }

    public function createPeminjaman(array $data): TrxPeminjamanBuku
    {
        return DB::transaction(function () use ($data) {
            // Check stok
            $buku = MstBuku::findOrFail($data['mst_buku_id']);
            if ($buku->stok <= 0) {
                throw new \Exception('Buku tidak tersedia');
            }

            $peminjaman = TrxPeminjamanBuku::create([
                'mst_siswa_id' => $data['mst_siswa_id'],
                'mst_buku_id' => $data['mst_buku_id'],
                'tanggal_pinjam' => $data['tanggal_pinjam'] ?? now(),
                'tanggal_jatuh_tempo' => $data['tanggal_jatuh_tempo'],
                'tanggal_kembali' => null,
                'keterangan' => $data['keterangan'] ?? null,
            ]);

            // Kurangi stok
            $buku->decrement('stok');

            Log::info('Peminjaman created', ['peminjaman_id' => $peminjaman->id]);
            return $peminjaman;
        });
    }

    public function updatePeminjaman(int $id, array $data): TrxPeminjamanBuku
    {
        return DB::transaction(function () use ($id, $data) {
            $peminjaman = TrxPeminjamanBuku::findOrFail($id);
            $peminjaman->update([
                'mst_siswa_id' => $data['mst_siswa_id'] ?? $peminjaman->mst_siswa_id,
                'mst_buku_id' => $data['mst_buku_id'] ?? $peminjaman->mst_buku_id,
                'tanggal_pinjam' => $data['tanggal_pinjam'] ?? $peminjaman->tanggal_pinjam,
                'tanggal_jatuh_tempo' => $data['tanggal_jatuh_tempo'] ?? $peminjaman->tanggal_jatuh_tempo,
                'keterangan' => $data['keterangan'] ?? $peminjaman->keterangan,
            ]);

            Log::info('Peminjaman updated', ['peminjaman_id' => $id]);
            return $peminjaman;
        });
    }

    public function deletePeminjaman(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $peminjaman = TrxPeminjamanBuku::find($id);
            if (!$peminjaman) {
                return false;
            }

            // Jika buku masih dipinjam, kembalikan stok
            if (!$peminjaman->tanggal_kembali) {
                MstBuku::find($peminjaman->mst_buku_id)?->increment('stok');
            }

            $result = $peminjaman->delete();
            Log::info('Peminjaman deleted', ['peminjaman_id' => $id]);
            return $result;
        });
    }

    public function pengembalian(int $id): TrxPeminjamanBuku
    {
        return DB::transaction(function () use ($id) {
            $peminjaman = TrxPeminjamanBuku::findOrFail($id);

            if ($peminjaman->tanggal_kembali) {
                throw new \Exception('Buku sudah dikembalikan');
            }

            $peminjaman->update([
                'tanggal_kembali' => now(),
            ]);

            // Tambah stok
            MstBuku::find($peminjaman->mst_buku_id)?->increment('stok');

            Log::info('Pengembalian buku', ['peminjaman_id' => $id]);
            return $peminjaman->fresh();
        });
    }

    public function getPeminjamanBySiswa(int $siswaId): Collection
    {
        return TrxPeminjamanBuku::where('mst_siswa_id', $siswaId)
            ->with('buku')
            ->orderBy('tanggal_pinjam', 'desc')
            ->get();
    }

    public function getOverduePeminjaman(): Collection
    {
        return TrxPeminjamanBuku::whereNull('tanggal_kembali')
            ->where('tanggal_jatuh_tempo', '<', now())
            ->with(['siswa', 'buku'])
            ->get();
    }
}
