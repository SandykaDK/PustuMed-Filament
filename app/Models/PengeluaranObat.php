<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PengeluaranObat extends Model
{
    use SoftDeletes;
    protected $table = 'pengeluaran_obat';
    protected $fillable = [
        'no_batch',
        'tanggal_pengeluaran',
        'tujuan_pengeluaran',
        'user_id',
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
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detailPengeluaranObat()
    {
        return $this->hasMany(DetailPengeluaranObat::class, foreignKey: 'pengeluaran_obat_id');
    }

    public function jenisPengeluaran()
    {
        return $this->hasMany(JenisPengeluaran::class, 'jenis_pengeluaran_id');
    }

    // public function detailPenerimaanObat()
    // {
    //     return $this->hasMany(DetailPenerimaanObat::class, foreignKey: 'penerimaan_obat_id');
    // }
}
