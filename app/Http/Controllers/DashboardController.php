<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataBeras;
use App\Models\HasilPrediksi;

class DashboardController extends Controller
{
    public function index()
    {
        $dataHistorisBulanan = DataBeras::orderBy('tahun', 'asc')->orderBy('bulan', 'asc')->get();

        $dataHistorisTahunan = $dataHistorisBulanan->groupBy('tahun')->map(function ($items, $tahun) {
            return (object) [
                'tahun' => $tahun,
                'produksi_ton' => $items->sum('produksi_ton'),
                'ketersediaan_ton' => $items->sum('ketersediaan_ton')
            ];
        })->values();

        $dataTerbaru = $dataHistorisTahunan->last();

        $latestRun = HasilPrediksi::orderBy('created_at', 'desc')->first();
        $hasilPrediksi = collect();
        $prediksiTahunDepan = null;

        $statusKondisi = 'Belum Ada Data';
        $warnaBadge = 'bg-gray-100 text-gray-800 border-gray-200';

        $konsumsiTahunan = 885;
        $konsumsiBulanan = 73.75;

        if ($latestRun) {
            $hasilPrediksi = HasilPrediksi::where('run_id', $latestRun->run_id)
                ->orderBy('tahun_prediksi', 'asc')
                ->get();
            $prediksiTahunDepan = $hasilPrediksi->first();

            if ($prediksiTahunDepan) {
                $statusNorm = strtolower($prediksiTahunDepan->status_kondisi ?? '');
                if ($statusNorm === 'aman') {
                    $statusKondisi = 'Aman';
                    $warnaBadge = 'bg-green-100 text-green-800 border-green-300';
                } elseif ($statusNorm === 'waspada') {
                    $statusKondisi = 'Waspada';
                    $warnaBadge = 'bg-yellow-100 text-yellow-800 border-yellow-300';
                } else {
                    $statusKondisi = 'Darurat';
                    $warnaBadge = 'bg-red-100 text-red-800 border-red-300';
                }
            }
        }

        return view('welcome', compact(
            'dataHistorisBulanan',
            'dataHistorisTahunan',
            'dataTerbaru',
            'hasilPrediksi',
            'prediksiTahunDepan',
            'statusKondisi',
            'warnaBadge',
            'konsumsiTahunan',
            'konsumsiBulanan'
        ));
    }
}
