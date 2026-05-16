<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataBeras;
use App\Models\HasilPrediksi;

class DashboardController extends Controller
{
    public function index()
    {
        $dataHistoris = DataBeras::orderBy('tahun', 'asc')->get();
        $dataTerbaru = $dataHistoris->last();

        $latestRun = HasilPrediksi::orderBy('created_at', 'desc')->first();
        $hasilPrediksi = collect();
        $prediksiTahunDepan = null;
        
        $statusKondisi = 'Belum Ada Data';
        $warnaBadge = 'bg-gray-100 text-gray-800 border-gray-200';

        if ($latestRun) {
            $hasilPrediksi = HasilPrediksi::where('run_id', $latestRun->run_id)
                                          ->orderBy('tahun_prediksi', 'asc')
                                          ->get();
            $prediksiTahunDepan = $hasilPrediksi->first();

            if ($prediksiTahunDepan) {
                if ($prediksiTahunDepan->status_kondisi === 'aman') {
                    $statusKondisi = 'Aman';
                    $warnaBadge = 'bg-green-100 text-green-800 border-green-300';
                } elseif ($prediksiTahunDepan->status_kondisi === 'waspada') {
                    $statusKondisi = 'Waspada';
                    $warnaBadge = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                } else {
                    $statusKondisi = 'Darurat';
                    $warnaBadge = 'bg-red-100 text-red-800 border-red-300';
                }
            }
        }

        return view('welcome', compact(
            'dataHistoris', 
            'dataTerbaru', 
            'hasilPrediksi', 
            'prediksiTahunDepan', 
            'statusKondisi', 
            'warnaBadge'
        ));
    }
}