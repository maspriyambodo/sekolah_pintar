<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Master\MstBkJenis;
use App\Models\Master\MstBkKategori;
use App\Models\Master\MstKelas;
use App\Models\Master\MstMapel;
use App\Models\Master\MstBuku;
use App\Models\Master\MstGuruMapel;
use App\Models\Master\MstGuru;
use App\Models\Master\MstMateri;
use App\Models\Master\MstSiswaWali;
use App\Models\Master\MstSiswa;
use App\Models\Master\MstSoalOpsi;
use App\Models\Master\MstSoal;
use App\Models\Master\MstTarifSpp;
use App\Models\Master\MstTugas;
use App\Models\Master\MstWaliMurid;
use App\Models\Master\MstWali;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedKelas();
        $this->seedMapel();
        $this->seedBkJenis();
        $this->seedBkKategori();
        $this->seedMstBuku();
        $this->seedGuruMapel();
        $this->seedGuru();
        $this->seedMateri();
        $this->seedSiswaWali();
        $this->seedSiswa();
        $this->seedSoalOpsi();
        $this->seedSoal();
        $this->seedTarifSpp();
        $this->seedTugas();
        $this->seedWaliMurid();
        $this->seedWali();

        $this->command->info('Master data seeded successfully from JSON files!');
    }

    /**
     * Load data from JSON file
     */
    private function loadJsonData(string $filename): array
    {
        $jsonPath = database_path('seeders/json/' . $filename);
        
        if (!File::exists($jsonPath)) {
            $this->command->warn("Warning: {$filename} file not found!");
            return [];
        }

        $jsonData = File::get($jsonPath);
        $data = json_decode($jsonData, true);

        if (!isset($data['RECORDS']) || !is_array($data['RECORDS'])) {
            $this->command->warn("Warning: Invalid JSON format in {$filename}!");
            return [];
        }

        return $data['RECORDS'];
    }

    private function seedKelas(): void
    {
        $records = $this->loadJsonData('mst_kelas.json');
        
        if (empty($records)) {
            $this->command->info('No kelas data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $k) {
            MstKelas::firstOrCreate(
                ['nama_kelas' => $k['nama_kelas'], 'tahun_ajaran' => $k['tahun_ajaran']],
                [
                    'nama_kelas' => $k['nama_kelas'],
                    'tingkat' => $k['tingkat'],
                    'tahun_ajaran' => $k['tahun_ajaran'],
                    'wali_guru_id' => $k['wali_guru_id'] ?? null,
                ]
            );
        }

        $this->command->info('Kelas seeded from JSON!');
    }

    private function seedMapel(): void
    {
        $records = $this->loadJsonData('mst_mapel.json');
        
        if (empty($records)) {
            $this->command->info('No mapel data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $m) {
            MstMapel::firstOrCreate(
                ['kode_mapel' => $m['kode_mapel']],
                [
                    'kode_mapel' => $m['kode_mapel'],
                    'nama_mapel' => $m['nama_mapel'],
                ]
            );
        }

        $this->command->info('Mata pelajaran seeded from JSON!');
    }

    private function seedBkJenis(): void
    {
        $records = $this->loadJsonData('mst_bk_jenis.json');
        
        if (empty($records)) {
            $this->command->info('No BK Jenis data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $j) {
            MstBkJenis::firstOrCreate(
                ['nama' => $j['nama']],
                ['nama' => $j['nama']]
            );
        }

        $this->command->info('BK Jenis seeded from JSON!');
    }

    private function seedBkKategori(): void
    {
        $records = $this->loadJsonData('mst_bk_kategori.json');
        
        if (empty($records)) {
            $this->command->info('No BK Kategori data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $k) {
            MstBkKategori::firstOrCreate(
                ['nama' => $k['nama']],
                ['nama' => $k['nama']]
            );
        }

        $this->command->info('BK Kategori seeded from JSON!');
    }

    private function seedMstBuku(): void
    {
        $records = $this->loadJsonData('mst_buku.json');
        
        if (empty($records)) {
            $this->command->info('No buku data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $b) {
            MstBuku::firstOrCreate(
                ['kode_buku' => $b['kode_buku']],
                [
                    'kode_buku' => $b['kode_buku'],
                    'judul' => $b['judul'],
                    'pengarang' => $b['pengarang'],
                    'penerbit' => $b['penerbit'],
                    'tahun_terbit' => $b['tahun_terbit'],
                ]
            );
        }

        $this->command->info('Buku seeded from JSON!');
    }

    private function seedGuruMapel(): void
    {
        $records = $this->loadJsonData('mst_guru_mapel.json');
        
        if (empty($records)) {
            $this->command->info('No guru-mapel data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $gm) {
            MstGuruMapel::firstOrCreate(
                ['guru_id' => $gm['guru_id'], 'mapel_id' => $gm['mapel_id']],
                [
                    'guru_id' => $gm['guru_id'],
                    'mapel_id' => $gm['mapel_id'],
                ]
            );
        }

        $this->command->info('Guru-Mapel relationships seeded from JSON!');
    }

    private function seedGuru(): void
    {
        $records = $this->loadJsonData('mst_guru.json');
        
        if (empty($records)) {
            $this->command->info('No guru data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $g) {
            MstGuru::firstOrCreate(
                ['nama' => $g['nama']],
                [
                    'nama' => $g['nama'],
                    'nip' => $g['nip'] ?? null,
                    'email' => $g['email'] ?? null,
                ]
            );
        }

        $this->command->info('Guru seeded from JSON!');
    }

    private function seedMateri(): void
    {
        $records = $this->loadJsonData('mst_materi.json');
        
        if (empty($records)) {
            $this->command->info('No materi data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $m) {
            MstMateri::firstOrCreate(
                ['judul' => $m['judul'], 'mapel_id' => $m['mapel_id']],
                [
                    'judul' => $m['judul'],
                    'mapel_id' => $m['mapel_id'],
                    'deskripsi' => $m['deskripsi'] ?? null,
                ]
            );
        }

        $this->command->info('Materi seeded from JSON!');
    }

    private function seedSiswaWali(): void
    {
        $records = $this->loadJsonData('mst_siswa_wali.json');
        
        if (empty($records)) {
            $this->command->info('No siswa-wali data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $sw) {
            MstSiswaWali::firstOrCreate(
                ['siswa_id' => $sw['siswa_id'], 'wali_id' => $sw['wali_id']],
                [
                    'siswa_id' => $sw['siswa_id'],
                    'wali_id' => $sw['wali_id'],
                ]
            );
        }

        $this->command->info('Siswa-Wali relationships seeded from JSON!');
    }

    private function seedSiswa(): void
    {
        $records = $this->loadJsonData('mst_siswa.json');
        
        if (empty($records)) {
            $this->command->info('No siswa data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $s) {
            MstSiswa::firstOrCreate(
                ['nama' => $s['nama']],
                [
                    'nama' => $s['nama'],
                    'nis' => $s['nis'] ?? null,
                    'email' => $s['email'] ?? null,
                ]
            );
        }

        $this->command->info('Siswa seeded from JSON!');
    }

    private function seedSoalOpsi(): void
    {
        $records = $this->loadJsonData('mst_soal_opsi.json');
        
        if (empty($records)) {
            $this->command->info('No soal-opsi data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $so) {
            MstSoalOpsi::firstOrCreate(
                ['soal_id' => $so['soal_id'], 'opsi' => $so['opsi']],
                [
                    'soal_id' => $so['soal_id'],
                    'opsi' => $so['opsi'],
                    'is_benar' => $so['is_benar'] ?? false,
                ]
            );
        }

        $this->command->info('Soal-Opsi relationships seeded from JSON!');
    }

    private function seedSoal(): void
    {
        $records = $this->loadJsonData('mst_soal.json');
        
        if (empty($records)) {
            $this->command->info('No soal data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $s) {
            MstSoal::firstOrCreate(
                ['materi_id' => $s['materi_id'], 'pertanyaan' => $s['pertanyaan']],
                [
                    'materi_id' => $s['materi_id'],
                    'pertanyaan' => $s['pertanyaan'],
                    'jenis_soal' => $s['jenis_soal'] ?? null,
                ]
            );
        }

        $this->command->info('Soal seeded from JSON!');
    }

    private function seedTarifSpp(): void
    {
        $records = $this->loadJsonData('mst_tarif_spp.json');
        
        if (empty($records)) {
            $this->command->info('No tarif SPP data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $t) {
            MstTarifSpp::firstOrCreate(
                ['kelas_id' => $t['kelas_id'], 'tahun_ajaran' => $t['tahun_ajaran']],
                [
                    'kelas_id' => $t['kelas_id'],
                    'tahun_ajaran' => $t['tahun_ajaran'],
                    'tarif' => $t['tarif'],
                ]
            );
        }

        $this->command->info('Tarif SPP seeded from JSON!');
    }

    private function seedTugas(): void
    {
        $records = $this->loadJsonData('mst_tugas.json');
        
        if (empty($records)) {
            $this->command->info('No tugas data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $t) {
            MstTugas::firstOrCreate(
                ['materi_id' => $t['materi_id'], 'judul' => $t['judul']],
                [
                    'materi_id' => $t['materi_id'],
                    'judul' => $t['judul'],
                    'deskripsi' => $t['deskripsi'] ?? null,
                    'deadline' => $t['deadline'] ?? null,
                ]
            );
        }

        $this->command->info('Tugas seeded from JSON!');
    }

    private function seedWaliMurid(): void
    {
        $records = $this->loadJsonData('mst_wali_murid.json');
        
        if (empty($records)) {
            $this->command->info('No wali murid data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $w) {
            MstWaliMurid::firstOrCreate(
                ['nama' => $w['nama']],
                [
                    'nama' => $w['nama'],
                    'email' => $w['email'] ?? null,
                    'telepon' => $w['telepon'] ?? null,
                ]
            );
        }

        $this->command->info('Wali Murid seeded from JSON!');
    }

    private function seedWali(): void
    {
        $records = $this->loadJsonData('mst_wali.json');
        
        if (empty($records)) {
            $this->command->info('No wali data to seed (JSON file not found or empty)');
            return;
        }

        foreach ($records as $w) {
            MstWali::firstOrCreate(
                ['nama' => $w['nama']],
                [
                    'nama' => $w['nama'],
                    'email' => $w['email'] ?? null,
                    'telepon' => $w['telepon'] ?? null,
                ]
            );
        }

        $this->command->info('Wali seeded from JSON!');
    }

}
