<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pusat Kendali Admin') }}
        </h2>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-[#1E3A5F] rounded-lg shadow-md mb-8 overflow-hidden border border-[#172E4D]">
                <div class="p-6 sm:p-10 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative">

                    <div class="z-10">
                        <h3 class="text-2xl sm:text-3xl font-bold mb-2 flex items-center gap-3">
                            <span>🌾</span> Selamat Datang, Admin!
                        </h3>
                        <p class="text-blue-100 max-w-2xl text-sm sm:text-base leading-relaxed">
                            Pantau ringkasan ketersediaan pangan secara makro di bawah ini. Anda dapat mengelola riwayat data beras atau menjalankan ulang algoritma AI melalui tombol aksi cepat.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto z-10">
                        <a href="{{ route('admin.data-beras.create') }}" class="text-center bg-white text-[#1E3A5F] hover:bg-gray-100 font-bold py-2.5 px-5 rounded shadow-sm transition-colors whitespace-nowrap">
                            + Tambah Data
                        </a>
                        <a href="{{ route('admin.prediksi.index') }}" class="text-center bg-transparent border-2 border-blue-400 hover:bg-blue-600/30 text-white font-bold py-2.5 px-5 rounded transition-colors whitespace-nowrap">
                            Analisis AI & Grafik
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center hover:shadow-md transition-shadow border-l-4 border-l-[#1E3A5F]">
                    <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Produksi ({{ $dataTerbaru ? $dataTerbaru->tahun : '-' }})</div>
                    <div class="text-3xl font-bold text-[#1E3A5F]">
                        {{ $dataTerbaru ? number_format($dataTerbaru->produksi_ton / 1000, 2, ',', '.') : '0,00' }} <span class="text-base font-medium text-gray-500">Juta Ton</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center hover:shadow-md transition-shadow border-l-4 border-l-[#2E6DA4]">
                    <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Ketersediaan Bersih ({{ $dataTerbaru ? $dataTerbaru->tahun : '-' }})</div>
                    <div class="text-3xl font-bold text-[#2E6DA4]">
                        {{ $dataTerbaru ? number_format($dataTerbaru->ketersediaan_ton / 1000, 2, ',', '.') : '0,00' }} <span class="text-base font-medium text-gray-500">Juta Ton</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center items-start hover:shadow-md transition-shadow">
                    <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Proyeksi AI ({{ $prediksiTahunDepan ? $prediksiTahunDepan->tahun_prediksi : 'N/A' }})</div>
                    <div>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold border uppercase tracking-wider {{ $warnaBadge }}">
                            {{ $statusKondisi }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-3">
                        <h3 class="text-lg font-bold text-gray-800">Tren Makro Ketersediaan Beras</h3>
                        <span class="text-xs font-bold text-[#2E6DA4] bg-blue-50 px-2 py-1 rounded border border-blue-100">Juta Ton</span>
                    </div>
                    <div class="relative w-full h-[300px]">
                        <canvas id="chartMakro"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden flex flex-col">
                    <div class="bg-gray-50 p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Input Data Terakhir</h3>
                        <a href="{{ route('admin.data-beras.index') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Lihat Semua &rarr;</a>
                    </div>
                    <div class="p-0 overflow-x-auto flex-1">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-white">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Periode</th>
                                    <th class="px-4 py-3 text-right text-xs font-bold text-gray-500 uppercase">Sedia (Ribuan)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @php
                                    $namaBln = [1=>'Jan', 2=>'Feb', 3=>'Mar', 4=>'Apr', 5=>'Mei', 6=>'Jun', 7=>'Jul', 8=>'Agu', 9=>'Sep', 10=>'Okt', 11=>'Nov', 12=>'Des'];
                                @endphp
                                @forelse ($recentData as $rd)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap font-medium text-gray-800">
                                        {{ $namaBln[$rd->bulan] ?? '-' }} {{ $rd->tahun }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right font-bold text-[#2E6DA4]">
                                        {{ number_format($rd->ketersediaan_ton, 2, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="2" class="px-4 py-6 text-center text-gray-400 italic">Belum ada data.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const histTahunan = JSON.parse('@json($dataHistorisTahunan ?? [])');
            const prediksiData = JSON.parse('@json($hasilPrediksi ?? [])');

            const ctx = document.getElementById('chartMakro');
            if(!ctx) return;

            let labels = [...histTahunan.map(h => h.tahun), ...prediksiData.map(p => p.tahun_prediksi)];
            let prodHistorisVal = [...histTahunan.map(h => parseFloat(h.ketersediaan_ton) / 1000), ...Array(prediksiData.length).fill(null)];
            
            let prodPrediksiVal = [];
            if(prediksiData.length > 0 && histTahunan.length > 0) {
                prodPrediksiVal = [
                    ...Array(histTahunan.length - 1).fill(null), 
                    parseFloat(histTahunan[histTahunan.length-1].ketersediaan_ton)/1000, 
                    ...prediksiData.map(p => parseFloat(p.nilai_prediksi)/1000)
                ];
            }

            new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        { 
                            label: 'Historis Aktual', 
                            data: prodHistorisVal, 
                            borderColor: '#2E6DA4', 
                            backgroundColor: 'rgba(46,109,164,0.1)', 
                            borderWidth: 3, 
                            fill: true, 
                            tension: 0.2,
                            pointRadius: 4,
                            pointHoverRadius: 7
                        },
                        { 
                            label: 'Proyeksi AI', 
                            data: prodPrediksiVal, 
                            borderColor: '#9CA3AF', 
                            borderWidth: 2, 
                            borderDash: [5,5], 
                            tension: 0.2,
                            pointRadius: 4,
                            pointHoverRadius: 7
                        }
                    ]
                },
                options: { 
                    responsive: true, 
                    maintainAspectRatio: false, 
                    interaction: { mode: 'index', intersect: false }, 
                    plugins: { legend: { position: 'top' } } 
                }
            });
        });
    </script>
</x-app-layout>