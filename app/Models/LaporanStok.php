<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanStok extends Model
{
    protected $table = 'laporan_stok';

    protected $fillable = [
        'nama_obat_id',
        'stok_awal',
        'jumlah_masuk',
        'jumlah_keluar',
        'stok_akhir',
        'lokasi_penyimpanan',
        'tanggal_kadaluwarsa_terdekat',
        'min_stock',
        'max_stock',
        'status_stok'
    ];

    public function detailPenerimaanObat()
    {
        return $this->hasMany(DetailPenerimaanObat::class);
    }

    public function namaObat()
    {
        return $this->belongsTo(NamaObat::class, 'nama_obat_id');
    }
}
