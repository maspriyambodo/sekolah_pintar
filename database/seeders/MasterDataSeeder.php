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
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
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
        $this->seedWaliMurid();
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
                    'mst_guru_mapel_id' => $u['mst_guru_mapel_id'] ?? null,
                    'mst_kelas_id' => $u['mst_kelas_id'] ?? null,
                    'judul' => $u['judul'] ?? null,
                    'deskripsi' => $u['deskripsi'] ?? null,
                    'tanggal_mulai' => $u['tanggal_mulai'] ?? null,
                    'tanggal_selesai' => $u['tanggal_selesai'] ?? null,
                    'durasi' => $u['durasi'] ?? null,
                    'status' => $u['status'] ?? 1,
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
                    'sys_user_id' => $uu['sys_user_id'] ?? null,
                    'tanggal_mulai' => $uu['tanggal_mulai'] ?? null,
                    'tanggal_selesai' => $uu['tanggal_selesai'] ?? null,
                    'status' => $uu['status'] ?? 1,
                    'nilai' => $uu['nilai'] ?? null,
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
                    'sys_user_id' => $n['sys_user_id'] ?? null,
                    'mst_mapel_id' => $n['mst_mapel_id'] ?? null,
                    'mst_kelas_id' => $n['mst_kelas_id'] ?? null,
                    'tipe' => $n['tipe'] ?? 1,
                    'nilai' => $n['nilai'] ?? null,
                    'semester' => $n['semester'] ?? null,
                    'tahun_ajaran' => $n['tahun_ajaran'] ?? null,
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
                    'sys_user_id' => $r['sys_user_id'] ?? null,
                    'mst_kelas_id' => $r['mst_kelas_id'] ?? null,
                    'semester' => $r['semester'] ?? null,
                    'tahun_ajaran' => $r['tahun_ajaran'] ?? null,
                    'status' => $r['status'] ?? 1,
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
                    'deskripsi' => $rd['deskripsi'] ?? null,
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
                ['id' => $rk['id'] ?? null],
                [
                    'sys_user_id' => $rk['sys_user_id'] ?? null,
                    'mst_kelas_id' => $rk['mst_kelas_id'] ?? null,
                    'semester' => $rk['semester'] ?? null,
                    'tahun_ajaran' => $rk['tahun_ajaran'] ?? null,
                    'ranking' => $rk['ranking'] ?? null,
                    'total_nilai' => $rk['total_nilai'] ?? null,
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
                    'sys_user_id' => $ag['sys_user_id'] ?? null,
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
                    'sys_user_id' => $as['sys_user_id'] ?? null,
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
                    'sys_user_id' => $ps['sys_user_id'] ?? null,
                    'mst_tarif_spp_id' => $ps['mst_tarif_spp_id'] ?? null,
                    'tanggal_bayar' => $ps['tanggal_bayar'] ?? null,
                    'jumlah_bayar' => $ps['jumlah_bayar'] ?? null,
                    'bulan' => $ps['bulan'] ?? null,
                    'tahun' => $ps['tahun'] ?? null,
                    'status' => $ps['status'] ?? 1,
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
                    'sys_user_id' => $pb['sys_user_id'] ?? null,
                    'mst_buku_id' => $pb['mst_buku_id'] ?? null,
                    'tanggal_pinjam' => $pb['tanggal_pinjam'] ?? null,
                    'tanggal_kembali' => $pb['tanggal_kembali'] ?? null,
                    'tanggal_dikembalikan' => $pb['tanggal_dikembalikan'] ?? null,
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
                    'sys_user_id' => $bk['sys_user_id'] ?? null,
                    'mst_bk_jenis_id' => $bk['mst_bk_jenis_id'] ?? null,
                    'mst_bk_kategori_id' => $bk['mst_bk_kategori_id'] ?? null,
                    'judul' => $bk['judul'] ?? null,
                    'deskripsi' => $bk['deskripsi'] ?? null,
                    'tanggal' => $bk['tanggal'] ?? null,
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
                    'tanggal' => $bh['tanggal'] ?? null,
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
                ['id' => $bs['id'] ?? null],
                [
                    'trx_bk_kasus_id' => $bs['trx_bk_kasus_id'] ?? null,
                    'sys_user_id' => $bs['sys_user_id'] ?? null,
                    'tanggal_sesi' => $bs['tanggal_sesi'] ?? null,
                    'deskripsi' => $bs['deskripsi'] ?? null,
                    'status' => $bs['status'] ?? 1,
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
                ['id' => $bt['id'] ?? null],
                [
                    'trx_bk_kasus_id' => $bt['trx_bk_kasus_id'] ?? null,
                    'tindakan' => $bt['tindakan'] ?? null,
                    'tanggal' => $bt['tanggal'] ?? null,
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
                    'nama_file' => $bl['nama_file'] ?? null,
                    'path' => $bl['path'] ?? null,
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
                    'sys_user_id' => $bw['sys_user_id'] ?? null,
                    'notifikasi' => $bw['notifikasi'] ?? null,
                    'tanggal' => $bw['tanggal'] ?? null,
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
                ['id' => $p['id'] ?? null],
                [
                    'sys_user_id' => $p['sys_user_id'] ?? null,
                    'tanggal' => $p['tanggal'] ?? null,
                    'jam_masuk' => $p['jam_masuk'] ?? null,
                    'jam_keluar' => $p['jam_keluar'] ?? null,
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
                ['id' => $f['id'] ?? null],
                [
                    'sys_user_id' => $f['sys_user_id'] ?? null,
                    'mst_mapel_id' => $f['mst_mapel_id'] ?? null,
                    'mst_kelas_id' => $f['mst_kelas_id'] ?? null,
                    'judul' => $f['judul'] ?? null,
                    'konten' => $f['konten'] ?? null,
                    'status' => $f['status'] ?? 1,
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
                ['id' => $lam['id'] ?? null],
                [
                    'sys_user_id' => $lam['sys_user_id'] ?? null,
                    'mst_materi_id' => $lam['mst_materi_id'] ?? null,
                    'tanggal_akses' => $lam['tanggal_akses'] ?? null,
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
                ['id' => $ts['id'] ?? null],
                [
                    'mst_tugas_id' => $ts['mst_tugas_id'] ?? null,
                    'sys_user_id' => $ts['sys_user_id'] ?? null,
                    'file_jawaban' => $ts['file_jawaban'] ?? null,
                    'tanggal_kirim' => $ts['tanggal_kirim'] ?? null,
                    'nilai' => $ts['nilai'] ?? null,
                    'status' => $ts['status'] ?? 1,
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
                ['id' => $sk['id'] ?? null],
                [
                    'nama_kriteria' => $sk['nama_kriteria'] ?? null,
                    'bobot' => $sk['bobot'] ?? null,
                    'jenis' => $sk['jenis'] ?? null,
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
                ['id' => $sp['id'] ?? null],
                [
                    'sys_user_id' => $sp['sys_user_id'] ?? null,
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
                ['id' => $sh['id'] ?? null],
                [
                    'sys_user_id' => $sh['sys_user_id'] ?? null,
                    'nilai_akhir' => $sh['nilai_akhir'] ?? null,
                    'ranking' => $sh['ranking'] ?? null,
                    'tahun_ajaran' => $sh['tahun_ajaran'] ?? null,
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
                    'activity' => $sal['activity'] ?? null,
                    'description' => $sal['description'] ?? null,
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
                    'sys_user_id' => $sel['sys_user_id'] ?? null,
                    'error_message' => $sel['error_message'] ?? null,
                    'error_trace' => $sel['error_trace'] ?? null,
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
                    'ip_address' => $sll['ip_address'] ?? null,
                    'user_agent' => $sll['user_agent'] ?? null,
                    'login_at' => $sll['login_at'] ?? null,
                ]
            );
        }

        $this->command->info('Sys Login Logs seeded from JSON!');
    }
}
