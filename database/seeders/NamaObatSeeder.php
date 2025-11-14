<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NamaObatSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('nama_obat')->insert([
            [
                'kode_obat' => 'MED-001',
                'nama_obat' => 'Amoxicillin',
                'jenis_obat_id' => '1',
                'satuan_obat_id' => '1',
                'lokasi_penyimpanan' => 'Rak A1',
                'stok' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_obat' => 'MED-002',
                'nama_obat' => 'Demacolin',
                'jenis_obat_id' => '2',
                'satuan_obat_id' => '2',
                'lokasi_penyimpanan' => 'Rak A2',
                'stok' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_obat' => 'MED-003',
                'nama_obat' => 'Panadol',
                'jenis_obat_id' => '3',
                'satuan_obat_id' => '3',
                'lokasi_penyimpanan' => 'Rak A3',
                'stok' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
