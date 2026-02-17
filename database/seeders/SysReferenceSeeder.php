<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\System\SysReference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SysReferenceSeeder extends Seeder
{
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

    public function run(): void
    {
        $records = $this->loadJsonData('sys_references.json');
        
        if (empty($records)) {
            $this->command->info('No sys_references data to seed');
            return;
        }

        foreach ($records as $ref) {
            SysReference::firstOrCreate(
                ['kategori' => $ref['kategori'], 'kode' => $ref['kode']],
                [
                    'kategori' => $ref['kategori'],
                    'kode' => $ref['kode'],
                    'nama' => $ref['nama'],
                    'urutan' => $ref['urutan'] ?? 0,
                ]
            );
        }

        $this->command->info('System references seeded successfully from JSON!');
    }
}
