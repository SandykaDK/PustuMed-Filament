<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NamaObat extends Model
{
    protected $table = 'nama_obat';

    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'jenis_obat_id',
        'satuan_obat_id',
        'lokasi_penyimpanan',
    ];

    public $timestamps = true;

    public function penerimaanObat()
    {
        return $this->hasMany(PenerimaanObat::class, 'nama_obat_id');
    }

    public function jenisObat()
    {
        return $this->belongsTo(JenisObat::class, 'jenis_obat_id');
    }

    public function satuanObat()
    {
        return $this->belongsTo(SatuanObat::class, 'satuan_obat_id');
    }
}
