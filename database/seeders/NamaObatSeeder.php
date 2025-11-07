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
                'stok_minimum' => 10,
                'stok_maksimum' => 20,
                'lokasi_penyimpanan' => 'Rak A1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_obat' => 'MED-002',
                'nama_obat' => 'Demacolin',
                'jenis_obat_id' => '2',
                'satuan_obat_id' => '2',
                'stok_minimum' => 5,
                'stok_maksimum' => 10,
                'lokasi_penyimpanan' => 'Rak A1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_obat' => 'MED-003',
                'nama_obat' => 'Panadol',
                'jenis_obat_id' => '3',
                'satuan_obat_id' => '1',
                'stok_minimum' => 7,
                'stok_maksimum' => 14,
                'lokasi_penyimpanan' => 'Rak A1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
