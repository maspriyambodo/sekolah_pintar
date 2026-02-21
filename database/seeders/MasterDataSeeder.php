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
use App\Models\Master\MstWali;
use App\Models\Master\MstEkstrakurikuler;
use App\Models\Master\MstOrganisasi;
use App\Models\Master\MstOrganisasiJabatan;
use App\Models\Transaction\TrxEkstrakurikulerSiswa;
use App\Models\Transaction\TrxOrganisasiAnggota;
use App\Models\Transaction\TrxAbsensiGuru;
use App\Models\Transaction\TrxAbsensiSiswa;
use App\Models\Transaction\TrxBkHasil;
use App\Models\Transaction\TrxBkKasus;
use App\Models\Transaction\TrxBkLampiran;
use App\Models\Transaction\TrxBkSesi;
use App\Models\Transaction\TrxBkTindakan;
use App\Models\Transaction\TrxBkWali;
use App\Models\Transaction\TrxForum;
use App\Models\Transaction\TrxLogAksesMateri;
use App\Models\Transaction\TrxNilai;
use App\Models\Transaction\TrxPembayaranSpp;
use App\Models\Transaction\TrxPeminjamanBuku;
use App\Models\Transaction\TrxPresensi;
use App\Models\Transaction\TrxRanking;
use App\Models\Transaction\TrxRapor;
use App\Models\Transaction\TrxRaporDetail;
use App\Models\Transaction\TrxTugasSiswa;
use App\Models\Transaction\TrxUjian;
use App\Models\Transaction\TrxUjianJawaban;
use App\Models\Transaction\TrxUjianUser;
use App\Models\Spk\SpkHasil;
use App\Models\Spk\SpkKriteria;
use App\Models\Spk\SpkPenilaian;
use App\Models\System\SysActivityLog;
use App\Models\System\SysErrorLog;
use App\Models\System\SysLoginLog;
use App\Models\Ppdb\PpdbGelombang;
use App\Models\Ppdb\PpdbPendaftaran;
use App\Models\Ppdb\PpdbDokumen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Master Data
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
        $this->seedWali();

        // Transaction Data
        $this->seedTrxUjian();
        $this->seedTrxUjianUser();
        $this->seedTrxUjianJawaban();
        $this->seedTrxNilai();
        $this->seedTrxRapor();
        $this->seedTrxRaporDetail();
        $this->seedTrxRanking();
        $this->seedTrxAbsensiGuru();
        $this->seedTrxAbsensiSiswa();
        $this->seedTrxPembayaranSpp();
        $this->seedTrxPeminjamanBuku();
        $this->seedTrxBkKasus();
        $this->seedTrxBkHasil();
        $this->seedTrxBkSesi();
        $this->seedTrxBkTindakan();
        $this->seedTrxBkLampiran();
        $this->seedTrxBkWali();
        $this->seedTrxPresensi();
        $this->seedTrxForum();
        $this->seedTrxLogAksesMateri();
        $this->seedTrxTugasSiswa();

        // SPK Data
        $this->seedSpkKriteria();
        $this->seedSpkPenilaian();
        $this->seedSpkHasil();

        // System Logs
        $this->seedSysActivityLogs();
        $this->seedSysErrorLogs();
        $this->seedSysLoginLogs();

        // PPDB Data
        $this->seedPpdbGelombang();
        $this->seedPpdbPendaftaran();
        $this->seedPpdbDokumen();

        // use App\Models\Master\MstOrganisasi;
        // use App\Models\Master\MstOrganisasiJabatan;
        // use App\Models\Transaction\TrxEkstrakurikulerSiswa;
        // use App\Models\Transaction\TrxOrganisasiAnggota;

        // Ekstrakurikuler
        $this->seedEkstrakurikuler();
        $this->seedEkstrakurikulerSiswa();

        // Organisasi
        $this->seedMstOrganisasi();
        $this->seedMstOrganisasiJabatan();
        $this->seedTrxOrganisasiAnggota();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
            return;
        }

        foreach ($records as $sw) {
            MstSiswaWali::firstOrCreate(
                ['mst_siswa_id' => $sw['mst_siswa_id'], 'mst_wali_id' => $sw['mst_wali_id']],
                [
                    'mst_siswa_id' => $sw['mst_siswa_id'],
                    'mst_wali_id' => $sw['mst_wali_id'],
                    'hubungan' => $sw['hubungan'] ?? null,
                ]
            );
        }

        $this->command->info('Siswa-Wali relationships seeded from JSON!');
    }

    private function seedSiswa(): void
    {
        $records = $this->loadJsonData('mst_siswa.json');
        
        if (empty($records)) {
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
            return;
        }

        foreach ($records as $so) {
            MstSoalOpsi::firstOrCreate(
                ['mst_soal_id' => $so['mst_soal_id'], 'teks_opsi' => $so['teks_opsi']],
                [
                    'mst_soal_id' => $so['mst_soal_id'],
                    'teks_opsi' => $so['teks_opsi'],
                    'is_jawaban' => $so['is_jawaban'] ?? false,
                ]
            );
        }

        $this->command->info('Soal-Opsi relationships seeded from JSON!');
    }

    private function seedSoal(): void
    {
        $records = $this->loadJsonData('mst_soal.json');
        
        if (empty($records)) {
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
            return;
        }

        foreach ($records as $t) {
            MstTarifSpp::firstOrCreate(
                ['mst_kelas_id' => $t['mst_kelas_id'], 'tahun_ajaran' => $t['tahun_ajaran']],
                [
                    'mst_kelas_id' => $t['mst_kelas_id'],
                    'tahun_ajaran' => $t['tahun_ajaran'],
                    'nominal' => $t['nominal'],
                    'keterangan' => $t['keterangan'] ?? null,
                ]
            );
        }

        $this->command->info('Tarif SPP seeded from JSON!');
    }

    private function seedTugas(): void
    {
        $records = $this->loadJsonData('mst_tugas.json');
        
        if (empty($records)) {
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

    private function seedWali(): void
    {
        $records = $this->loadJsonData('mst_wali.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $w) {
            MstWali::firstOrCreate(
                ['sys_user_id' => $w['sys_user_id']],
                [
                    'sys_user_id' => $w['sys_user_id'],
                    'nama' => $w['nama'],
                    'no_hp' => $w['no_hp'] ?? null,
                    'alamat' => $w['alamat'] ?? null,
                ]
            );
        }

        $this->command->info('Wali seeded from JSON!');
    }

    // Transaction Seeding Methods
    private function seedTrxUjian(): void
    {
        $records = $this->loadJsonData('trx_ujian.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $u) {
            TrxUjian::firstOrCreate(
                ['id' => $u['id'] ?? null],
                [
                    'mst_mapel_id' => $u['mst_mapel_id'] ?? null,
                    'mst_kelas_id' => $u['mst_kelas_id'] ?? null,
                    'jenis' => $u['jenis'],
                    'semester' => $u['semester'],
                    'tanggal' => $u['tanggal'],
                ]
            );
        }

        $this->command->info('Ujian seeded from JSON!');
    }

    private function seedTrxUjianUser(): void
    {
        $records = $this->loadJsonData('trx_ujian_user.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $uu) {
            TrxUjianUser::firstOrCreate(
                ['id' => $uu['id'] ?? null],
                [
                    'trx_ujian_id' => $uu['trx_ujian_id'] ?? null,
                    'mst_siswa_id' => $uu['mst_siswa_id'] ?? null,
                    'waktu_mulai' => $uu['waktu_mulai'] ?? null,
                    'waktu_selesai' => $uu['waktu_selesai'] ?? null,
                    'status' => $uu['status'] ?? 1,
                    'sisa_waktu' => $uu['sisa_waktu'] ?? null,
                    'total_benar' => $uu['total_benar'] ?? 0,
                    'total_salah' => $uu['total_salah'] ?? 0,
                    'nilai_akhir' => $uu['nilai_akhir'] ?? 0.00,
                ]
            );
        }

        $this->command->info('Ujian User seeded from JSON!');
    }

    private function seedTrxUjianJawaban(): void
    {
        $records = $this->loadJsonData('trx_ujian_jawaban.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $uj) {
            TrxUjianJawaban::firstOrCreate(
                ['id' => $uj['id'] ?? null],
                [
                    'trx_ujian_user_id' => $uj['trx_ujian_user_id'] ?? null,
                    'mst_soal_id' => $uj['mst_soal_id'] ?? null,
                    'mst_soal_opsi_id' => $uj['mst_soal_opsi_id'] ?? null,
                    'jawaban_teks' => $uj['jawaban_teks'] ?? null,
                    'is_benar' => $uj['is_benar'] ?? false,
                ]
            );
        }

        $this->command->info('Ujian Jawaban seeded from JSON!');
    }

    private function seedTrxNilai(): void
    {
        $records = $this->loadJsonData('trx_nilai.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $n) {
            TrxNilai::firstOrCreate(
                ['id' => $n['id'] ?? null],
                [
                    'mst_siswa_id' => $n['mst_siswa_id'] ?? null,
                    'trx_ujian_id' => $n['trx_ujian_id'] ?? null,
                    'nilai' => $n['nilai'] ?? null,
                ]
            );
        }

        $this->command->info('Nilai seeded from JSON!');
    }

    private function seedTrxRapor(): void
    {
        $records = $this->loadJsonData('trx_rapor.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $r) {
            TrxRapor::firstOrCreate(
                ['id' => $r['id'] ?? null],
                [
                    'mst_siswa_id' => $r['mst_siswa_id'] ?? null,
                    'semester' => $r['semester'] ?? null,
                    'tahun_ajaran' => $r['tahun_ajaran'] ?? null,
                    'total_nilai' => $r['total_nilai'] ?? 0.00,
                    'rata_rata' => $r['rata_rata'] ?? null,
                ]
            );
        }

        $this->command->info('Rapor seeded from JSON!');
    }

    private function seedTrxRaporDetail(): void
    {
        $records = $this->loadJsonData('trx_rapor_detail.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $rd) {
            TrxRaporDetail::firstOrCreate(
                ['id' => $rd['id'] ?? null],
                [
                    'trx_rapor_id' => $rd['trx_rapor_id'] ?? null,
                    'mst_mapel_id' => $rd['mst_mapel_id'] ?? null,
                    'nilai_akhir' => $rd['nilai_akhir'] ?? null,
                ]
            );
        }

        $this->command->info('Rapor Detail seeded from JSON!');
    }

    private function seedTrxRanking(): void
    {
        $records = $this->loadJsonData('trx_ranking.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $rk) {
            TrxRanking::firstOrCreate(
                ['trx_rapor_id' => $rk['trx_rapor_id'] ?? null],
                [
                    'trx_rapor_id' => $rk['trx_rapor_id'] ?? null,
                    'mst_kelas_id' => $rk['mst_kelas_id'] ?? null,
                    'peringkat' => $rk['peringkat'] ?? null,
                ]
            );
        }

        $this->command->info('Ranking seeded from JSON!');
    }

    private function seedTrxAbsensiGuru(): void
    {
        $records = $this->loadJsonData('trx_absensi_guru.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $ag) {
            TrxAbsensiGuru::firstOrCreate(
                ['id' => $ag['id'] ?? null],
                [
                    'mst_guru_id' => $ag['mst_guru_id'] ?? null,
                    'tanggal' => $ag['tanggal'] ?? null,
                    'status' => $ag['status'] ?? null,
                    'keterangan' => $ag['keterangan'] ?? null,
                ]
            );
        }

        $this->command->info('Absensi Guru seeded from JSON!');
    }

    private function seedTrxAbsensiSiswa(): void
    {
        $records = $this->loadJsonData('trx_absensi_siswa.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $as) {
            TrxAbsensiSiswa::firstOrCreate(
                ['id' => $as['id'] ?? null],
                [
                    'mst_siswa_id' => $as['mst_siswa_id'] ?? null,
                    'tanggal' => $as['tanggal'] ?? null,
                    'status' => $as['status'] ?? null,
                    'keterangan' => $as['keterangan'] ?? null,
                ]
            );
        }

        $this->command->info('Absensi Siswa seeded from JSON!');
    }

    private function seedTrxPembayaranSpp(): void
    {
        $records = $this->loadJsonData('trx_pembayaran_spp.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $ps) {
            TrxPembayaranSpp::firstOrCreate(
                ['id' => $ps['id'] ?? null],
                [
                    'mst_siswa_id' => $ps['mst_siswa_id'] ?? null,
                    'mst_tarif_spp_id' => $ps['mst_tarif_spp_id'] ?? null,
                    'tanggal_bayar' => $ps['tanggal_bayar'] ?? null,
                    'jumlah_bayar' => $ps['jumlah_bayar'] ?? null,
                    'bulan' => $ps['bulan'] ?? null,
                    'tahun' => $ps['tahun'] ?? null,
                    'status' => $ps['status'] ?? 1,
                    'metode_pembayaran' => $ps['metode_pembayaran'] ?? null,
                    'keterangan' => $ps['keterangan'] ?? null,
                    'petugas_id' => $ps['petugas_id'] ?? null,
                ]
            );
        }

        $this->command->info('Pembayaran SPP seeded from JSON!');
    }

    private function seedTrxPeminjamanBuku(): void
    {
        $records = $this->loadJsonData('trx_peminjaman_buku.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $pb) {
            TrxPeminjamanBuku::firstOrCreate(
                ['id' => $pb['id'] ?? null],
                [
                    'mst_siswa_id' => $pb['mst_siswa_id'] ?? null,
                    'mst_buku_id' => $pb['mst_buku_id'] ?? null,
                    'tanggal_pinjam' => $pb['tanggal_pinjam'] ?? null,
                    'tanggal_kembali' => $pb['tanggal_kembali'] ?? null,
                    'status' => $pb['status'] ?? 1,
                ]
            );
        }

        $this->command->info('Peminjaman Buku seeded from JSON!');
    }

    private function seedTrxBkKasus(): void
    {
        $records = $this->loadJsonData('trx_bk_kasus.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $bk) {
            TrxBkKasus::firstOrCreate(
                ['id' => $bk['id'] ?? null],
                [
                    'mst_siswa_id' => $bk['mst_siswa_id'] ?? null,
                    'mst_bk_jenis_id' => $bk['mst_bk_jenis_id'] ?? null,
                    'mst_bk_kategori_id' => $bk['mst_bk_kategori_id'] ?? null,
                    'judul_kasus' => $bk['judul_kasus'] ?? null,
                    'deskripsi_masalah' => $bk['deskripsi_masalah'] ?? null,
                    'tanggal_mulai' => $bk['tanggal_mulai'] ?? null,
                    'tanggal_selesai' => $bk['tanggal_selesai'] ?? null,
                    'status' => $bk['status'] ?? 1,
                ]
            );
        }

        $this->command->info('BK Kasus seeded from JSON!');
    }

    private function seedTrxBkHasil(): void
    {
        $records = $this->loadJsonData('trx_bk_hasil.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $bh) {
            TrxBkHasil::firstOrCreate(
                ['id' => $bh['id'] ?? null],
                [
                    'trx_bk_kasus_id' => $bh['trx_bk_kasus_id'] ?? null,
                    'hasil' => $bh['hasil'] ?? null,
                    'rekomendasi' => $bh['rekomendasi'] ?? null,
                ]
            );
        }

        $this->command->info('BK Hasil seeded from JSON!');
    }

    private function seedTrxBkSesi(): void
    {
        $records = $this->loadJsonData('trx_bk_sesi.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $bs) {
            TrxBkSesi::firstOrCreate(
                ['trx_bk_kasus_id' => $bs['trx_bk_kasus_id'] ?? null],
                [
                    'trx_bk_kasus_id' => $bs['trx_bk_kasus_id'] ?? null,
                    'tanggal' => $bs['tanggal'] ?? null,
                    'metode' => $bs['metode'] ?? null,
                    'catatan' => $bs['catatan'] ?? null,
                ]
            );
        }

        $this->command->info('BK Sesi seeded from JSON!');
    }

    private function seedTrxBkTindakan(): void
    {
        $records = $this->loadJsonData('trx_bk_tindakan.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $bt) {
            TrxBkTindakan::firstOrCreate(
                ['trx_bk_kasus_id' => $bt['trx_bk_kasus_id'] ?? null],
                [
                    'trx_bk_kasus_id' => $bt['trx_bk_kasus_id'] ?? null,
                    'deskripsi_tindakan' => $bt['deskripsi_tindakan'] ?? null,
                ]
            );
        }

        $this->command->info('BK Tindakan seeded from JSON!');
    }

    private function seedTrxBkLampiran(): void
    {
        $records = $this->loadJsonData('trx_bk_lampiran.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $bl) {
            TrxBkLampiran::firstOrCreate(
                ['id' => $bl['id'] ?? null],
                [
                    'trx_bk_kasus_id' => $bl['trx_bk_kasus_id'] ?? null,
                    'file_path' => $bl['file_path'] ?? null,
                    'keterangan' => $bl['keterangan'] ?? null,
                ]
            );
        }

        $this->command->info('BK Lampiran seeded from JSON!');
    }

    private function seedTrxBkWali(): void
    {
        $records = $this->loadJsonData('trx_bk_wali.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $bw) {
            TrxBkWali::firstOrCreate(
                ['id' => $bw['id'] ?? null],
                [
                    'trx_bk_kasus_id' => $bw['trx_bk_kasus_id'] ?? null,
                    'mst_wali_id' => $bw['mst_wali_id'] ?? null,
                    'peran' => $bw['peran'] ?? null,
                ]
            );
        }

        $this->command->info('BK Wali seeded from JSON!');
    }

    private function seedTrxPresensi(): void
    {
        $records = $this->loadJsonData('trx_presensi.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $p) {
            TrxPresensi::firstOrCreate(
                ['mst_guru_mapel_id' => $p['mst_guru_mapel_id'] ?? null],
                [
                    'mst_guru_mapel_id' => $p['mst_guru_mapel_id'] ?? null,
                    'mst_siswa_id' => $p['mst_siswa_id'] ?? null,
                    'tanggal' => $p['tanggal'] ?? null,
                    'jam_masuk' => $p['jam_masuk'] ?? null,
                    'keterangan' => $p['keterangan'] ?? null,
                    'status' => $p['status'] ?? null,
                ]
            );
        }

        $this->command->info('Presensi seeded from JSON!');
    }

    private function seedTrxForum(): void
    {
        $records = $this->loadJsonData('trx_forum.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $f) {
            TrxForum::firstOrCreate(
                ['mst_guru_mapel_id' => $f['mst_guru_mapel_id'] ?? null],
                [
                    'mst_guru_mapel_id' => $f['mst_guru_mapel_id'] ?? null,
                    'sys_user_id' => $f['sys_user_id'] ?? null,
                    'parent_id' => $f['parent_id'] ?? null,
                    'judul' => $f['judul'] ?? null,
                    'pesan' => $f['pesan'] ?? null,
                    'file_lampiran' => $f['file_lampiran'] ?? null,
                ]
            );
        }

        $this->command->info('Forum seeded from JSON!');
    }

    private function seedTrxLogAksesMateri(): void
    {
        $records = $this->loadJsonData('trx_log_akses_materi.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $lam) {
            TrxLogAksesMateri::firstOrCreate(
                ['mst_materi_id' => $lam['mst_materi_id'] ?? null],
                [
                    'mst_materi_id' => $lam['mst_materi_id'] ?? null,
                    'mst_siswa_id' => $lam['mst_siswa_id'] ?? null,
                    'waktu_akses' => $lam['waktu_akses'] ?? null,
                    'durasi_detik' => $lam['durasi_detik'] ?? null,
                    'perangkat' => $lam['perangkat'] ?? null,
                ]
            );
        }

        $this->command->info('Log Akses Materi seeded from JSON!');
    }

    private function seedTrxTugasSiswa(): void
    {
        $records = $this->loadJsonData('trx_tugas_siswa.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $ts) {
            TrxTugasSiswa::firstOrCreate(
                ['mst_tugas_id' => $ts['mst_tugas_id'] ?? null, 'mst_siswa_id' => $ts['mst_siswa_id'] ?? null],
                [
                    'mst_tugas_id' => $ts['mst_tugas_id'] ?? null,
                    'mst_siswa_id' => $ts['mst_siswa_id'] ?? null,
                    'jawaban_teks' => $ts['jawaban_teks'] ?? null,
                    'file_siswa' => $ts['file_siswa'] ?? null,
                    'waktu_kumpul' => $ts['waktu_kumpul'] ?? null,
                    'nilai' => $ts['nilai'] ?? null,
                    'catatan_guru' => $ts['catatan_guru'] ?? null,
                    'status_kumpul' => $ts['status_kumpul'] ?? null,
                ]
            );
        }

        $this->command->info('Tugas Siswa seeded from JSON!');
    }

    // SPK Seeding Methods
    private function seedSpkKriteria(): void
    {
        $records = $this->loadJsonData('spk_kriteria.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $sk) {
            SpkKriteria::firstOrCreate(
                ['kode_kriteria' => $sk['kode_kriteria'] ?? null],
                [
                    'kode_kriteria' => $sk['kode_kriteria'] ?? null,
                    'nama_kriteria' => $sk['nama_kriteria'] ?? null,
                    'bobot' => $sk['bobot'] ?? null,
                    'tipe' => $sk['tipe'] ?? null,
                ]
            );
        }

        $this->command->info('SPK Kriteria seeded from JSON!');
    }

    private function seedSpkPenilaian(): void
    {
        $records = $this->loadJsonData('spk_penilaian.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $sp) {
            SpkPenilaian::firstOrCreate(
                ['mst_siswa_id' => $sp['mst_siswa_id'] ?? null, 'spk_kriteria_id' => $sp['spk_kriteria_id'] ?? null],
                [
                    'mst_siswa_id' => $sp['mst_siswa_id'] ?? null,
                    'spk_kriteria_id' => $sp['spk_kriteria_id'] ?? null,
                    'nilai' => $sp['nilai'] ?? null,
                    'tahun_ajaran' => $sp['tahun_ajaran'] ?? null,
                ]
            );
        }

        $this->command->info('SPK Penilaian seeded from JSON!');
    }

    private function seedSpkHasil(): void
    {
        $records = $this->loadJsonData('spk_hasil.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $sh) {
            SpkHasil::firstOrCreate(
                ['mst_siswa_id' => $sh['mst_siswa_id'] ?? null],
                [
                    'mst_siswa_id' => $sh['mst_siswa_id'] ?? null,
                    'total_skor' => $sh['total_skor'] ?? null,
                    'peringkat' => $sh['peringkat'] ?? null,
                    'periode' => $sh['periode'] ?? null,
                ]
            );
        }

        $this->command->info('SPK Hasil seeded from JSON!');
    }

    // System Logs Seeding Methods
    private function seedSysActivityLogs(): void
    {
        $records = $this->loadJsonData('sys_activity_logs.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $sal) {
            SysActivityLog::firstOrCreate(
                ['id' => $sal['id'] ?? null],
                [
                    'sys_user_id' => $sal['sys_user_id'] ?? null,
                    'action' => $sal['action'] ?? null,
                    'module' => $sal['module'] ?? null,
                    'reference_table' => $sal['reference_table'] ?? null,
                    'reference_id' => $sal['reference_id'] ?? null,
                    'ip_address' => $sal['ip_address'] ?? null,
                    'user_agent' => $sal['user_agent'] ?? null,
                ]
            );
        }

        $this->command->info('Sys Activity Logs seeded from JSON!');
    }

    private function seedSysErrorLogs(): void
    {
        $records = $this->loadJsonData('sys_error_logs.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $sel) {
            SysErrorLog::firstOrCreate(
                ['id' => $sel['id'] ?? null],
                [
                    'level' => $sel['level'] ?? null,
                    'message' => $sel['message'] ?? null,
                    'file' => $sel['file'] ?? null,
                    'line' => $sel['line'] ?? null,
                    'trace' => $sel['trace'] ?? null,
                ]
            );
        }

        $this->command->info('Sys Error Logs seeded from JSON!');
    }

    private function seedSysLoginLogs(): void
    {
        $records = $this->loadJsonData('sys_login_logs.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $sll) {
            SysLoginLog::firstOrCreate(
                ['id' => $sll['id'] ?? null],
                [
                    'sys_user_id' => $sll['sys_user_id'] ?? null,
                    'email' => $sll['email'] ?? null,
                    'status' => $sll['status'] ?? null,
                    'ip_address' => $sll['ip_address'] ?? null,
                    'user_agent' => $sll['user_agent'] ?? null,
                    'login_at' => $sll['login_at'] ?? null,
                ]
            );
        }

        $this->command->info('Sys Login Logs seeded from JSON!');
    }

    private function seedPpdbGelombang(): void
    {
        $records = $this->loadJsonData('ppdb_gelombang.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $pg) {
            PpdbGelombang::firstOrCreate(
                ['id' => $pg['id'] ?? null],
                [
                    'mst_sekolah_id' => $pg['mst_sekolah_id'] ?? null,
                    'nama_gelombang' => $pg['nama_gelombang'] ?? null,
                    'tahun_ajaran' => $pg['tahun_ajaran'] ?? null,
                    'tgl_mulai' => $pg['tgl_mulai'] ?? null,
                    'tgl_selesai' => $pg['tgl_selesai'] ?? null,
                    'biaya_pendaftaran' => $pg['biaya_pendaftaran'] ?? null,
                    'is_active' => $pg['is_active'] ?? 1,
                ]
            );
        }

        $this->command->info('PPDB Gelombang seeded from JSON!');
    }

    private function seedPpdbPendaftaran(): void
    {
        $records = $this->loadJsonData('ppdb_pendaftar.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $pp) {
            PpdbPendaftaran::firstOrCreate(
                ['id' => $pp['id'] ?? null],
                [
                    'mst_sekolah_id' => $pp['mst_sekolah_id'] ?? null,
                    'ppdb_gelombang_id' => $pp['ppdb_gelombang_id'] ?? null,
                    'no_pendaftaran' => $pp['no_pendaftaran'] ?? null,
                    'nama_lengkap' => $pp['nama_lengkap'] ?? null,
                    'email' => $pp['email'] ?? null,
                    'password' => $pp['password'] ?? null,
                    'nisn' => $pp['nisn'] ?? null,
                    'jenis_kelamin' => $pp['jenis_kelamin'] ?? null,
                    'telp_hp' => $pp['telp_hp'] ?? null,
                    'asal_sekolah' => $pp['asal_sekolah'] ?? null,
                    'status_pendaftaran' => $pp['status_pendaftaran'] ?? null,
                    'pilihan_jurusan_id' => $pp['pilihan_jurusan_id'] ?? null,
                ]
            );
        }

        $this->command->info('PPDB Pendaftaran seeded from JSON!');
    }

    private function seedPpdbDokumen(): void
    {
        $records = $this->loadJsonData('ppdb_dokumen.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $pd) {
            PpdbDokumen::firstOrCreate(
                ['id' => $pd['id'] ?? null],
                [
                    'ppdb_pendaftar_id' => (int)$pd['ppdb_pendaftar_id'] ?? null,
                    'jenis_dokumen' => $pd['jenis_dokumen'] ?? null,
                    'file_name' => $pd['file_name'] ?? null,
                    'mime_type' => $pd['mime_type'] ?? null,
                    'file_path' => $pd['file_path'] ?? null,
                    'file_size' => $pd['file_size'] ?? null,
                    'verifikasi_status' => $pd['verifikasi_status'] ?? null,
                    'catatan_admin' => $pd['catatan_admin'] ?? null,
                ]
            );
        }

        $this->command->info('PPDB Dokumen seeded from JSON!');
    }

    private function seedEkstrakurikuler() : void {
        $records = $this->loadJsonData('mst_ekstrakurikuler.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $eksul) {
            MstEkstrakurikuler::firstOrCreate(
                ['id' => $eksul['id']],
                [
                    'kode' => $eksul['kode'] ?? null,
                    'nama' => $eksul['nama'] ?? null,
                    'deskripsi' => $eksul['deskripsi'] ?? null,
                    'pembina_guru_id' => (int)$eksul['pembina_guru_id'] ?? null,
                    'hari' => $eksul['hari'] ?? null,
                    'jam_mulai' => $eksul['jam_mulai'] ?? null,
                    'jam_selesai' => $eksul['jam_selesai'] ?? null,
                    'lokasi' => $eksul['lokasi'] ?? null,
                    'status' => $eksul['status'] ?? null
                ]
            );
        }
    }

    private function seedEkstrakurikulerSiswa() : void {
        $records = $this->loadJsonData('trx_ekstrakurikuler_siswa.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $eksul) {
            TrxEkstrakurikulerSiswa::firstOrCreate(
                ['id' => $eksul['id']],
                [
                    'ekstrakurikuler_id' => $eksul['ekstrakurikuler_id'] ?? null,
                    'siswa_id' => (int)$eksul['siswa_id'] ?? null,
                    'tanggal_daftar' => $eksul['tanggal_daftar'] ?? null,
                    'status' => $eksul['status'] ?? null,
                ]
            );
        }
    }

    private function seedMstOrganisasi() : void {
        $records = $this->loadJsonData('mst_organisasi.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $org) {
            MstOrganisasi::firstOrCreate(
                ['id' => $org['id']],
                [
                    'kode' => $org['kode'] ?? null,
                    'nama' => $org['nama'] ?? null,
                    'deskripsi' => $org['deskripsi'] ?? null,
                    'pembina_guru_id' => $org['pembina_guru_id'] ?? null,
                    'periode_mulai' => $org['periode_mulai'] ?? null,
                    'periode_selesai' => $org['periode_selesai'] ?? null,
                    'status' => $org['status'] ?? null,
                ]
            );
        }
    }

    private function seedMstOrganisasiJabatan() : void {
        $records = $this->loadJsonData('mst_organisasi_jabatan.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $org) {
            MstOrganisasiJabatan::firstOrCreate(
                ['id' => $org['id']],
                [
                    'nama' => $org['nama'] ?? null,
                    'deskripsi' => $org['deskripsi'] ?? null,
                    'urutan' => $org['urutan'] ?? null,
                ]
            );
        }
    }

    private function seedTrxOrganisasiAnggota() : void {
        $records = $this->loadJsonData('trx_organisasi_anggota.json');
        
        if (empty($records)) {
            return;
        }

        foreach ($records as $org) {
            TrxOrganisasiAnggota::firstOrCreate(
                ['id' => $org['id']],
                [
                    'organisasi_id' => $org['organisasi_id'] ?? null,
                    'siswa_id' => $org['siswa_id'] ?? null,
                    'jabatan_id' => $org['jabatan_id'] ?? null,
                    'tanggal_mulai' => $org['tanggal_mulai'] ?? null,
                    'tanggal_selesai' => $org['tanggal_selesai'] ?? null,
                    'status' => $org['status'] ?? null,
                ]
            );
        }
    }

}
