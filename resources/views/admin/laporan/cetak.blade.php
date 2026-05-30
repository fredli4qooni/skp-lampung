<!DOCTYPE html>
<html>
<head>
    <title>Laporan Ketahanan Pangan</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 25px; }
        .title { font-size: 15px; font-weight: bold; color: #1E3A5F; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        th, td { border: 1px solid #444; padding: 7px; }
        th { background-color: #1E3A5F; color: #ffffff; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .status-aman { color: #16A34A; font-weight: bold; }
        .status-waspada { color: #D97706; font-weight: bold; }
        .status-darurat { color: #DC2626; font-weight: bold; }
        .surplus { color: #16A34A; font-weight: bold; }
        .defisit { color: #DC2626; font-weight: bold; }
        .note { font-style: italic; color: #555; font-size: 11px; margin-top: -15px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN KETAHANAN PANGAN BERAS PROVINSI LAMPUNG</div>
        <div style="margin-top: 5px;">Dicetak pada: {{ date('d-m-Y H:i') }}</div>
    </div>

    <h3>1. Data Historis Ketersediaan Teragregasi (Skala Juta Ton)</h3>
    <table>
        <thead>
            <tr>
                <th class="text-center">Tahun</th>
                <th class="text-right">Produksi (Juta Ton)</th>
                <th class="text-right">Stok Awal (Juta Ton)</th>
                <th class="text-right">Konsumsi (Juta Ton)</th>
                <th class="text-right">Ketersediaan Bersih (Juta Ton)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataHistoris as $data)
            <tr>
                <td class="text-center">{{ $data->tahun }}</td>
                <td class="text-right">{{ number_format($data->produksi_ton / 1000000, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($data->stok_awal_ton / 1000000, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($data->konsumsi_ton / 1000000, 2, ',', '.') }}</td>
                <td class="text-right font-bold" style="color: #1E3A5F;">{{ number_format($data->ketersediaan_ton / 1000000, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>2. Hasil Prediksi ARIMA & Proyeksi Neraca (Skala Juta Ton)</h3>
    @php
        $konsumsiTahunan = 885000; 
    @endphp
    
    <div class="note">
        * Asumsi Beban Konsumsi Tetap: <b>{{ number_format($konsumsiTahunan / 1000000, 2, ',', '.') }} Juta Ton/Tahun</b>
    </div>
    
    <table>
        <thead>
            <tr>
                <th class="text-center">Tahun Proyeksi</th>
                <th class="text-right">Total Ketersediaan (Juta Ton)</th>
                <th class="text-right">Batas Bawah (Juta Ton)</th>
                <th class="text-right">Batas Atas (Juta Ton)</th>
                <th class="text-right">Neraca / Selisih (Juta Ton)</th>
                <th class="text-center">Status Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hasilPrediksi as $pred)
                @php
                    $ketersediaan = $pred->nilai_prediksi;
                    $selisih = $ketersediaan - $konsumsiTahunan;
                @endphp
            <tr>
                <td class="text-center font-bold"><b>{{ $pred->tahun_prediksi }}</b></td>
                <td class="text-right"><b>{{ number_format($ketersediaan / 1000000, 2, ',', '.') }}</b></td>
                <td class="text-right">{{ number_format($pred->lower_bound / 1000000, 2, ',', '.') }}</td>
                <td class="text-right">{{ number_format($pred->upper_bound / 1000000, 2, ',', '.') }}</td>
                <td class="text-right">
                    @if($selisih >= 0)
                        <span class="surplus">Surplus (+) {{ number_format($selisih / 1000000, 2, ',', '.') }}</span>
                    @else
                        <span class="defisit">Defisit (-) {{ number_format(abs($selisih) / 1000000, 2, ',', '.') }}</span>
                    @endif
                </td>
                <td class="text-center"><span class="status-{{ strtolower($pred->status_kondisi) }}">{{ strtoupper($pred->status_kondisi) }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Belum ada data prediksi. Silakan jalankan enjin komputasi di dasbor Admin.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>