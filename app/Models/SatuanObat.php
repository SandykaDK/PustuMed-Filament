<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SatuanObat extends Model
{
    protected $table = 'satuan_obat';
    protected $fillable = [
        'kode_satuan',
        'nama_satuan',
    ];

    use SoftDeletes;
    public $timestamps = true;

        public function penerimaanObat()
    {
        return $this->hasMany(PenerimaanObat::class, 'satuan_id');
    }
}
