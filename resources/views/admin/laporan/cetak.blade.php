<!DOCTYPE html>
<html>
<head>
    <title>Laporan Ketahanan Pangan SKP Lampung</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #333; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; text-align: center; margin-bottom: 5px; color: #1E3A5F; }
        .subtitle { font-size: 12px; text-align: center; margin-bottom: 20px; color: #666; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #94a3b8; padding: 6px 8px; }
        th { background-color: #f1f5f9; color: #1e293b; text-transform: uppercase; font-size: 11px; }
        .bg-blue { background-color: #e0f2fe; }
    </style>
</head>
<body>

    <div class="title">Laporan Sistem Ketahanan Pangan (SKP) Provinsi Lampung</div>
    <div class="subtitle">Dicetak pada: {{ date('d/m/Y H:i') }} WIB | Sumber: Dasbor Ketahanan Pangan</div>

    <h3 class="mb-2">1. Riwayat Data Beras Aktual (Skala: Ribu Ton)</h3>
    <table>
        <thead>
            <tr>
                <th class="text-center">Tahun</th>
                <th class="text-center">Bulan</th>
                <th class="text-right">Produksi (Ribu Ton)</th>
                <th class="text-right">Impor (Ribu Ton)</th>
                <th class="text-right">Konsumsi (Ribu Ton)</th>
                <th class="text-right bg-blue">Ketersediaan Bersih (Ribu Ton)</th>
            </tr>
        </thead>
        <tbody>
            @php
                $namaBulan = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
            @endphp
            @forelse($dataHistoris as $data)
            <tr>
                <td class="text-center">{{ $data->tahun }}</td>
                <td class="text-center">{{ $namaBulan[$data->bulan] ?? $data->bulan }}</td>
                <td class="text-right">{{ number_format($data->produksi_ton, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($data->impor_ton, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($data->konsumsi_ton, 2, ',', '.') }}</td>
                <td class="text-right font-bold bg-blue">{{ number_format($data->ketersediaan_ton, 2, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center" style="padding: 20px;">Belum ada riwayat data beras.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <h3 class="mb-2">2. Proyeksi Ketersediaan Beras (Skala: Juta Ton)</h3>
    <table>
        <thead>
            <tr>
                <th class="text-center">Tahun Proyeksi</th>
                <th class="text-right">Estimasi Ketersediaan (Juta Ton)</th>
                <th class="text-center">Status Kondisi Proyeksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prediksi as $p)
            <tr>
                <td class="text-center font-bold">{{ $p->tahun_prediksi }}</td>
                <td class="text-right font-bold">{{ number_format($p->nilai_prediksi / 1000, 2, ',', '.') }}</td>
                <td class="text-center font-bold">
                    {{ $p->status_dinamis ?? 'AMAN' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center" style="padding: 20px;">Data proyeksi mesin AI belum tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="font-size: 10px; color: #555; margin-top: -15px;">
        *Catatan Indikator Proyeksi:<br>
        - <b>AMAN</b>: &ge; 0,50 Juta Ton<br>
        - <b>HATI-HATI</b>: &lt; 0,45 Juta Ton <br>
        - <b>DARURAT</b>: &lt; 0,20 Juta Ton
    </div>

</body>
</html>