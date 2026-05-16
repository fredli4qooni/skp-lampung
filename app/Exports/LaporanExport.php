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
        $dataHistoris = DataBeras::orderBy('tahun', 'asc')->get();
        $latestRun = HasilPrediksi::orderBy('created_at', 'desc')->first();
        $hasilPrediksi = $latestRun ? HasilPrediksi::where('run_id', $latestRun->run_id)->orderBy('tahun_prediksi', 'asc')->get() : collect();

        return view('admin.laporan.cetak', compact('dataHistoris', 'hasilPrediksi'));
    }
}