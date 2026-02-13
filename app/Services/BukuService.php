<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Master\MstBuku;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BukuService
{
    public function getAllBuku(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = MstBuku::query();

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('judul', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('isbn', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('penulis', 'like', '%' . $filters['search'] . '%');
            });
        }

        if (!empty($filters['penulis'])) {
            $query->where('penulis', 'like', '%' . $filters['penulis'] . '%');
        }

        if (!empty($filters['penerbit'])) {
            $query->where('penerbit', 'like', '%' . $filters['penerbit'] . '%');
        }

        if (!empty($filters['tahun'])) {
            $query->where('tahun_terbit', $filters['tahun']);
        }

        return $query->orderBy('judul')->cursorPaginate($perPage);
    }

    public function getBukuById(int $id): ?MstBuku
    {
        return MstBuku::with('kategori')->find($id);
    }

    public function createBuku(array $data): MstBuku
    {
        return DB::transaction(function () use ($data) {
            $buku = MstBuku::create([
                'isbn' => $data['isbn'] ?? null,
                'judul' => $data['judul'],
                'penulis' => $data['penulis'] ?? null,
                'penerbit' => $data['penerbit'] ?? null,
                'tahun_terbit' => $data['tahun_terbit'] ?? null,
                'jumlah_halaman' => $data['jumlah_halaman'] ?? null,
                'stok' => $data['stok'] ?? 0,
                'kategori_id' => $data['kategori_id'] ?? null,
                'deskripsi' => $data['deskripsi'] ?? null,
            ]);

            Log::info('Buku created', ['buku_id' => $buku->id]);
            return $buku;
        });
    }

    public function updateBuku(int $id, array $data): MstBuku
    {
        return DB::transaction(function () use ($id, $data) {
            $buku = MstBuku::findOrFail($id);
            $buku->update([
                'isbn' => $data['isbn'] ?? $buku->isbn,
                'judul' => $data['judul'] ?? $buku->judul,
                'penulis' => $data['penulis'] ?? $buku->penulis,
                'penerbit' => $data['penerbit'] ?? $buku->penerbit,
                'tahun_terbit' => $data['tahun_terbit'] ?? $buku->tahun_terbit,
                'jumlah_halaman' => $data['jumlah_halaman'] ?? $buku->jumlah_halaman,
                'stok' => $data['stok'] ?? $buku->stok,
                'kategori_id' => $data['kategori_id'] ?? $buku->kategori_id,
                'deskripsi' => $data['deskripsi'] ?? $buku->deskripsi,
            ]);

            Log::info('Buku updated', ['buku_id' => $id]);
            return $buku;
        });
    }

    public function deleteBuku(int $id): bool
    {
        $buku = MstBuku::find($id);
        if (!$buku) {
            return false;
        }

        $result = $buku->delete();
        Log::info('Buku deleted', ['buku_id' => $id]);
        return $result;
    }

    public function getAvailableBuku(): Collection
    {
        return MstBuku::where('stok', '>', 0)
            ->orderBy('judul')
            ->get();
    }

    public function getPeminjamanByBuku(int $id): array
    {
        $buku = MstBuku::with(['peminjaman' => function ($q) {
            $q->whereNull('tanggal_kembali');
        }, 'peminjaman.siswa'])->find($id);

        if (!$buku) {
            return [];
        }

        return [
            'buku' => [
                'id' => $buku->id,
                'judul' => $buku->judul,
                'stok' => $buku->stok,
            ],
            'peminjaman_aktif' => $buku->peminjaman->map(function ($p) {
                return [
                    'id' => $p->id,
                    'siswa' => [
                        'id' => $p->siswa->id,
                        'nama' => $p->siswa->nama,
                        'nis' => $p->siswa->nis,
                    ],
                    'tanggal_pinjam' => $p->tanggal_pinjam,
                ];
            }),
        ];
    }
}
