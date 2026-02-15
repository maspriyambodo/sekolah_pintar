<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\System\SysReference;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SysReferenceSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('sys_references.json');
        
        if (!File::exists($jsonPath)) {
            $this->command->error('sys_references.json file not found!');
            return;
        }

        $jsonData = File::get($jsonPath);
        $data = json_decode($jsonData, true);

        if (!isset($data['RECORDS']) || !is_array($data['RECORDS'])) {
            $this->command->error('Invalid JSON format in sys_references.json!');
            return;
        }

        $references = $data['RECORDS'];

        foreach ($references as $ref) {
            SysReference::firstOrCreate(
                ['kategori' => $ref['kategori'], 'kode' => $ref['kode']],
                [
                    'kategori' => $ref['kategori'],
                    'kode' => $ref['kode'],
                    'nama' => $ref['nama'],
                    'urutan' => $ref['urutan'],
                ]
            );
        }

        $this->command->info('System references seeded successfully from JSON!');
    }
}
