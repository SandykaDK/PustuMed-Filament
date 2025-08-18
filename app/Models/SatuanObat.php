<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SatuanObat extends Model
{
    protected $table = 'satuan_obat';
    protected $fillable = [
        'kode_satuan',
        'nama_satuan',
    ];
    public $timestamps = true;

        public function penerimaanObat()
    {
        return $this->hasMany(PenerimaanObat::class, 'satuan_id');
    }
}
