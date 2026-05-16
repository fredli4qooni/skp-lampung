<?php

namespace App\Http\Controllers;

use App\Models\DataBeras;
use App\Models\HasilPrediksi;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalTahun = DataBeras::count();
        $rataProduksi = DataBeras::avg('produksi_ton') ?? 0;
        $rataKonsumsi = DataBeras::avg('konsumsi_ton') ?? 0;
        $latestRun = HasilPrediksi::orderBy('created_at', 'desc')->first();
        $infoModel = null;
        $statusMendatang = 'Belum Diprediksi';

        if ($latestRun) {
            $prediksiTerdekat = HasilPrediksi::where('run_id', $latestRun->run_id)
                                              ->orderBy('tahun_prediksi', 'asc')
                                              ->first();

            $infoModel = [
                'mape' => $latestRun->mape,
                'rmse' => $latestRun->rmse,
                'p' => $latestRun->parameter_p,
                'd' => $latestRun->parameter_d,
                'q' => $latestRun->parameter_q,
            ];

            if ($prediksiTerdekat) {
                $statusMendatang = strtoupper($prediksiTerdekat->status_kondisi);
            }
        }

        $recentData = DataBeras::orderBy('tahun', 'desc')->take(5)->get();

        return view('dashboard', compact('totalTahun', 'rataProduksi', 'rataKonsumsi', 'infoModel', 'statusMendatang', 'recentData'));
    }
}