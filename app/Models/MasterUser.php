<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterUser extends Model
{
    protected $table = 'master_user';
    protected $fillable = [
        'kode_user',
        'nama_user',
        'jabatan',
        'telepon'
    ];
    public $timestamps = true;

    public function penerimaanObat()
    {
        return $this->hasMany(PenerimaanObat::class, 'master_user_id');
    }
}
