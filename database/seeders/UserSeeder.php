<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Sandyka',
                'email' => 'Sandyka@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'kepala_pustu',
                'no_telepon' => '089620106214',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Mysthios',
                'email' => 'Mysthios@gmail.com',
                'password' => Hash::make('123'),
                'role' => 'petugas_pustu',
                'no_telepon' => '089620176453',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
