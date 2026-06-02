<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-[#1E3A5F] rounded-lg shadow-md mb-8 overflow-hidden border border-[#172E4D]">
                <div class="p-6 sm:p-10 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative">
                    <div class="absolute top-0 right-0 opacity-10 pointer-events-none">
                        <svg width="200" height="200" viewBox="0 0 100 100" fill="currentColor"><path d="M0 0h100v100H0z"/></svg>
                    </div>

                    <div class="z-10">
                        <h3 class="text-2xl sm:text-3xl font-bold mb-2 flex items-center gap-3">
                            <span>🌾</span> Selamat Datang, Admin!
                        </h3>
                        <p class="text-blue-100 max-w-2xl text-sm sm:text-base leading-relaxed">
                            Ini adalah Pusat Kendali <b>Sistem Ketahanan Pangan (SKP) Provinsi Lampung</b>. Melalui panel ini, Anda dapat mengelola riwayat data beras, menjalankan komputasi kecerdasan buatan (ARIMA), serta mengunduh laporan proyeksi masa depan.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto z-10">
                        <a href="{{ route('admin.data-beras.create') }}" class="text-center bg-white text-[#1E3A5F] hover:bg-gray-100 font-bold py-2.5 px-5 rounded shadow-sm transition-colors whitespace-nowrap">
                            + Tambah Data
                        </a>
                        <a href="{{ route('admin.prediksi.index') }}" class="text-center bg-transparent border-2 border-blue-400 hover:bg-blue-600/30 text-white font-bold py-2.5 px-5 rounded transition-colors whitespace-nowrap">
                            Lihat Analisis Prediksi
                        </a>
                    </div>
                </div>
            </div>

            <h3 class="text-lg font-bold text-gray-700 mb-4 px-2">Ringkasan Status Saat Ini</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center hover:shadow-md transition-shadow">
                    <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Produksi ({{ $dataTerbaru ? $dataTerbaru->tahun : '-' }})</div>
                    <div class="text-3xl font-bold text-[#1E3A5F]">
                        {{ $dataTerbaru ? number_format($dataTerbaru->produksi_ton / 1000, 2, ',', '.') : '0,00' }} <span class="text-base font-medium text-gray-500">Juta Ton</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center hover:shadow-md transition-shadow">
                    <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-1">Ketersediaan Bersih ({{ $dataTerbaru ? $dataTerbaru->tahun : '-' }})</div>
                    <div class="text-3xl font-bold text-[#2E6DA4]">
                        {{ $dataTerbaru ? number_format($dataTerbaru->ketersediaan_ton / 1000, 2, ',', '.') : '0,00' }} <span class="text-base font-medium text-gray-500">Juta Ton</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-center items-start hover:shadow-md transition-shadow">
                    <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Proyeksi Kondisi AI ({{ $prediksiTahunDepan ? $prediksiTahunDepan->tahun_prediksi : 'N/A' }})</div>
                    <div>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold border uppercase tracking-wider {{ $warnaBadge }}">
                            {{ $statusKondisi }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>