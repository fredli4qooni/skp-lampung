<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cetak Laporan & Export Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-center py-10">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    
                    <h3 class="text-xl font-bold text-[#1E3A5F] mb-2">Unduh Laporan Sistem</h3>
                    <p class="text-gray-600 mb-8 max-w-lg mx-auto">Anda dapat mengunduh rekapan data historis ketersediaan beras beserta hasil kalkulasi prediksi ARIMA dalam format PDF atau Spreadsheet Excel.</p>
                    
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <a href="{{ route('admin.laporan.pdf') }}" target="_blank" class="flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded shadow transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Unduh PDF (.pdf)
                        </a>

                        <a href="{{ route('admin.laporan.excel') }}" class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded shadow transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 13.5l3 3m0 0l3-3m-3 3v-6m1.06-4.19l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                            </svg>
                            Unduh Excel (.xlsx)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>