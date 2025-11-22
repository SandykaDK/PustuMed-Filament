<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pasien extends Model
{
    protected $table = 'pasien';
    protected $fillable = [
        'nama',
        'nik',
        'alamat',
        'no_telepon',
        'no_bpjs'
    ];

    use SoftDeletes;
    public $timestamps = true;

    public function pengeluaranObat()
    {
    return $this->hasMany(PengeluaranObat::class, DetailPengeluaranObat::class);
    }
}
