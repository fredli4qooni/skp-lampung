<!DOCTYPE html>
<html>
<head>
    <title>Laporan Ketahanan Pangan</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .title { font-size: 16px; font-weight: bold; color: #1E3A5F; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #1E3A5F; color: #ffffff; font-weight: bold; }
        .status-aman { color: green; font-weight: bold; }
        .status-waspada { color: orange; font-weight: bold; }
        .status-darurat { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">LAPORAN KETAHANAN PANGAN BERAS PROVINSI LAMPUNG</div>
        <div style="margin-top: 5px;">Dicetak pada: {{ date('d-m-Y H:i') }}</div>
    </div>

    <h3>1. Data Historis Ketersediaan</h3>
    <table>
        <thead>
            <tr>
                <th>Tahun</th>
                <th>Produksi (Ton)</th>
                <th>Stok Awal (Ton)</th>
                <th>Konsumsi (Ton)</th>
                <th>Ketersediaan Bersih (Ton)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataHistoris as $data)
            <tr>
                <td>{{ $data->tahun }}</td>
                <td>{{ number_format($data->produksi_ton, 2, ',', '.') }}</td>
                <td>{{ number_format($data->stok_awal_ton, 2, ',', '.') }}</td>
                <td>{{ number_format($data->konsumsi_ton, 2, ',', '.') }}</td>
                <td>{{ number_format($data->ketersediaan_ton, 2, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>2. Hasil Prediksi ARIMA 3 Tahun Kedepan</h3>
    <table>
        <thead>
            <tr>
                <th>Tahun Prediksi</th>
                <th>Nilai Prediksi (Ton)</th>
                <th>Batas Bawah</th>
                <th>Batas Atas</th>
                <th>Status Kondisi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hasilPrediksi as $pred)
            <tr>
                <td>{{ $pred->tahun_prediksi }}</td>
                <td>{{ number_format($pred->nilai_prediksi, 2, ',', '.') }}</td>
                <td>{{ number_format($pred->lower_bound, 2, ',', '.') }}</td>
                <td>{{ number_format($pred->upper_bound, 2, ',', '.') }}</td>
                <td><span class="status-{{ $pred->status_kondisi }}">{{ strtoupper($pred->status_kondisi) }}</span></td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Belum ada data prediksi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>