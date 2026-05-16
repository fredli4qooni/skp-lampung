<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\KonfigurasiSistem;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Ketahanan Pangan',
            'email' => 'admin@lampung.go.id',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $konfigurasi = [
            [
                'key' => 'threshold_aman',
                'value' => '100000',
                'keterangan' => 'Batas minimal ketersediaan beras (ton) untuk status Aman (Hijau)'
            ],
            [
                'key' => 'threshold_waspada',
                'value' => '50000',
                'keterangan' => 'Batas minimal ketersediaan beras (ton) untuk status Waspada (Kuning)'
            ],
            [
                'key' => 'arima_default_p',
                'value' => '1',
                'keterangan' => 'Parameter AutoRegressive (p) default'
            ],
            [
                'key' => 'arima_default_d',
                'value' => '1',
                'keterangan' => 'Parameter Differencing (d) default'
            ],
            [
                'key' => 'arima_default_q',
                'value' => '1',
                'keterangan' => 'Parameter Moving Average (q) default'
            ],
        ];

        foreach ($konfigurasi as $config) {
            KonfigurasiSistem::create($config);
        }
    }
}