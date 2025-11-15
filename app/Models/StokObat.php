<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokObat extends Model
{
    protected $table = 'stok_obat';

    protected $fillable = [
        'nama_obat_id',
        'tanggal_kadaluwarsa',
        'stok',
        'no_batch',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_kadaluwarsa' => 'date',
    ];

    public function namaObat()
    {
        return $this->belongsTo(NamaObat::class);
    }
}
