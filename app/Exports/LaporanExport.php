<?php

namespace App\Exports;

use App\Models\DataBeras;
use App\Models\HasilPrediksi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporanExport implements FromView, ShouldAutoSize
{
    public function view(): View
    {
        $dataHistoris = DataBeras::orderBy('tahun', 'asc')->orderBy('bulan', 'asc')->get();

        $latestRun = HasilPrediksi::orderBy('created_at', 'desc')->first();
        $prediksi = collect();
        
        if ($latestRun) {
            $prediksi = HasilPrediksi::where('run_id', $latestRun->run_id)->orderBy('tahun_prediksi', 'asc')->get();
            
            // Logika Status Dinamis
            $prediksi->transform(function ($item) {
                $ketJuta = $item->nilai_prediksi / 1000;
                
                if ($ketJuta >= 0.50) {
                    $item->status_dinamis = 'AMAN';
                } elseif ($ketJuta < 0.50 && $ketJuta >= 0.20) {
                    $item->status_dinamis = 'HATI-HATI';
                } elseif ($ketJuta < 0.20 && $ketJuta >= -0.45) {
                    $item->status_dinamis = 'DARURAT';
                } else {
                    $item->status_dinamis = 'HATI-HATI';
                }
                return $item;
            });
        }

        return view('admin.laporan.cetak', compact('dataHistoris', 'prediksi'));
    }
}