<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            JenisObatSeeder::class,
            SatuanObatSeeder::class,
            NamaObatSeeder::class,
            JenisPengeluaranSeeder::class,
        ]);
    }
}
