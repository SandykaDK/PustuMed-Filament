<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierObat extends Model
{
    protected $table = 'supplier_obat';
    protected $fillable = [
        'kode_supplier',
        'nama_supplier',
        'alamat_supplier',
        'telepon_supplier',
    ];
    public $timestamps = true;

        public function penerimaanObat()
    {
        return $this->hasMany(PenerimaanObat::class, 'supplier_id');
    }
}
