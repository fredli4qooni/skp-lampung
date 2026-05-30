<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Overview Admin') }}
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
        <div class="bg-gradient-to-r from-[#1E3A5F] to-[#2E6DA4] text-white p-6 rounded-lg shadow-sm mb-6 mx-4 sm:mx-6 lg:mx-8">
            <h3 class="text-xl font-bold">Selamat Datang Kembali, {{ Auth::user()->name }}!</h3>
            <p class="text-sm text-gray-200 mt-1">Sistem Pemantauan dan Prediksi ARIMA Ketahanan Pangan Beras Provinsi Lampung (Mode Hibrida) siap dikelola.</p>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Rentang Data</div>
                    <div class="text-2xl font-bold text-gray-800 mt-1">{{ $totalTahun }} <span class="text-sm font-normal text-gray-500">Tahun</span></div>
                    <a href="{{ route('admin.data-beras.index') }}" class="text-xs text-[#2E6DA4] hover:underline inline-block mt-2">Kelola Data Bulanan &rarr;</a>
                </div>

                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Rata-rata Produksi (Thn)</div>
                    <div class="text-2xl font-bold text-green-600 mt-1">{{ number_format($rataProduksi / 1000, 2, ',', '.') }} <span class="text-sm font-normal text-gray-500">Juta Ton</span></div>
                    <span class="text-[10px] text-gray-400 block mt-2.5">Agregasi otomatis dari DB bulanan</span>
                </div>

                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Akurasi ARIMA (MAPE)</div>
                    <div class="text-2xl font-bold text-[#1E3A5F] mt-1">{{ $infoModel ? number_format($infoModel['mape'], 2) . '%' : 'N/A' }}</div>
                    <a href="{{ route('admin.prediksi.index') }}" class="text-xs text-[#2E6DA4] hover:underline inline-block mt-2">Lihat Parameter Model &rarr;</a>
                </div>

                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200">
                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Proyeksi Status Terdekat</div>
                    <div class="mt-2">
                        @if($statusMendatang === 'AMAN')
                            <span class="bg-green-100 text-green-800 text-xs font-bold px-2.5 py-1 rounded-md border border-green-200">{{ $statusMendatang }}</span>
                        @elseif($statusMendatang === 'WASPADA')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2.5 py-1 rounded-md border border-yellow-200">{{ $statusMendatang }}</span>
                        @elseif($statusMendatang === 'DARURAT')
                            <span class="bg-red-100 text-red-800 text-xs font-bold px-2.5 py-1 rounded-md border border-red-200">{{ $statusMendatang }}</span>
                        @else
                            <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2.5 py-1 rounded-md border border-gray-200">{{ $statusMendatang }}</span>
                        @endif
                    </div>
                    <span class="text-[10px] text-gray-400 block mt-3">Prediksi tahun berikutnya</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 lg:col-span-2">
                    <h4 class="text-sm font-bold text-[#1E3A5F] mb-4 uppercase tracking-wider">Entri Data Historis Bulanan Terakhir</h4>
                    <div class="overflow-x-auto border rounded-lg">
                        <table class="min-w-full divide-y divide-gray-100 text-sm">
                            <thead class="bg-gray-50 text-gray-500 font-medium">
                                <tr>
                                    <th class="px-4 py-3 text-left">Periode</th>
                                    <th class="px-4 py-3 text-right">Produksi (Ribu Ton)</th>
                                    <th class="px-4 py-3 text-right">Ketersediaan Bersih (Ribu Ton)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-gray-700 bg-white">
                                @forelse($recentData as $data)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="font-bold text-gray-900">{{ $namaBulan[$data->bulan] ?? $data->bulan }}</span> 
                                        <span class="text-gray-500 text-xs">{{ $data->tahun }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right">{{ number_format($data->produksi_ton, 2, ',', '.') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap font-bold text-right text-[#1E3A5F]">{{ number_format($data->ketersediaan_ton, 2, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-400 italic">Belum ada data bulanan di database.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 flex flex-col justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-[#1E3A5F] mb-3 uppercase tracking-wider">Alur Kerja Hibrida</h4>
                        <ul class="space-y-3 text-xs text-gray-600">
                            <li class="flex items-start">
                                <span class="bg-blue-50 text-[#1E3A5F] rounded-full w-5 h-5 flex items-center justify-center font-bold mr-2 mt-0.5 flex-shrink-0">1</span>
                                <span>Input riwayat data ketersediaan secara <b>bulanan</b> di menu Manajemen Data.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-blue-50 text-[#1E3A5F] rounded-full w-5 h-5 flex items-center justify-center font-bold mr-2 mt-0.5 flex-shrink-0">2</span>
                                <span>Masuk ke menu <b>Prediksi ARIMA</b>. Sistem akan mengagregasi data bulanan menjadi tahunan secara otomatis.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="bg-blue-50 text-[#1E3A5F] rounded-full w-5 h-5 flex items-center justify-center font-bold mr-2 mt-0.5 flex-shrink-0">3</span>
                                <span>Proyeksi makro (3 Tahun) dieksekusi skrip Python, menghitung surplus/defisit, dan disajikan di Laporan Cetak.</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-100 mt-4 text-[11px] text-gray-400">
                        Asumsi Konsumsi Makro: <span class="text-gray-700 font-semibold">{{ number_format($konsumsiTahunan / 1000, 2, ',', '.') }} Juta Ton/Thn</span><br>
                        Status Engine: <span class="text-green-600 font-semibold">Aktif & Terintegrasi (Venv)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>