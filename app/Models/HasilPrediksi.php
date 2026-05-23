<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilPrediksi extends Model
{
    protected $table = 'hasil_prediksi';
    
    const UPDATED_AT = null;

    protected $fillable = [
        'run_id',
        'tahun_prediksi',
        'bulan_prediksi',
        'nilai_prediksi',
        'lower_bound',
        'upper_bound',
        'parameter_p',
        'parameter_d',
        'parameter_q',
        'mape',
        'rmse',
        'status_kondisi'
    ];
}