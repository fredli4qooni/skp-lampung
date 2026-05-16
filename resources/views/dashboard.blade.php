<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Overview Admin') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="bg-gradient-to-r from-[#1E3A5F] to-[#2E6DA4] text-white p-6 rounded-lg shadow-sm mb-6">
            <h3 class="text-xl font-bold">Selamat Datang Kembali, {{ Auth::user()->name }}!</h3>
            <p class="text-sm text-gray-200 mt-1">Sistem Pemantauan dan Prediksi ARIMA Ketahanan Pangan Beras Provinsi Lampung siap dikelola.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Data Historis</div>
                <div class="text-2xl font-bold text-gray-800 mt-1">{{ $totalTahun }} <span class="text-sm font-normal text-gray-500">Tahun</span></div>
                <a href="{{ route('admin.data-beras.index') }}" class="text-xs text-[#2E6DA4] hover:underline inline-block mt-2">Kelola Data &rarr;</a>
            </div>

            <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rata-rata Produksi</div>
                <div class="text-2xl font-bold text-green-600 mt-1">{{ number_format($rataProduksi, 0, ',', '.') }} <span class="text-sm font-normal text-gray-500">Ton</span></div>
                <span class="text-[10px] text-gray-400 block mt-2.5">Dihitung otomatis dari DB</span>
            </div>

            <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Akurasi ARIMA (MAPE)</div>
                <div class="text-2xl font-bold text-[#1E3A5F] mt-1">{{ $infoModel ? number_format($infoModel['mape'], 2) . '%' : 'N/A' }}</div>
                <a href="{{ route('admin.prediksi.index') }}" class="text-xs text-[#2E6DA4] hover:underline inline-block mt-2">Lihat Model &rarr;</a>
            </div>

            <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Proyeksi Status Terdekat</div>
                <div class="mt-2">
                    @if($statusMendatang === 'AMAN')
                        <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-md">{{ $statusMendatang }}</span>
                    @elseif($statusMendatang === 'WASPADA')
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-1 rounded-md">{{ $statusMendatang }}</span>
                    @elseif($statusMendatang === 'DARURAT')
                        <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-1 rounded-md">{{ $statusMendatang }}</span>
                    @else
                        <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-1 rounded-md">{{ $statusMendatang }}</span>
                    @endif
                </div>
                <span class="text-[10px] text-gray-400 block mt-3">Hasil komputasi Python terakhir</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 lg:col-span-2">
                <h4 class="text-sm font-bold text-[#1E3A5F] mb-4 uppercase tracking-wider">Entri Data Historis Terakhir</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50 text-gray-500 font-medium">
                            <tr>
                                <th class="px-4 py-2.5 text-left">Tahun</th>
                                <th class="px-4 py-2.5 text-left">Produksi (Ton)</th>
                                <th class="px-4 py-2.5 text-left">Konsumsi (Ton)</th>
                                <th class="px-4 py-2.5 text-left">Ketersediaan Bersih</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 text-gray-700">
                            @forelse($recentData as $data)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap font-semibold">{{ $data->tahun }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ number_format($data->produksi_ton, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">{{ number_format($data->konsumsi_ton, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 whitespace-nowrap font-bold text-[#1E3A5F]">{{ number_format($data->ketersediaan_ton, 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-center text-gray-400">Belum ada data di database.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <h4 class="text-sm font-bold text-[#1E3A5F] mb-3 uppercase tracking-wider">Alur Kerja Sistem</h4>
                    <ul class="space-y-3 text-xs text-gray-600">
                        <li class="flex items-start">
                            <span class="bg-blue-50 text-[#1E3A5F] rounded-full w-5 h-5 flex items-center justify-center font-bold mr-2 mt-0.5 flex-shrink-0">1</span>
                            <span>Input atau perbarui data ketersediaan tahunan di menu <strong>Data Beras</strong>.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-blue-50 text-[#1E3A5F] rounded-full w-5 h-5 flex items-center justify-center font-bold mr-2 mt-0.5 flex-shrink-0">2</span>
                            <span>Masuk ke menu <strong>Prediksi ARIMA</strong> untuk mengeksekusi komputasi skrip Python Machine Learning.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="bg-blue-50 text-[#1E3A5F] rounded-full w-5 h-5 flex items-center justify-center font-bold mr-2 mt-0.5 flex-shrink-0">3</span>
                            <span>Periksa grafik tren publik dan unduh hasil rekapan cetak resmi di menu <strong>Laporan & Export</strong>.</span>
                        </li>
                    </ul>
                </div>
                
                <div class="pt-4 border-t border-gray-100 mt-4 text-[11px] text-gray-400">
                    Sistem Operasi Engine: <span class="text-green-600 font-semibold">Aktif & Terintegrasi (Venv)</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>