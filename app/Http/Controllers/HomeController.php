<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataBeras;
use App\Models\HasilPrediksi;

class HomeController extends Controller
{
    public function index()
    {
        // 1. FASE HIBRIDA: Ambil data bulanan lalu agregasi menjadi total tahunan untuk Grafik
        $dataBulanan = DataBeras::orderBy('tahun', 'asc')->orderBy('bulan', 'asc')->get();
        $dataHistoris = $dataBulanan->groupBy('tahun')->map(function ($items, $tahun) {
            return (object) [
                'tahun' => $tahun,
                'ketersediaan_ton' => $items->sum('ketersediaan_ton')
            ];
        })->values();

        // 2. Ambil data historis terakhir untuk Info di UI
        $dataTerbaru = DataBeras::orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->first();

        // 3. Ambil data prediksi
        $latestRun = HasilPrediksi::orderBy('created_at', 'desc')->first();
        $hasilPrediksi = collect();
        $prediksiTahunDepan = null;
        
        // Sesuaikan penamaan variabel dengan welcome.blade.php
        $statusKondisi = 'Belum Ada Data';
        $warnaBadge = 'bg-gray-100 text-gray-800 border-gray-200';

        if ($latestRun) {
            $hasilPrediksi = HasilPrediksi::where('run_id', $latestRun->run_id)
                                          ->orderBy('tahun_prediksi', 'asc')
                                          ->get();
            $prediksiTahunDepan = $hasilPrediksi->first();

            if ($prediksiTahunDepan) {
                // Penentuan Teks Status dan Warna Badge sesuai controller lama
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