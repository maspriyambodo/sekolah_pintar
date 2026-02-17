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
                ['isbn' => $b['isbn']],
                [
                    'isbn' => $b['isbn'],
                    'judul' => $b['judul'],
                    'penulis' => $b['penulis'] ?? null,
                    'penerbit' => $b['penerbit'] ?? null,
                    'tahun' => $b['tahun'] ?? null,
                    'stok' => $b['stok'] ?? 0,
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
                ['mst_guru_id' => $gm['mst_guru_id'], 'mst_mapel_id' => $gm['mst_mapel_id']],
                [
                    'mst_guru_id' => $gm['mst_guru_id'],
                    'mst_mapel_id' => $gm['mst_mapel_id'],
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
                ['sys_user_id' => $g['sys_user_id']],
                [
                    'sys_user_id' => $g['sys_user_id'],
                    'nip' => $g['nip'] ?? null,
                    'nuptk' => $g['nuptk'] ?? null,
                    'nama' => $g['nama'],
                    'jenis_kelamin' => $g['jenis_kelamin'] ?? null,
                    'tanggal_lahir' => $g['tanggal_lahir'] ?? null,
                    'alamat' => $g['alamat'] ?? null,
                    'email' => $g['email'] ?? null,
                    'pendidikan_terakhir' => $g['pendidikan_terakhir'] ?? null,
                    'no_hp' => $g['no_hp'] ?? null,
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
                ['judul' => $m['judul'], 'mst_guru_mapel_id' => $m['mst_guru_mapel_id']],
                [
                    'judul' => $m['judul'],
                    'mst_guru_mapel_id' => $m['mst_guru_mapel_id'],
                    'deskripsi' => $m['deskripsi'] ?? null,
                    'file_materi' => $m['file_materi'] ?? null,
                    'link_video' => $m['link_video'] ?? null,
                    'status' => $m['status'] ?? 1,
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
                ['sys_user_id' => $s['sys_user_id']],
                [
                    'sys_user_id' => $s['sys_user_id'],
                    'nis' => $s['nis'] ?? null,
                    'nama' => $s['nama'],
                    'jenis_kelamin' => $s['jenis_kelamin'] ?? null,
                    'tanggal_lahir' => $s['tanggal_lahir'] ?? null,
                    'alamat' => $s['alamat'] ?? null,
                    'mst_kelas_id' => $s['mst_kelas_id'] ?? null,
                    'status' => $s['status'] ?? 1,
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
                ['soal_id' => $so['mst_soal_id'], 'opsi' => $so['teks_opsi']],
                [
                    'soal_id' => $so['mst_soal_id'],
                    'opsi' => $so['teks_opsi'],
                    'is_benar' => $so['is_jawaban'] ?? false,
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
                ['mst_mapel_id' => $s['mst_mapel_id'], 'pertanyaan' => $s['pertanyaan']],
                [
                    'mst_mapel_id' => $s['mst_mapel_id'],
                    'pertanyaan' => $s['pertanyaan'],
                    'tipe' => $s['tipe'] ?? 1,
                    'tingkat_kesulitan' => $s['tingkat_kesulitan'] ?? 1,
                    'media_path' => $s['media_path'] ?? null,
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
                ['kelas_id' => $t['mst_kelas_id'], 'tahun_ajaran' => $t['tahun_ajaran']],
                [
                    'kelas_id' => $t['mst_kelas_id'],
                    'tahun_ajaran' => $t['tahun_ajaran'],
                    'tarif' => $t['nominal'],
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
                ['mst_guru_mapel_id' => $t['mst_guru_mapel_id'], 'mst_kelas_id' => $t['mst_kelas_id'], 'judul' => $t['judul']],
                [
                    'mst_guru_mapel_id' => $t['mst_guru_mapel_id'],
                    'mst_kelas_id' => $t['mst_kelas_id'],
                    'judul' => $t['judul'],
                    'deskripsi' => $t['deskripsi'] ?? null,
                    'file_lampiran' => $t['file_lampiran'] ?? null,
                    'tenggat_waktu' => $t['tenggat_waktu'] ?? null,
                    'status' => $t['status'] ?? 1,
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
                ['sys_user_id' => $w['sys_user_id']],
                [
                    'sys_user_id' => $w['sys_user_id'],
                    'nama' => $w['nama'],
                    'email' => $w['email'] ?? null,
                    'no_hp' => $w['no_hp'] ?? null,
                    'alamat' => $w['alamat'] ?? null,
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
                ['sys_user_id' => $w['sys_user_id']],
                [
                    'sys_user_id' => $w['sys_user_id'],
                    'nama' => $w['nama'],
                    'email' => $w['email'] ?? null,
                    'no_hp' => $w['no_hp'] ?? null,
                    'alamat' => $w['alamat'] ?? null,
                ]
            );
        }

        $this->command->info('Wali seeded from JSON!');
    }

}
