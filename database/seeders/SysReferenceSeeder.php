<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\System\SysReference;
use Illuminate\Database\Seeder;

class SysReferenceSeeder extends Seeder
{
    public function run(): void
    {
        $references = [
            // Jenis Kelamin
            ['kategori' => 'jenis_kelamin', 'kode' => '1', 'nama' => 'Laki-Laki', 'urutan' => 1],
            ['kategori' => 'jenis_kelamin', 'kode' => '2', 'nama' => 'Perempuan', 'urutan' => 2],

            // Status Siswa
            ['kategori' => 'status_siswa', 'kode' => '1', 'nama' => 'Aktif', 'urutan' => 1],
            ['kategori' => 'status_siswa', 'kode' => '2', 'nama' => 'Lulus', 'urutan' => 2],
            ['kategori' => 'status_siswa', 'kode' => '3', 'nama' => 'Pindah', 'urutan' => 3],

            // Siswa Wali
            ['kategori' => 'siswa_wali', 'kode' => '1', 'nama' => 'Ayah', 'urutan' => 1],
            ['kategori' => 'siswa_wali', 'kode' => '2', 'nama' => 'Ibu', 'urutan' => 2],
            ['kategori' => 'siswa_wali', 'kode' => '3', 'nama' => 'Wali', 'urutan' => 3],

            // Error Logs
            ['kategori' => 'error_logs', 'kode' => '1', 'nama' => 'info', 'urutan' => 1],
            ['kategori' => 'error_logs', 'kode' => '2', 'nama' => 'warning', 'urutan' => 2],
            ['kategori' => 'error_logs', 'kode' => '3', 'nama' => 'error', 'urutan' => 3],
            ['kategori' => 'error_logs', 'kode' => '4', 'nama' => 'critical', 'urutan' => 4],

            // Login Logs
            ['kategori' => 'login_logs', 'kode' => '1', 'nama' => 'success', 'urutan' => 1],
            ['kategori' => 'login_logs', 'kode' => '2', 'nama' => 'failed', 'urutan' => 2],

            // Absensi Status
            ['kategori' => 'absensi_status', 'kode' => '1', 'nama' => 'hadir', 'urutan' => 1],
            ['kategori' => 'absensi_status', 'kode' => '2', 'nama' => 'izin', 'urutan' => 2],
            ['kategori' => 'absensi_status', 'kode' => '3', 'nama' => 'sakit', 'urutan' => 3],
            ['kategori' => 'absensi_status', 'kode' => '4', 'nama' => 'alpha', 'urutan' => 4],

            // BK Kasus Status
            ['kategori' => 'bk_kasus_status', 'kode' => '1', 'nama' => 'dibuka', 'urutan' => 1],
            ['kategori' => 'bk_kasus_status', 'kode' => '2', 'nama' => 'proses', 'urutan' => 2],
            ['kategori' => 'bk_kasus_status', 'kode' => '3', 'nama' => 'selesai', 'urutan' => 3],
            ['kategori' => 'bk_kasus_status', 'kode' => '4', 'nama' => 'dirujuk', 'urutan' => 4],

            // BK Sesi Metode
            ['kategori' => 'bk_sesi_metode', 'kode' => '1', 'nama' => 'Tatap Muka', 'urutan' => 1],
            ['kategori' => 'bk_sesi_metode', 'kode' => '2', 'nama' => 'Online', 'urutan' => 2],
            ['kategori' => 'bk_sesi_metode', 'kode' => '3', 'nama' => 'Telepon', 'urutan' => 3],

            // BK Wali Peran
            ['kategori' => 'bk_wali_peran', 'kode' => '1', 'nama' => 'dipanggil', 'urutan' => 1],
            ['kategori' => 'bk_wali_peran', 'kode' => '2', 'nama' => 'pendamping', 'urutan' => 2],
            ['kategori' => 'bk_wali_peran', 'kode' => '3', 'nama' => 'informasi', 'urutan' => 3],

            // Pembayaran SPP Status
            ['kategori' => 'pembayaran_spp_status', 'kode' => '1', 'nama' => 'Lunas', 'urutan' => 1],
            ['kategori' => 'pembayaran_spp_status', 'kode' => '2', 'nama' => 'Belum Lunas', 'urutan' => 2],
            ['kategori' => 'pembayaran_spp_status', 'kode' => '3', 'nama' => 'Pending', 'urutan' => 3],
            ['kategori' => 'pembayaran_spp_status', 'kode' => '4', 'nama' => 'Batal', 'urutan' => 4],

            // Pembayaran SPP Metode Pembayaran
            ['kategori' => 'pembayaran_spp_metode_pembayaran', 'kode' => '1', 'nama' => 'Tunai', 'urutan' => 1],
            ['kategori' => 'pembayaran_spp_metode_pembayaran', 'kode' => '2', 'nama' => 'Transfer', 'urutan' => 2],
            ['kategori' => 'pembayaran_spp_metode_pembayaran', 'kode' => '3', 'nama' => 'Virtual Account', 'urutan' => 3],
            ['kategori' => 'pembayaran_spp_metode_pembayaran', 'kode' => '4', 'nama' => 'QRIS', 'urutan' => 4],

            // Peminjaman Buku Status
            ['kategori' => 'peminjaman_buku_status', 'kode' => '1', 'nama' => 'dipinjam', 'urutan' => 1],
            ['kategori' => 'peminjaman_buku_status', 'kode' => '2', 'nama' => 'dikembalikan', 'urutan' => 2],
            ['kategori' => 'peminjaman_buku_status', 'kode' => '3', 'nama' => 'hilang', 'urutan' => 3],

            // Rapor Semester
            ['kategori' => 'rapor_semester', 'kode' => '1', 'nama' => 'ganjil', 'urutan' => 1],
            ['kategori' => 'rapor_semester', 'kode' => '2', 'nama' => 'genap', 'urutan' => 2],

            // Ujian Jenis
            ['kategori' => 'ujian_jenis', 'kode' => '1', 'nama' => 'Harian', 'urutan' => 1],
            ['kategori' => 'ujian_jenis', 'kode' => '2', 'nama' => 'UTS', 'urutan' => 2],
            ['kategori' => 'ujian_jenis', 'kode' => '3', 'nama' => 'UAS', 'urutan' => 3],
        ];

        foreach ($references as $ref) {
            SysReference::firstOrCreate(
                ['kategori' => $ref['kategori'], 'kode' => $ref['kode']],
                $ref
            );
        }

        $this->command->info('System references seeded successfully!');
    }
}