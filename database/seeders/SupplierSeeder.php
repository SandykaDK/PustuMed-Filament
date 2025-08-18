<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('supplier_obat')->insert([
            [
                'kode_supplier' => 'SUP-001',
                'nama_supplier' => 'PT. Marga Nusantara Jaya',
                'alamat_supplier' => 'Brebek',
                'telepon_supplier' => '081234567890',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_supplier' => 'SUP-002',
                'nama_supplier' => 'PT.Konimex Farma',
                'alamat_supplier' => 'Brebek Industri',
                'telepon_supplier' => '082345678901',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_supplier' => 'SUP-003',
                'nama_supplier' => 'PT.Kimia Farma',
                'alamat_supplier' => 'Rungkut Industri',
                'telepon_supplier' => '082345678901',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
