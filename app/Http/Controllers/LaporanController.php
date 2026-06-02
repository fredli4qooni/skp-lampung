<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataBeras;
use App\Models\HasilPrediksi;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.index');
    }

    public function exportPdf()
    {
        $dataHistoris = DataBeras::orderBy('tahun', 'asc')->orderBy('bulan', 'asc')->get();

        $latestRun = HasilPrediksi::orderBy('created_at', 'desc')->first();
        $prediksi = collect();
        
        if ($latestRun) {
            $prediksi = HasilPrediksi::where('run_id', $latestRun->run_id)->orderBy('tahun_prediksi', 'asc')->get();
            
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

        $pdf = Pdf::loadView('admin.laporan.cetak', compact('dataHistoris', 'prediksi'));
        return $pdf->download('Laporan_Ketahanan_Pangan_Lampung.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new LaporanExport, 'Laporan_Ketahanan_Pangan_Lampung.xlsx');
    }
}