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
        $dataHistoris = DataBeras::orderBy('tahun', 'asc')->get();
        $latestRun = HasilPrediksi::orderBy('created_at', 'desc')->first();
        $hasilPrediksi = $latestRun ? HasilPrediksi::where('run_id', $latestRun->run_id)->orderBy('tahun_prediksi', 'asc')->get() : collect();

        $pdf = Pdf::loadView('admin.laporan.cetak', compact('dataHistoris', 'hasilPrediksi'))
                  ->setPaper('a4', 'portrait');
                  
        return $pdf->download('Laporan_Ketahanan_Pangan_Lampung_'.date('Ymd').'.pdf');
    }

    public function exportExcel()
    {
        return Excel::download(new LaporanExport, 'Laporan_Ketahanan_Pangan_Lampung_'.date('Ymd').'.xlsx');
    }
}