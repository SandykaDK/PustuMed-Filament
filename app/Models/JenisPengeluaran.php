<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPengeluaran extends Model
{
    protected $table = 'jenis_pengeluaran';

    protected $fillable = [
        'kode_pengeluaran',
        'jenis_pengeluaran',
        'keterangan'
    ];

    public $timestamp = true;

    public function pengeluaranObat()
    {
        return $this->hasMany(PengeluaranObat::class, 'jenis_pengeluaran_id');
    }
}
