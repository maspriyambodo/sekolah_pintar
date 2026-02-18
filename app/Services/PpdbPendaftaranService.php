<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Ppdb\PpdbPendaftaran;
use App\Models\Ppdb\PpdbGelombang;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PpdbPendaftaranService
{
    public function getAllPendaftaran(array $filters = [], int $perPage = 15): CursorPaginator
    {
        $query = PpdbPendaftaran::query()->with(['sekolah', 'gelombang']);

        if (!empty($filters['mst_sekolah_id'])) {
            $query->where('mst_sekolah_id', $filters['mst_sekolah_id']);
        }

        if (!empty($filters['ppdb_gelombang_id'])) {
            $query->where('ppdb_gelombang_id', $filters['ppdb_gelombang_id']);
        }

        if (!empty($filters['status_pendaftaran'])) {
            $query->where('status_pendaftaran', $filters['status_pendaftaran']);
        }

        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('nama_lengkap', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('no_pendaftaran', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                    ->orWhere('nisn', 'like', '%' . $filters['search'] . '%');
            });
        }

        return $query->orderBy('created_at', 'desc')->cursorPaginate($perPage);
    }

    public function getPendaftaranById(int $id): ?PpdbPendaftaran
    {
        return PpdbPendaftaran::with(['sekolah', 'gelombang', 'dokumens'])->find($id);
    }

    public function getPendaftaranByNo(string $noPendaftaran): ?PpdbPendaftaran
    {
        return PpdbPendaftaran::with(['sekolah', 'gelombang', 'dokumens'])
            ->where('no_pendaftaran', $noPendaftaran)
            ->first();
    }

    public function getPendaftaranByEmail(string $email, int $sekolahId): ?PpdbPendaftaran
    {
        return PpdbPendaftaran::where('email', $email)
            ->where('mst_sekolah_id', $sekolahId)
            ->first();
    }

    public function createPendaftaran(array $data): PpdbPendaftaran
    {
        return DB::transaction(function () use ($data) {
            // Generate nomor pendaftaran
            $noPendaftaran = $this->generateNoPendaftaran($data['mst_sekolah_id'], $data['ppdb_gelombang_id']);

            $pendaftaran = PpdbPendaftaran::create([
                'mst_sekolah_id' => $data['mst_sekolah_id'],
                'ppdb_gelombang_id' => $data['ppdb_gelombang_id'],
                'no_pendaftaran' => $noPendaftaran,
                'nama_lengkap' => $data['nama_lengkap'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'] ?? Str::random(8)),
                'nisn' => $data['nisn'] ?? null,
                'jenis_kelamin' => $data['jenis_kelamin'],
                'telp_hp' => $data['telp_hp'] ?? null,
                'asal_sekolah' => $data['asal_sekolah'] ?? null,
                'status_pendaftaran' => $data['status_pendaftaran'] ?? 'draft',
                'pilihan_jurusan_id' => $data['pilihan_jurusan_id'] ?? null,
            ]);

            Log::info('PPDB Pendaftaran created', [
                'pendaftaran_id' => $pendaftaran->id,
                'no_pendaftaran' => $noPendaftaran,
            ]);
            return $pendaftaran;
        });
    }

    public function updatePendaftaran(int $id, array $data): PpdbPendaftaran
    {
        return DB::transaction(function () use ($id, $data) {
            $pendaftaran = PpdbPendaftaran::findOrFail($id);
            $updateData = [];

            if (isset($data['nama_lengkap'])) {
                $updateData['nama_lengkap'] = $data['nama_lengkap'];
            }
            if (isset($data['email'])) {
                $updateData['email'] = $data['email'];
            }
            if (isset($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }
            if (isset($data['nisn'])) {
                $updateData['nisn'] = $data['nisn'];
            }
            if (isset($data['jenis_kelamin'])) {
                $updateData['jenis_kelamin'] = $data['jenis_kelamin'];
            }
            if (isset($data['telp_hp'])) {
                $updateData['telp_hp'] = $data['telp_hp'];
            }
            if (isset($data['asal_sekolah'])) {
                $updateData['asal_sekolah'] = $data['asal_sekolah'];
            }
            if (isset($data['status_pendaftaran'])) {
                $updateData['status_pendaftaran'] = $data['status_pendaftaran'];
            }
            if (isset($data['pilihan_jurusan_id'])) {
                $updateData['pilihan_jurusan_id'] = $data['pilihan_jurusan_id'];
            }

            $pendaftaran->update($updateData);

            Log::info('PPDB Pendaftaran updated', ['pendaftaran_id' => $id]);
            return $pendaftaran;
        });
    }

    public function updateStatus(int $id, string $status): PpdbPendaftaran
    {
        $allowedStatuses = ['draft', 'terverifikasi', 'seleksi', 'diterima', 'cadangan', 'ditolak'];
        
        if (!in_array($status, $allowedStatuses)) {
            throw new \InvalidArgumentException("Status tidak valid: {$status}");
        }

        $pendaftaran = PpdbPendaftaran::findOrFail($id);
        $pendaftaran->update(['status_pendaftaran' => $status]);

        Log::info('PPDB Pendaftaran status updated', [
            'pendaftaran_id' => $id,
            'status' => $status,
        ]);
        return $pendaftaran;
    }

    public function deletePendaftaran(int $id): bool
    {
        $pendaftaran = PpdbPendaftaran::find($id);
        if (!$pendaftaran) {
            return false;
        }

        $result = $pendaftaran->delete();
        Log::info('PPDB Pendaftaran deleted', ['pendaftaran_id' => $id]);
        return $result;
    }

    public function verifyPendaftaran(int $id, string $catatan = null): PpdbPendaftaran
    {
        return $this->updateStatus($id, 'terverifikasi');
    }

    public function acceptPendaftaran(int $id): PpdbPendaftaran
    {
        return $this->updateStatus($id, 'diterima');
    }

    public function rejectPendaftaran(int $id): PpdbPendaftaran
    {
        return $this->updateStatus($id, 'ditolak');
    }

    private function generateNoPendaftaran(int $sekolahId, int $gelombangId): string
    {
        $tahun = date('Y');
        $prefix = 'PPDB-' . $tahun . '-';
        
        $lastPendaftaran = PpdbPendaftaran::where('mst_sekolah_id', $sekolahId)
            ->where('ppdb_gelombang_id', $gelombangId)
            ->where('no_pendaftaran', 'like', $prefix . '%')
            ->orderBy('no_pendaftaran', 'desc')
            ->first();

        if ($lastPendaftaran) {
            $lastNumber = (int) substr($lastPendaftaran->no_pendaftaran, -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad((string) $newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function getStatistics(int $sekolahId, ?int $gelombangId = null): array
    {
        $query = PpdbPendaftaran::where('mst_sekolah_id', $sekolahId);
        
        if ($gelombangId) {
            $query->where('ppdb_gelombang_id', $gelombangId);
        }

        $total = $query->count();
        $draft = (clone $query)->where('status_pendaftaran', 'draft')->count();
        $terverifikasi = (clone $query)->where('status_pendaftaran', 'terverifikasi')->count();
        $seleksi = (clone $query)->where('status_pendaftaran', 'seleksi')->count();
        $diterima = (clone $query)->where('status_pendaftaran', 'diterima')->count();
        $cadangan = (clone $query)->where('status_pendaftaran', 'cadangan')->count();
        $ditolak = (clone $query)->where('status_pendaftaran', 'ditolak')->count();

        return [
            'total' => $total,
            'draft' => $draft,
            'terverifikasi' => $terverifikasi,
            'seleksi' => $seleksi,
            'diterima' => $diterima,
            'cadangan' => $cadangan,
            'ditolak' => $ditolak,
        ];
    }
}
