<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengeluaranObat extends Model
{
    protected $table = 'pengeluaran_obat';

    protected $fillable = [
        'no_batch',
        'tanggal_pengeluaran',
        'tujuan_pengeluaran',
        'user_id',
        'pasien_id',
        'keterangan'
    ];
    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->no_batch)) {
                $model->no_batch = 'BATCH-' . strtoupper(str()->random(8));
            }
        });

        // saat dihapus (hard delete) -> kembalikan stok pada stok_obat & nama_obat, lalu hapus child
        static::deleting(function ($model) {
            // ambil semua detail yang berelasi
            $details = $model->detailPengeluaranObat()->get();

            foreach ($details as $detail) {
                // sesuaikan stok pada stok_obat (batch)
                if (!empty($detail->detail_penerimaan_obat_id)) {
                    $stokObat = StokObat::find($detail->detail_penerimaan_obat_id);
                    if ($stokObat) {
                        $stokObat->stok = ($stokObat->stok ?? 0) + (int) $detail->jumlah_keluar;
                        $stokObat->save();
                    }
                }

                // sesuaikan total stok pada nama_obat jika kolom stok ada
                if (!empty($detail->nama_obat_id)) {
                    $namaObat = NamaObat::find($detail->nama_obat_id);
                    if ($namaObat && array_key_exists('stok', $namaObat->getAttributes())) {
                        $namaObat->stok = ($namaObat->stok ?? 0) + (int) $detail->jumlah_keluar;
                        $namaObat->save();
                    }
                }
            }

            // hapus permanen semua child yang berkaitan
            $model->detailPengeluaranObat()->get()->each->delete();
        });
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Pasien()
    {
        return $this->belongsTo(Pasien::class, 'pasien_id');
    }

    public function detailPengeluaranObat()
    {
        return $this->hasMany(DetailPengeluaranObat::class, foreignKey: 'pengeluaran_obat_id');
    }

    // public function detailPenerimaanObat()
    // {
    //     return $this->hasMany(DetailPenerimaanObat::class, foreignKey: 'penerimaan_obat_id');
    // }
}
