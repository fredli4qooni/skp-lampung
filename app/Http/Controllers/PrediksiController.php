<?php

namespace App\Http\Controllers;

use App\Models\HasilPrediksi;
use App\Models\DataBeras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PrediksiController extends Controller
{
    public function index()
    {
        $latestRun = HasilPrediksi::orderBy('created_at', 'desc')->first();
        $hasilPrediksi = collect();
        $akurasi = null;

        if ($latestRun) {
            $hasilPrediksi = HasilPrediksi::where('run_id', $latestRun->run_id)
                                          ->orderBy('tahun_prediksi', 'asc')
                                          ->get();
            
            $akurasi = [
                'mape' => $latestRun->mape,
                'rmse' => $latestRun->rmse,
                'p' => $latestRun->parameter_p,
                'd' => $latestRun->parameter_d,
                'q' => $latestRun->parameter_q,
            ];
        }

        $dataHistoris = DataBeras::orderBy('tahun', 'asc')->get();

        return view('admin.prediksi.index', compact('hasilPrediksi', 'dataHistoris', 'akurasi'));
    }

    public function jalankan()
    {
        try {
            Artisan::call('arima:forecast');
            $output = Artisan::output();

            return redirect()->route('admin.prediksi.index')
                             ->with('success', 'Prediksi berhasil dijalankan! Hasil telah diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('admin.prediksi.index')
                             ->with('error', 'Terjadi kesalahan saat menjalankan prediksi: ' . $e->getMessage());
        }
    }
}