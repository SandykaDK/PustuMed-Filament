<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenerimaanObat extends Model
{
    protected $table = 'detail_penerimaan_obat';

    protected $fillable = [
        'supplier_id',
        'nama_obat_id',
        'jenis_obat_id',
        'no_batch',
        'tanggal_kadaluwarsa',
        'jumlah_masuk',
        'satuan_id',
        'lokasi_penyimpanan',
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         if (empty($model->no_batch)) {
    //             $model->no_batch = 'BATCH-' . strtoupper(str()->random(8));
    //         }
    //     });
    // }
    public $timestamps = true;

    public function supplier()
    {
        return $this->belongsTo(SupplierObat::class, 'supplier_id');
    }

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

    public function penerimaanObat()
    {
        return $this->belongsTo(PenerimaanObat::class, 'penerimaan_obat_id');
    }

    public function pengeluaranObat()
    {
        return $this->belongsTo(PengeluaranObat::class, 'pengeluaran_obat_id');
    }
}
