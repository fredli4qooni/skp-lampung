<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Ketahanan Pangan (SKP) - Provinsi Lampung</title>
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
                    <span class="font-bold text-lg tracking-wide hidden sm:block">Sistem Ketahanan Pangan (SKP) Provinsi Lampung</span>
                    <span class="font-bold text-lg tracking-wide sm:hidden">SKP Lampung</span>
                </div>
                <div>
                    @if (Route::has('login'))
                    @auth
                    <a href="{{ url('/admin/dashboard') }}" class="font-semibold hover:text-gray-300 transition-colors">Panel Admin</a>
                    @else
                    <a href="{{ route('login') }}" class="bg-[#2E6DA4] hover:bg-[#172E4D] px-4 py-2 rounded-md text-sm font-semibold transition-colors">Login Admin</a>
                    @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#1E3A5F]">Dashboard Analisis & Prediksi</h1>
            <p class="text-gray-600 mt-2">Pemantauan Ketersediaan Pangan Beras Provinsi Lampung secara prediktif berbasis <i>Machine Learning</i>.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Produksi ({{ $dataTerbaru ? $dataTerbaru->tahun : '-' }})</div>
                <div class="text-3xl font-bold text-[#1E3A5F]">
                    {{ $dataTerbaru ? number_format($dataTerbaru->produksi_ton / 1000, 2, ',', '.') : '0,00' }} <span class="text-base font-medium text-gray-500">Juta Ton</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Ketersediaan Bersih ({{ $dataTerbaru ? $dataTerbaru->tahun : '-' }})</div>
                <div class="text-3xl font-bold text-[#2E6DA4]">
                    {{ $dataTerbaru ? number_format($dataTerbaru->ketersediaan_ton / 1000, 2, ',', '.') : '0,00' }} <span class="text-base font-medium text-gray-500">Juta Ton</span>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col justify-center items-start">
                <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Proyeksi Status ({{ $prediksiTahunDepan ? $prediksiTahunDepan->tahun_prediksi : 'N/A' }})</div>
                <div>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold border uppercase tracking-wider {{ $warnaBadge }}">
                        {{ $statusKondisi }}
                    </span>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex-col sm:flex-row gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-700">Pilihan Skala Dimensi Tampilan:</h3>
                <p class="text-xs text-gray-500">Sesuaikan tampilan grafik dan tabel berdasarkan rentang periode agregasi.</p>
            </div>
            <div class="inline-flex rounded-md shadow-sm" role="group">
                <button type="button" id="btn-tahunan" onclick="switchMode('tahunan')" class="px-5 py-2.5 text-sm font-bold rounded-l-lg border border-gray-200 bg-[#1E3A5F] text-white transition-colors">
                    Skala Tahunan (Juta Ton)
                </button>
                <button type="button" id="btn-bulanan" onclick="switchMode('bulanan')" class="px-5 py-2.5 text-sm font-bold rounded-r-lg border-t border-b border-r border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                    Skala Bulanan (Ribu Ton)
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-base font-bold text-[#1E3A5F] uppercase tracking-wider">1. Grafik Ketersediaan Historis & Proyeksi AI</h4>
                    <span id="badge-skala-produksi" class="text-sm font-bold text-[#2E6DA4] bg-blue-50 px-3 py-1.5 rounded-md border border-blue-100 shadow-sm">Skala: Juta Ton</span>
                </div>
                <div class="relative w-full h-[400px]">
                    <canvas id="chartProduksi"></canvas>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-base font-bold text-[#1E3A5F] uppercase tracking-wider">2. Grafik Beban Konsumsi Provinsi</h4>
                    <span id="badge-skala-konsumsi" class="text-sm font-bold text-orange-600 bg-orange-50 px-3 py-1.5 rounded-md border border-orange-100 shadow-sm">Skala: Juta Ton</span>
                </div>
                <div class="relative w-full h-[400px]">
                    <canvas id="chartKonsumsi"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-[#1E3A5F] mb-1">Tabel Proyeksi Neraca Ketersediaan Beras</h3>
                    <p class="text-sm text-gray-600 max-w-3xl">
                        Tabel ini menampilkan kalkulasi Neraca pangan berdasarkan rumus <b>(Total Ketersediaan - Beban Konsumsi)</b> untuk mengetahui kondisi ketahanan pangan (Surplus / Defisit).
                    </p>
                </div>
                <div class="mt-4 lg:mt-0">
                    <span id="label-asumsi-konsumsi" class="text-sm bg-gray-100 text-gray-600 px-3 py-2 rounded border font-medium">Asumsi Beban: <b>0,89 Juta Ton/Tahun</b></span>
                </div>
            </div>

            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th id="th-periode" class="px-6 py-4 text-left font-bold text-[#1E3A5F] uppercase tracking-wider">Periode</th>
                            <th id="th-ketersediaan" class="px-6 py-4 text-right font-bold text-[#1E3A5F] uppercase tracking-wider">Total Ketersediaan</th>
                            <th class="px-6 py-4 text-center font-bold text-[#1E3A5F] uppercase tracking-wider">Status Proyeksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-prediksi-body" class="bg-white divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-gray-200 py-6 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-sm text-gray-500">
            &copy; 2026 Dashboard Sistem Ketahanan Pangan (SKP) Provinsi Lampung. <br>
            Dikembangkan dengan Framework Laravel & Python (ARIMA).
        </div>
    </footer>

    <script id="historis-tahunan-json" type="application/json">{!! json_encode($dataHistorisTahunan ?? []) !!}</script>
    <script id="historis-bulanan-json" type="application/json">{!! json_encode($dataHistorisBulanan ?? []) !!}</script>
    <script id="prediksi-json" type="application/json">{!! json_encode($hasilPrediksi ?? []) !!}</script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        let currentMode = 'tahunan';
        let chartP, chartK;

        const histTahunan = JSON.parse('@json($dataHistorisTahunan ?? [])');
        const histBulanan = JSON.parse('@json($dataHistorisBulanan ?? [])');
        const prediksiData = JSON.parse('@json($hasilPrediksi ?? [])');

        const namaBulanMap = {1:'Jan', 2:'Feb', 3:'Mar', 4:'Apr', 5:'Mei', 6:'Jun', 7:'Jul', 8:'Agu', 9:'Sep', 10:'Okt', 11:'Nov', 12:'Des'};

        function formatId(num) {
            return new Intl.NumberFormat('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(num);
        }

        function switchMode(mode) {
            currentMode = mode;
            const btnT = document.getElementById('btn-tahunan');
            const btnB = document.getElementById('btn-bulanan');

            if (mode === 'tahunan') {
                if(btnT) btnT.className = "px-5 py-2.5 text-sm font-bold rounded-l-lg border border-gray-200 bg-[#1E3A5F] text-white transition-colors";
                if(btnB) btnB.className = "px-5 py-2.5 text-sm font-bold rounded-r-lg border-t border-b border-r border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition-colors";
                if(document.getElementById('badge-skala-produksi')) document.getElementById('badge-skala-produksi').innerText = "Skala: Juta Ton";
                if(document.getElementById('badge-skala-konsumsi')) document.getElementById('badge-skala-konsumsi').innerText = "Skala: Juta Ton";
            } else {
                if(btnB) btnB.className = "px-5 py-2.5 text-sm font-bold rounded-r-lg border border-[#1E3A5F] bg-[#1E3A5F] text-white transition-colors";
                if(btnT) btnT.className = "px-5 py-2.5 text-sm font-bold rounded-l-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition-colors";
                if(document.getElementById('badge-skala-produksi')) document.getElementById('badge-skala-produksi').innerText = "Skala: Ribu Ton";
                if(document.getElementById('badge-skala-konsumsi')) document.getElementById('badge-skala-konsumsi').innerText = "Skala: Ribu Ton";
            }
            renderAll();
        }

        function renderAll() {
            let labels = [];
            let prodHistorisVal = [], prodPrediksiVal = [], konsumsiValues = [];

            let thPeriode = document.getElementById('th-periode');
            let thKetersediaan = document.getElementById('th-ketersediaan');
            let tbody = document.getElementById('tabel-prediksi-body');

            if(!tbody) return;
            tbody.innerHTML = '';

            if (currentMode === 'tahunan') {
                if(thPeriode) thPeriode.innerText = "Tahun Proyeksi";
                if(thKetersediaan) thKetersediaan.innerText = "Total Ketersediaan (Juta Ton)";

                labels = [...histTahunan.map(h => h.tahun), ...prediksiData.map(p => p.tahun_prediksi)];
                prodHistorisVal = [...histTahunan.map(h => parseFloat(h.ketersediaan_ton) / 1000), ...Array(prediksiData.length).fill(null)];
                
                konsumsiValues = [...histTahunan.map(h => (parseFloat(h.konsumsi_ton) || 885) / 1000), ...Array(prediksiData.length).fill(null)];
                
                if(prediksiData.length > 0 && histTahunan.length > 0) {
                    prodPrediksiVal = [...Array(histTahunan.length - 1).fill(null), parseFloat(histTahunan[histTahunan.length-1].ketersediaan_ton)/1000, ...prediksiData.map(p => parseFloat(p.nilai_prediksi)/1000)];
                }

                if (prediksiData.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="3" class="px-6 py-6 text-center text-gray-500 italic">Data proyeksi masa depan belum tersedia.</td></tr>`;
                } else {
                    prediksiData.forEach(p => {
                        let ketersediaan = p.nilai_prediksi / 1000;
                        let kondisiStr = p.status_kondisi ? p.status_kondisi.toLowerCase() : 'aman';
                        let statusBg = kondisiStr === 'aman' ? 'bg-green-100 text-green-800' : (kondisiStr === 'waspada' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');

                        tbody.innerHTML += `
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 text-base">${p.tahun_prediksi}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-[#2E6DA4] text-base">${formatId(ketersediaan)}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 text-xs font-bold rounded uppercase ${statusBg}">${p.status_kondisi || 'AMAN'}</span>
                                </td>
                            </tr>`;
                    });
                }
            } else {
                if(thPeriode) thPeriode.innerText = "Bulan Proyeksi";
                if(thKetersediaan) thKetersediaan.innerText = "Total Ketersediaan (Ribu Ton)";

                let histLabels = histBulanan.map(h => `${namaBulanMap[h.bulan]} ${h.tahun}`);
                let pHistVal = histBulanan.map(h => parseFloat(h.ketersediaan_ton));
                let kHistVal = histBulanan.map(h => parseFloat(h.konsumsi_ton) || 73.75);

                let predLabels = [], pPredVal = [];

                if (prediksiData.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="3" class="px-6 py-8 text-center text-gray-500 italic">Data proyeksi masa depan belum tersedia.</td></tr>`;
                } else {
                    prediksiData.forEach(p => {
                        let ketersediaanBulan = parseFloat(p.nilai_prediksi) / 12;
                        let kondisiStr = p.status_kondisi ? p.status_kondisi.toLowerCase() : 'aman';
                        let statusBg = kondisiStr === 'aman' ? 'bg-green-100 text-green-800' : (kondisiStr === 'waspada' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');

                        for(let i=1; i<=12; i++) {
                            predLabels.push(`${namaBulanMap[i]} ${p.tahun_prediksi}`);
                            pPredVal.push(ketersediaanBulan);

                            tbody.innerHTML += `
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 text-sm">${namaBulanMap[i]} ${p.tahun_prediksi}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-[#2E6DA4]">${formatId(ketersediaanBulan)}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 py-1 text-[10px] font-bold rounded uppercase ${statusBg}">${p.status_kondisi || 'AMAN'}</span>
                                    </td>
                                </tr>`;
                        }
                    });
                }

                labels = [...histLabels, ...predLabels];
                prodHistorisVal = [...pHistVal, ...Array(predLabels.length).fill(null)];
                konsumsiValues = [...kHistVal, ...Array(predLabels.length).fill(null)];
                
                if (prediksiData.length > 0 && histBulanan.length > 0) {
                    prodPrediksiVal = [...Array(histBulanan.length - 1).fill(null), parseFloat(histBulanan[histBulanan.length-1].ketersediaan_ton), ...pPredVal];
                } else {
                    prodPrediksiVal = [...Array(histBulanan.length).fill(null), ...pPredVal];
                }
            }

            updateCharts(labels, prodHistorisVal, prodPrediksiVal, konsumsiValues);
        }

        function updateCharts(labels, prodHist, prodPred, konsumsi) {
            const canvasP = document.getElementById('chartProduksi');
            const canvasK = document.getElementById('chartKonsumsi');
            if(!canvasP || !canvasK) return;

            if (chartP) chartP.destroy();
            if (chartK) chartK.destroy();

            chartP = new Chart(canvasP.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Historis (Net Ketersediaan)', data: prodHist, borderColor: '#2E6DA4', backgroundColor: 'rgba(46,109,164,0.1)', borderWidth: 2, fill: true, tension: 0.1, pointRadius: 1, pointHoverRadius: 6 },
                        { label: 'Proyeksi AI', data: prodPred, borderColor: '#9CA3AF', borderWidth: 2, borderDash: [5,5], pointRadius: 5, pointHoverRadius: 8, tension: 0.1 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, plugins: { legend: { position: 'bottom' } } }
            });

            chartK = new Chart(canvasK.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        { label: 'Beban Konsumsi Aktual', data: konsumsi, borderColor: '#EA580C', backgroundColor: 'rgba(234,88,12,0.05)', borderWidth: 2, fill: true, tension: 0, pointRadius: 1, pointHoverRadius: 6 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false, interaction: { mode: 'index', intersect: false }, plugins: { legend: { position: 'bottom' } } }
            });
        }

        document.addEventListener('DOMContentLoaded', () => { renderAll(); });
    </script>
</body>

</html>