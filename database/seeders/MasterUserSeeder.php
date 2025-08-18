<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('master_user')->insert([
            [
               'kode_user' => 'USER-001',
               'nama_user' => 'Sandyka',
               'jabatan' => 'Staff Gudang',
               'telepon' => '089620106214'
            ],
            [
               'kode_user' => 'USER-002',
               'nama_user' => 'Mysthios',
               'jabatan' => 'Dokter',
               'telepon' => '089620101231'
            ]
        ]);
    }
}
