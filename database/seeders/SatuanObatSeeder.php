<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SatuanObatSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('satuan_obat')->insert([
            [
                'kode_satuan' => 'SAT-001',
                'satuan_obat' => 'Tablet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_satuan' => 'SAT-002',
                'satuan_obat' => 'Botol',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_satuan' => 'SAT-003',
                'satuan_obat' => 'Pack',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_satuan' => 'SAT-004',
                'satuan_obat' => 'Box',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_satuan' => 'SAT-005',
                'satuan_obat' => 'Sachet',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
