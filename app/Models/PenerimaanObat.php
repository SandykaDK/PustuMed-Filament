<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PenerimaanObat extends Model
{
    use SoftDeletes;
    protected $table = 'penerimaan_obat';
    protected $fillable = [
        'no_batch',
        'tanggal_penerimaan',
        'master_user_id',
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

    public function masterUser()
    {
        return $this->belongsTo(MasterUser::class, 'master_user_id');
    }

    public function detailPenerimaanObat()
    {
        return $this->hasMany(DetailPenerimaanObat::class, 'penerimaan_obat_id');
    }
}
