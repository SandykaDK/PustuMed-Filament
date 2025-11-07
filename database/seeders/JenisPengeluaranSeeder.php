<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisPengeluaranSeeder extends Seeder
{

    public function run(): void
    {
        DB::table('jenis_pengeluaran')->insert([
            [
               'kode_pengeluaran' => 'OUT-001',
               'jenis_pengeluaran' => 'Pengobatan Pasien',
               'keterangan' => 'Pengobatan untuk pasien',
            ],
            [
               'kode_pengeluaran' => 'OUT-002',
               'jenis_pengeluaran' => 'Kadaluwarsa',
               'keterangan' => 'Obat kadaluwarsa',
            ],
        ]);
    }
}
