<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Master\MstBkJenis;
use App\Models\Master\MstBkKategori;
use App\Models\Master\MstKelas;
use App\Models\Master\MstMapel;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedKelas();
        $this->seedMapel();
        $this->seedBkJenis();
        $this->seedBkKategori();

        $this->command->info('Master data seeded successfully!');
    }

    private function seedKelas(): void
    {
        $kelas = [
            ['nama_kelas' => 'X IPA 1', 'tingkat' => 10, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'X IPA 2', 'tingkat' => 10, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'X IPS 1', 'tingkat' => 10, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XI IPA 1', 'tingkat' => 11, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XI IPA 2', 'tingkat' => 11, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XI IPS 1', 'tingkat' => 11, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XII IPA 1', 'tingkat' => 12, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XII IPA 2', 'tingkat' => 12, 'tahun_ajaran' => '2024/2025'],
            ['nama_kelas' => 'XII IPS 1', 'tingkat' => 12, 'tahun_ajaran' => '2024/2025'],
        ];

        foreach ($kelas as $k) {
            MstKelas::firstOrCreate(
                ['nama_kelas' => $k['nama_kelas'], 'tahun_ajaran' => $k['tahun_ajaran']],
                $k
            );
        }

        $this->command->info('Kelas seeded!');
    }

    private function seedMapel(): void
    {
        $mapel = [
            ['kode_mapel' => 'PAI', 'nama_mapel' => 'Pendidikan Agama Islam'],
            ['kode_mapel' => 'PKN', 'nama_mapel' => 'Pendidikan Kewarganegaraan'],
            ['kode_mapel' => 'BI', 'nama_mapel' => 'Bahasa Indonesia'],
            ['kode_mapel' => 'BIG', 'nama_mapel' => 'Bahasa Inggris'],
            ['kode_mapel' => 'MTK', 'nama_mapel' => 'Matematika'],
            ['kode_mapel' => 'FIS', 'nama_mapel' => 'Fisika'],
            ['kode_mapel' => 'KIM', 'nama_mapel' => 'Kimia'],
            ['kode_mapel' => 'BIO', 'nama_mapel' => 'Biologi'],
            ['kode_mapel' => 'SEJ', 'nama_mapel' => 'Sejarah'],
            ['kode_mapel' => 'SOS', 'nama_mapel' => 'Sosiologi'],
            ['kode_mapel' => 'EKO', 'nama_mapel' => 'Ekonomi'],
            ['kode_mapel' => 'GEO', 'nama_mapel' => 'Geografi'],
            ['kode_mapel' => 'SNB', 'nama_mapel' => 'Seni Budaya'],
            ['kode_mapel' => 'PJOK', 'nama_mapel' => 'Pendidikan Jasmani dan Kesehatan'],
            ['kode_mapel' => 'PKK', 'nama_mapel' => 'Prakarya dan Kewirausahaan'],
            ['kode_mapel' => 'TIK', 'nama_mapel' => 'Teknologi Informasi dan Komunikasi'],
        ];

        foreach ($mapel as $m) {
            MstMapel::firstOrCreate(
                ['kode_mapel' => $m['kode_mapel']],
                $m
            );
        }

        $this->command->info('Mata pelajaran seeded!');
    }

    private function seedBkJenis(): void
    {
        $jenis = [
            ['nama' => 'Akademik'],
            ['nama' => 'Perilaku'],
            ['nama' => 'Sosial'],
            ['nama' => 'Karakter'],
            ['nama' => 'Karier'],
            ['nama' => 'Pribadi'],
        ];

        foreach ($jenis as $j) {
            MstBkJenis::firstOrCreate(['nama' => $j['nama']], $j);
        }

        $this->command->info('BK Jenis seeded!');
    }

    private function seedBkKategori(): void
    {
        $kategori = [
            ['nama' => 'Kesulitan Belajar'],
            ['nama' => 'Masalah Disiplin'],
            ['nama' => 'Kasus Bullying'],
            ['nama' => 'Masalah Keluarga'],
            ['nama' => 'Kecanduan Gadget'],
            ['nama' => 'Masalah Teman Sebaya'],
            ['nama' => 'Kecemasan'],
            ['nama' => 'Depresi'],
            ['nama' => 'Karier dan Masa Depan'],
            ['nama' => 'Lainnya'],
        ];

        foreach ($kategori as $k) {
            MstBkKategori::firstOrCreate(['nama' => $k['nama']], $k);
        }

        $this->command->info('BK Kategori seeded!');
    }

}