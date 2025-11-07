<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JenisObat extends Model
{
    protected $table = 'jenis_obat';

    protected $fillable = [
        'kode_jenis',
        'jenis_obat',
    ];

    use SoftDeletes;

    public $timestamps = true;

    public function namaObat()
    {
        return $this->hasMany(NamaObat::class, 'jenis_obat_id');
    }
}
