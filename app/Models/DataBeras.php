<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBeras extends Model
{
    use HasFactory;

    protected $table = 'data_beras';

    protected $fillable = [
        'tahun',
        'produksi_ton',
        'stok_awal_ton',
        'impor_ton',
        'ekspor_ton',
        'konsumsi_ton',
        'ketersediaan_ton',
        'sumber_data',
        'catatan'
    ];
}