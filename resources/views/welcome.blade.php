<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Ketahanan Pangan - Provinsi Lampung</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="font-sans antialiased bg-[#F8F9FA] text-[#333333]">

    <nav class="bg-[#1E3A5F] text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex-shrink-0 flex items-center gap-3">
                    <span class="text-2xl">🌾</span>
                    <span class="font-bold text-lg tracking-wide">SKP Provinsi Lampung</span>
                </div>
                <div>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/admin/dashboard') }}" class="font-semibold hover:text-gray-300 transition-colors">Panel Admin</a>
                        @else
                            <a href="{{ route('login') }}" class="bg-[#2E6DA4] hover:bg-[#172E4D] px-4 py-2 rounded-md font-semibold transition-colors">Login Admin</a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#1E3A5F]">Dashboard Analisis & Prediksi</h1>
            <p class="text-gray-600 mt-2">Pemantauan Ketersediaan Pangan Beras Provinsi Lampung</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Produksi Terkini ({{ $dataTerbaru ? $dataTerbaru->tahun : '-' }})</div>
                <div class="text-3xl font-bold text-[#1E3A5F]">
                    {{ $dataTerbaru ? number_format($dataTerbaru->produksi_ton, 0, ',', '.') : '0' }} <span class="text-base font-medium text-gray-500">Ton</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Ketersediaan Bersih ({{ $dataTerbaru ? $dataTerbaru->tahun : '-' }})</div>
                <div class="text-3xl font-bold text-[#2E6DA4]">
                    {{ $dataTerbaru ? number_format($dataTerbaru->ketersediaan_ton, 0, ',', '.') : '0' }} <span class="text-base font-medium text-gray-500">Ton</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-center">
                <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Proyeksi Status ({{ $prediksiTahunDepan ? $prediksiTahunDepan->tahun_prediksi : 'N/A' }})</div>
                <div>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold border uppercase tracking-wider {{ $warnaBadge }}">
                        {{ $statusKondisi }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h3 class="text-lg font-bold text-[#1E3A5F] mb-4">Grafik Ketersediaan Beras (Historis & Prediksi)</h3>
            <div class="relative w-full h-[400px]">
                <canvas id="publicChart"></canvas>
            </div>
            <div class="mt-4 flex justify-center space-x-6 text-sm text-gray-600">
                <div class="flex items-center">
                    <span class="w-4 h-1 bg-[#2E6DA4] mr-2 block"></span> Data Historis
                </div>
                <div class="flex items-center">
                    <span class="w-4 h-1 bg-transparent border-t-2 border-dashed border-[#9CA3AF] mr-2 block"></span> Prediksi ARIMA (Warna titik sesuai status)
                </div>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500">
            &copy; 2026 Dashboard Ketahanan Pangan Beras Provinsi Lampung. <br>
            Dikembangkan dengan Laravel & Python (ARIMA).
        </div>
    </footer>

    <script id="data-historis-json" type="application/json">
        {!! json_encode($dataHistoris) !!}
    </script>
    <script id="hasil-prediksi-json" type="application/json">
        {!! json_encode($hasilPrediksi) !!}
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dataHistoris = JSON.parse(document.getElementById('data-historis-json').textContent);
            const hasilPrediksi = JSON.parse(document.getElementById('hasil-prediksi-json').textContent);

            if (dataHistoris.length === 0) return;

            const historicYears = dataHistoris.map(item => item.tahun);
            const predYears = hasilPrediksi.map(item => item.tahun_prediksi);
            const allLabels = [...historicYears, ...predYears];

            const historicValues = dataHistoris.map(item => parseFloat(item.ketersediaan_ton) / 1000);
            const predValues = hasilPrediksi.map(item => parseFloat(item.nilai_prediksi) / 1000);

            const predColors = hasilPrediksi.map(item => {
                let status = item.status_kondisi ? item.status_kondisi.toLowerCase() : '';
                if(status === 'aman') return '#16A34A';
                if(status === 'waspada') return '#EAB308';
                return '#DC2626';
            });

            let datasetHistoris = [...historicValues];
            let datasetPrediksi = [];
            let datasetPrediksiColors = [];

            if (hasilPrediksi.length > 0) {
                const finalHistoricVal = historicValues[historicValues.length - 1];
                datasetHistoris = [...historicValues, ...Array(predValues.length).fill(null)];
                
                datasetPrediksi = [
                    ...Array(historicValues.length - 1).fill(null),
                    finalHistoricVal,
                    ...predValues
                ];

                datasetPrediksiColors = [
                    ...Array(historicValues.length - 1).fill('transparent'),
                    '#2E6DA4',
                    ...predColors
                ];
            }

            const ctx = document.getElementById('publicChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: allLabels,
                    datasets: [
                        {
                            label: 'Historis (Ribu Ton)',
                            data: datasetHistoris,
                            borderColor: '#2E6DA4',
                            backgroundColor: 'rgba(46, 109, 164, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.1
                        },
                        {
                            label: 'Prediksi (Ribu Ton)',
                            data: datasetPrediksi,
                            borderColor: '#9CA3AF',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            backgroundColor: 'transparent',
                            pointBackgroundColor: datasetPrediksiColors,
                            pointBorderColor: datasetPrediksiColors,
                            pointRadius: 6,
                            tension: 0.1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { title: { display: true, text: 'Ribu Ton' } },
                        x: { title: { display: true, text: 'Tahun' } }
                    }
                }
            });
        });
    </script>
</body>
</html>