<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPengeluaranObat extends Model
{
    protected $table = 'detail_pengeluaran_obat';

    protected $fillable = [
        'nama_obat_id',
        'jumlah_keluar',
        'satuan_id',
        'no_batch',
        'lokasi_penyimpanan',
        'detail_penerimaan_obat_id',
    ];
    public $timestamps = true;

    public function namaObat()
    {
        return $this->belongsTo(NamaObat::class, 'nama_obat_id');
    }

    public function jenisObat()
    {
        return $this->belongsTo(JenisObat::class, 'jenis_obat_id');
    }

    public function satuan()
    {
        return $this->belongsTo(SatuanObat::class, 'satuan_id');
    }

    public function pengeluaranObat()
    {
        return $this->belongsTo(PengeluaranObat::class, foreignKey: 'pengeluaran_obat_id');
    }
    public function detailPenerimaanObat()
    {
        return $this->belongsTo(DetailPenerimaanObat::class, 'detail_penerimaan_obat_id');
    }
}
