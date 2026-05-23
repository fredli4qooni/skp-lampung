<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Analisis & Prediksi SARIMA (Bulanan)') }}
        </h2>
    </x-slot>

    @php
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
    @endphp

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
                    <h3 class="text-lg font-bold text-[#1E3A5F] mb-2">Komputasi Mesin SARIMA</h3>
                    <p class="text-sm text-gray-600 mb-4">Tekan tombol di bawah untuk mengeksekusi mesin Machine Learning Python membaca pola panen bulanan (Seasonal ARIMA).</p>
                </div>
                <form action="{{ route('admin.prediksi.jalankan') }}" method="POST" onsubmit="return confirm('Jalankan komputasi? Proses ini memakan waktu beberapa detik untuk interpolasi data.');">
                    @csrf
                    <button type="submit" class="w-full bg-[#1E3A5F] hover:bg-[#2E6DA4] text-white font-bold py-3 px-4 rounded transition-colors flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        Jalankan Proyeksi 12 Bulan
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
                            <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1.5 rounded-full uppercase">SARIMA Aktif</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-6 text-gray-500">Belum ada riwayat komputasi model.</div>
                @endif
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 mb-6">
            <h3 class="text-lg font-bold text-[#1E3A5F] mb-4">Grafik Tren Ketersediaan Bersih Bulanan (Ribu Ton)</h3>
            <div class="relative w-full h-[350px]">
                <canvas id="arimaChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-lg font-bold text-[#1E3A5F] mb-4">Tabel Proyeksi 12 Bulan Kedepan</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proyeksi (Ton)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Batas Bawah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Batas Atas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($hasilPrediksi as $pred)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">{{ $namaBulan[$pred->bulan_prediksi] ?? $pred->bulan_prediksi }} {{ $pred->tahun_prediksi }}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-[#1E3A5F]">{{ number_format($pred->nilai_prediksi, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ number_format($pred->lower_bound, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ number_format($pred->upper_bound, 2, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($pred->status_kondisi === 'aman')
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded border border-green-200 uppercase">Aman</span>
                                    @elseif($pred->status_kondisi === 'waspada')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded border border-yellow-200 uppercase">Waspada</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-1 rounded border border-red-200 uppercase">Darurat</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data hasil prediksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script id="data-historis-json" type="application/json">{!! json_encode($dataHistoris) !!}</script>
    <script id="hasil-prediksi-json" type="application/json">{!! json_encode($hasilPrediksi) !!}</script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dataHistoris = JSON.parse(document.getElementById('data-historis-json').textContent);
            const hasilPrediksi = JSON.parse(document.getElementById('hasil-prediksi-json').textContent);

            const monthNames = ["", "Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agt", "Sep", "Okt", "Nov", "Des"];
            
            const historicLabels = dataHistoris.map(item => `${monthNames[item.bulan]} ${item.tahun}`);
            const predLabels = hasilPrediksi.map(item => `${monthNames[item.bulan_prediksi]} ${item.tahun_prediksi}`);
            const allLabels = [...historicLabels, ...predLabels];

            const historicValues = dataHistoris.map(item => parseFloat(item.ketersediaan_ton) / 1000);
            const predValues = hasilPrediksi.map(item => parseFloat(item.nilai_prediksi) / 1000);

            let datasetHistoris = [...historicValues];
            let datasetPrediksi = [];

            if (hasilPrediksi.length > 0 && historicValues.length > 0) {
                const finalHistoricVal = historicValues[historicValues.length - 1];
                datasetHistoris = [...historicValues, ...Array(predValues.length).fill(null)];
                datasetPrediksi = [
                    ...Array(historicValues.length - 1).fill(null),
                    finalHistoricVal,
                    ...predValues
                ];
            }

            const ctx = document.getElementById('arimaChart').getContext('2d');
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
                            borderWidth: 2,
                            pointRadius: 2,
                            fill: true,
                            tension: 0.2
                        },
                        {
                            label: 'Proyeksi SARIMA (Ribu Ton)',
                            data: datasetPrediksi,
                            borderColor: '#FF6B35',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            pointBackgroundColor: '#FF6B35',
                            pointRadius: 3,
                            backgroundColor: 'transparent',
                            tension: 0.2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: { mode: 'index', intersect: false }
                    },
                    scales: {
                        y: { title: { display: true, text: 'Ribu Ton' } },
                        x: { 
                            title: { display: true, text: 'Periode Bulan' },
                            ticks: { maxTicksLimit: 24 }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>