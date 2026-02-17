<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Master\MstBkJenis;
use App\Models\Master\MstBkKategori;
use App\Models\Master\MstKelas;
use App\Models\Master\MstMapel;
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

        $this->command->info('Master data seeded successfully from JSON files!');
    }

    /**
     * Load data from JSON file
     */
    private function loadJsonData(string $filename): array
    {
        $jsonPath = database_path('seeders/' . $filename);
        
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

}
