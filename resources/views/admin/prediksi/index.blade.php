<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Analisis & Dasbor Prediksi ARIMA') }}
        </h2>
    </x-slot>

    <div class="py-6">
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 shadow-sm">
            <span class="block sm:inline font-medium">{{ session('success') }}</span>
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6 shadow-sm">
            <span class="block sm:inline font-medium">{{ session('error') }}</span>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <h3 class="text-lg font-bold text-[#1E3A5F] mb-2">Komputasi Mesin ARIMA</h3>
                    <p class="text-sm text-gray-600 mb-4">Sistem akan memproses seluruh data riwayat secara otomatis menggunakan model prediktif kecerdasan buatan Python.</p>
                </div>
                <form action="{{ route('admin.prediksi.jalankan') }}" method="POST" onsubmit="return confirm('Jalankan komputasi model?');">
                    @csrf
                    <button type="submit" class="w-full bg-[#1E3A5F] hover:bg-[#2E6DA4] text-white font-bold py-3 px-4 rounded transition-colors flex items-center justify-center shadow-md">
                        Jalankan Proyeksi Sistem
                    </button>
                </form>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 lg:col-span-2">
                <h3 class="text-lg font-bold text-[#1E3A5F] mb-3">Evaluasi & Parameter Model</h3>
                @if($akurasi)
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-100 text-center">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Orde (p,d,q)</div>
                        <div class="text-xl font-bold text-gray-800 mt-1">({{ $akurasi['p'] }}, {{ $akurasi['d'] }}, {{ $akurasi['q'] }})</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-100 text-center">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai MAPE</div>
                        <div class="text-xl font-bold text-gray-800 mt-1">{{ number_format($akurasi['mape'], 2) }}%</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-100 text-center">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Nilai RMSE</div>
                        <div class="text-xl font-bold text-gray-800 mt-1">{{ number_format($akurasi['rmse'], 2) }}</div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-md border border-gray-100 flex flex-col justify-center items-center">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status Enjin</div>
                        <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1.5 rounded-full uppercase">Optimal</span>
                    </div>
                </div>
                @else
                <div class="text-center py-6 text-gray-500">Belum ada riwayat komputasi model.</div>
                @endif
            </div>
        </div>

        <div class="flex justify-between items-center mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-200 flex-col sm:flex-row gap-4">
            <div>
                <h3 class="text-base font-bold text-gray-700">Pilihan Skala Dimensi Tampilan:</h3>
                <p class="text-xs text-gray-500">Pilih rentang waktu penyajian data pada grafik dan tabel analisa neraca.</p>
            </div>
            <div class="inline-flex rounded-md shadow-sm" role="group">
                <button type="button" id="btn-tahunan" onclick="switchMode('tahunan')" class="px-5 py-2.5 text-sm font-bold rounded-l-lg border border-gray-200 bg-[#1E3A5F] text-white transition-colors">
                    Prediksi Tahunan (Juta Ton)
                </button>
                <button type="button" id="btn-bulanan" onclick="switchMode('bulanan')" class="px-5 py-2.5 text-sm font-bold rounded-r-lg border-t border-b border-r border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                    Prediksi Bulanan (Ribu Ton)
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
                    <h4 class="text-base font-bold text-[#1E3A5F] uppercase tracking-wider">2. Grafik Tren Beban Konsumsi Daerah</h4>
                    <span id="badge-skala-konsumsi" class="text-sm font-bold text-orange-600 bg-orange-50 px-3 py-1.5 rounded-md border border-orange-100 shadow-sm">Skala: Juta Ton</span>
                </div>
                <div class="relative w-full h-[400px]">
                    <canvas id="chartKonsumsi"></canvas>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-[#1E3A5F] mb-1">Tabel Proyeksi Neraca Ketersediaan Beras</h3>
                    <p class="text-sm text-gray-600 max-w-3xl">
                        Tabel ini menampilkan kalkulasi Neraca pangan berdasarkan rumus <b>(Total Ketersediaan - Beban Konsumsi)</b> untuk mengetahui kondisi ketahanan pangan (Surplus / Defisit).
                    </p>
                </div>
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-md p-4 mb-6 shadow-sm">
                <h4 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Keterangan Indikator Status Proyeksi</h4>
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-8 text-sm">
                    <div class="flex items-center">
                        <span class="bg-green-100 border border-green-300 w-4 h-4 rounded inline-block mr-2.5"></span>
                        <span class="text-slate-700"><b>Aman:</b> &ge; 0,50 Juta Ton</span>
                    </div>
                    <div class="flex items-center">
                        <span class="bg-yellow-100 border border-yellow-300 w-4 h-4 rounded inline-block mr-2.5"></span>
                        <span class="text-slate-700"><b>Hati-Hati:</b> &lt; 0,45 Juta Ton </span>
                    </div>
                    <div class="flex items-center">
                        <span class="bg-red-100 border border-red-300 w-4 h-4 rounded inline-block mr-2.5"></span>
                        <span class="text-slate-700"><b>Darurat:</b> &lt; 0,20 Juta Ton </span>
                    </div>
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
    </div>

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
                
                konsumsiValues = [
                    ...histTahunan.map(h => (parseFloat(h.konsumsi_ton) || 0) / 1000), 
                    ...Array(prediksiData.length).fill(null)
                ];
                
                if(prediksiData.length > 0 && histTahunan.length > 0) {
                    prodPrediksiVal = [...Array(histTahunan.length - 1).fill(null), parseFloat(histTahunan[histTahunan.length-1].ketersediaan_ton)/1000, ...prediksiData.map(p => parseFloat(p.nilai_prediksi)/1000)];
                }

                if (prediksiData.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="3" class="px-6 py-6 text-center text-gray-500 italic">Data proyeksi masa depan belum tersedia.</td></tr>`;
                } else {
                    prediksiData.forEach(p => {
                        let ketersediaan = p.nilai_prediksi / 1000;
                        let kondisiStr = 'AMAN';
                        let statusBg = 'bg-green-100 text-green-800';

                        if (ketersediaan >= 0.50) {
                            kondisiStr = 'AMAN'; statusBg = 'bg-green-100 text-green-800';
                        } else if (ketersediaan < 0.50 && ketersediaan >= 0.20) {
                            kondisiStr = 'HATI-HATI'; statusBg = 'bg-yellow-100 text-yellow-800';
                        } else if (ketersediaan < 0.20 && ketersediaan >= -0.45) {
                            kondisiStr = 'DARURAT'; statusBg = 'bg-red-100 text-red-800';
                        } else {
                            kondisiStr = 'HATI-HATI'; statusBg = 'bg-yellow-100 text-yellow-800';
                        }

                        tbody.innerHTML += `
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 text-base">${p.tahun_prediksi}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-[#2E6DA4] text-base">${formatId(ketersediaan)}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-3 py-1 text-xs font-bold rounded uppercase ${statusBg}">${kondisiStr}</span>
                                </td>
                            </tr>`;
                    });
                }
            } 
            else {
                if(thPeriode) thPeriode.innerText = "Bulan Proyeksi";
                if(thKetersediaan) thKetersediaan.innerText = "Total Ketersediaan (Ribu Ton)";

                let histLabels = histBulanan.map(h => `${namaBulanMap[h.bulan]} ${h.tahun}`);
                let pHistVal = histBulanan.map(h => parseFloat(h.ketersediaan_ton));
                
                let kHistVal = histBulanan.map(h => parseFloat(h.konsumsi_ton) || 0);

                let predLabels = [], pPredVal = [];

                if (prediksiData.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="3" class="px-6 py-8 text-center text-gray-500 italic">Data proyeksi masa depan belum tersedia.</td></tr>`;
                } else {
                    prediksiData.forEach(p => {
                        let ketersediaanBulan = parseFloat(p.nilai_prediksi) / 12;
                        let ketersediaanTahunan = parseFloat(p.nilai_prediksi) / 1000;
                        
                        let kondisiStr = 'AMAN';
                        let statusBg = 'bg-green-100 text-green-800';

                        if (ketersediaanTahunan >= 0.50) {
                            kondisiStr = 'AMAN'; statusBg = 'bg-green-100 text-green-800';
                        } else if (ketersediaanTahunan < 0.50 && ketersediaanTahunan >= 0.20) {
                            kondisiStr = 'HATI-HATI'; statusBg = 'bg-yellow-100 text-yellow-800';
                        } else if (ketersediaanTahunan < 0.20 && ketersediaanTahunan >= -0.45) {
                            kondisiStr = 'DARURAT'; statusBg = 'bg-red-100 text-red-800';
                        } else {
                            kondisiStr = 'HATI-HATI'; statusBg = 'bg-yellow-100 text-yellow-800';
                        }

                        for(let i=1; i<=12; i++) {
                            predLabels.push(`${namaBulanMap[i]} ${p.tahun_prediksi}`);
                            pPredVal.push(ketersediaanBulan);

                            tbody.innerHTML += `
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 text-sm">${namaBulanMap[i]} ${p.tahun_prediksi}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-[#2E6DA4]">${formatId(ketersediaanBulan)}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 py-1 text-[10px] font-bold rounded uppercase ${statusBg}">${kondisiStr}</span>
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
</x-app-layout>