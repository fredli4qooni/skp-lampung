<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DataBerasSeeder extends Seeder
{
    public function run(): void
    {
        $dataClient = [
            // 2018
            ['tahun' => 2018, 'bulan' => 1, 'produksi' => 65.00],
            ['tahun' => 2018, 'bulan' => 2, 'produksi' => 85.00],
            ['tahun' => 2018, 'bulan' => 3, 'produksi' => 185.00],
            ['tahun' => 2018, 'bulan' => 4, 'produksi' => 317.00],
            ['tahun' => 2018, 'bulan' => 5, 'produksi' => 149.00],
            ['tahun' => 2018, 'bulan' => 6, 'produksi' => 73.00],
            ['tahun' => 2018, 'bulan' => 7, 'produksi' => 78.00],
            ['tahun' => 2018, 'bulan' => 8, 'produksi' => 89.00],
            ['tahun' => 2018, 'bulan' => 9, 'produksi' => 155.00],
            ['tahun' => 2018, 'bulan' => 10, 'produksi' => 131.00],
            ['tahun' => 2018, 'bulan' => 11, 'produksi' => 54.00],
            ['tahun' => 2018, 'bulan' => 12, 'produksi' => 33.00],
            // 2019
            ['tahun' => 2019, 'bulan' => 1, 'produksi' => 22.00],
            ['tahun' => 2019, 'bulan' => 2, 'produksi' => 73.00],
            ['tahun' => 2019, 'bulan' => 3, 'produksi' => 170.00],
            ['tahun' => 2019, 'bulan' => 4, 'produksi' => 318.00],
            ['tahun' => 2019, 'bulan' => 5, 'produksi' => 147.00],
            ['tahun' => 2019, 'bulan' => 6, 'produksi' => 63.00],
            ['tahun' => 2019, 'bulan' => 7, 'produksi' => 36.00],
            ['tahun' => 2019, 'bulan' => 8, 'produksi' => 83.00],
            ['tahun' => 2019, 'bulan' => 9, 'produksi' => 120.00],
            ['tahun' => 2019, 'bulan' => 10, 'produksi' => 126.00],
            ['tahun' => 2019, 'bulan' => 11, 'produksi' => 52.00],
            ['tahun' => 2019, 'bulan' => 12, 'produksi' => 25.00],
            // 2020
            ['tahun' => 2020, 'bulan' => 1, 'produksi' => 36.00],
            ['tahun' => 2020, 'bulan' => 2, 'produksi' => 39.00],
            ['tahun' => 2020, 'bulan' => 3, 'produksi' => 71.00],
            ['tahun' => 2020, 'bulan' => 4, 'produksi' => 336.00],
            ['tahun' => 2020, 'bulan' => 5, 'produksi' => 260.00],
            ['tahun' => 2020, 'bulan' => 6, 'produksi' => 116.00],
            ['tahun' => 2020, 'bulan' => 7, 'produksi' => 26.00],
            ['tahun' => 2020, 'bulan' => 8, 'produksi' => 67.00],
            ['tahun' => 2020, 'bulan' => 9, 'produksi' => 255.00],
            ['tahun' => 2020, 'bulan' => 10, 'produksi' => 205.00],
            ['tahun' => 2020, 'bulan' => 11, 'produksi' => 93.00],
            ['tahun' => 2020, 'bulan' => 12, 'produksi' => 19.00],
            // 2021
            ['tahun' => 2021, 'bulan' => 1, 'produksi' => 13.00],
            ['tahun' => 2021, 'bulan' => 2, 'produksi' => 38.00],
            ['tahun' => 2021, 'bulan' => 3, 'produksi' => 245.00],
            ['tahun' => 2021, 'bulan' => 4, 'produksi' => 446.00],
            ['tahun' => 2021, 'bulan' => 5, 'produksi' => 113.00],
            ['tahun' => 2021, 'bulan' => 6, 'produksi' => 32.00],
            ['tahun' => 2021, 'bulan' => 7, 'produksi' => 28.00],
            ['tahun' => 2021, 'bulan' => 8, 'produksi' => 84.00],
            ['tahun' => 2021, 'bulan' => 9, 'produksi' => 204.00],
            ['tahun' => 2021, 'bulan' => 10, 'produksi' => 153.00],
            ['tahun' => 2021, 'bulan' => 11, 'produksi' => 56.00],
            ['tahun' => 2021, 'bulan' => 12, 'produksi' => 15.00],
            // 2022
            ['tahun' => 2022, 'bulan' => 1, 'produksi' => 23.25],
            ['tahun' => 2022, 'bulan' => 2, 'produksi' => 65.37],
            ['tahun' => 2022, 'bulan' => 3, 'produksi' => 209.19],
            ['tahun' => 2022, 'bulan' => 4, 'produksi' => 376.35],
            ['tahun' => 2022, 'bulan' => 5, 'produksi' => 192.80],
            ['tahun' => 2022, 'bulan' => 6, 'produksi' => 51.73],
            ['tahun' => 2022, 'bulan' => 7, 'produksi' => 41.81],
            ['tahun' => 2022, 'bulan' => 8, 'produksi' => 76.14],
            ['tahun' => 2022, 'bulan' => 9, 'produksi' => 226.15],
            ['tahun' => 2022, 'bulan' => 10, 'produksi' => 175.04],
            ['tahun' => 2022, 'bulan' => 11, 'produksi' => 71.94],
            ['tahun' => 2022, 'bulan' => 12, 'produksi' => 35.51],
            // 2023 (Hanya sampai April)
            ['tahun' => 2023, 'bulan' => 1, 'produksi' => 23.46],
            ['tahun' => 2023, 'bulan' => 2, 'produksi' => 76.80],
            ['tahun' => 2023, 'bulan' => 3, 'produksi' => 265.26],
            ['tahun' => 2023, 'bulan' => 4, 'produksi' => 288.42],
            // 2024
            ['tahun' => 2024, 'bulan' => 1, 'produksi' => 11.02],
            ['tahun' => 2024, 'bulan' => 2, 'produksi' => 16.69],
            ['tahun' => 2024, 'bulan' => 3, 'produksi' => 107.53],
            ['tahun' => 2024, 'bulan' => 4, 'produksi' => 323.74],
            ['tahun' => 2024, 'bulan' => 5, 'produksi' => 368.18],
            ['tahun' => 2024, 'bulan' => 6, 'produksi' => 82.50],
            ['tahun' => 2024, 'bulan' => 7, 'produksi' => 25.89],
            ['tahun' => 2024, 'bulan' => 8, 'produksi' => 89.30],
            ['tahun' => 2024, 'bulan' => 9, 'produksi' => 254.93],
            ['tahun' => 2024, 'bulan' => 10, 'produksi' => 226.63],
            ['tahun' => 2024, 'bulan' => 11, 'produksi' => 80.53],
            ['tahun' => 2024, 'bulan' => 12, 'produksi' => 17.68],
        ];

        $now = Carbon::now();
        $insertData = [];

        foreach ($dataClient as $data) {
            $insertData[] = [
                'tahun' => $data['tahun'],
                'bulan' => $data['bulan'],
                'produksi_ton' => $data['produksi'],
                'stok_awal_ton' => 0,
                'impor_ton' => 0,
                'ekspor_ton' => 0,
                'konsumsi_ton' => 0,
                'ketersediaan_ton' => $data['produksi'],
                'sumber_data' => 'Data Dummy Klien',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('data_beras')->truncate();
        DB::table('data_beras')->insert($insertData);
    }
}