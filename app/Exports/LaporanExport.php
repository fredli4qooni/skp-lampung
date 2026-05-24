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
        $dataBulanan = DataBeras::orderBy('tahun', 'asc')->orderBy('bulan', 'asc')->get();
        $dataHistoris = $dataBulanan->groupBy('tahun')->map(function ($items, $tahun) {
            return (object) [
                'tahun' => $tahun,
                'produksi_ton' => $items->sum('produksi_ton'),
                'stok_awal_ton' => $items->sum('stok_awal_ton'),
                'konsumsi_ton' => $items->sum('konsumsi_ton'),
                'ketersediaan_ton' => $items->sum('ketersediaan_ton')
            ];
        })->values();

        $latestRun = HasilPrediksi::orderBy('created_at', 'desc')->first();
        $hasilPrediksi = $latestRun ? HasilPrediksi::where('run_id', $latestRun->run_id)->orderBy('tahun_prediksi', 'asc')->get() : collect();

        return view('admin.laporan.cetak', compact('dataHistoris', 'hasilPrediksi'));
    }
}