<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Data Beras (Bulanan)') }}
        </h2>
    </x-slot>

    @php
    $namaBulan = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-[#1E3A5F] mb-3 sm:mb-0">Daftar Produksi & Ketersediaan Beras</h3>
                        <a href="{{ route('admin.data-beras.create') }}" class="bg-[#1E3A5F] hover:bg-[#2E6DA4] text-white font-bold py-2 px-4 rounded transition-colors shadow-sm">
                            + Tambah Data Bulanan
                        </a>
                    </div>

                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-[#1E3A5F] uppercase tracking-wider">Periode</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-[#1E3A5F] uppercase tracking-wider">Produksi (Ribu Ton)</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-[#1E3A5F] uppercase tracking-wider">Ketersediaan Bersih (Ribu Ton)</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-[#1E3A5F] uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($dataBeras as $data)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900">{{ $namaBulan[$data->bulan] ?? $data->bulan }}</div>
                                        <div class="text-sm text-gray-500">{{ $data->tahun }}</div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right font-semibold text-gray-700">
                                        {{ number_format($data->produksi_ton, 2, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-base {{ $data->ketersediaan_ton >= 0 ? 'text-[#2E6DA4]' : 'text-red-600' }}">
                                        {{ number_format($data->ketersediaan_ton, 2, ',', '.') }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <a href="{{ route('admin.data-beras.edit', $data->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded mr-2 border border-indigo-200">Edit</a>
                                        <form action="{{ route('admin.data-beras.destroy', $data->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus data {{ $namaBulan[$data->bulan] ?? $data->bulan }} {{ $data->tahun }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1 rounded border border-red-200">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">Belum ada data historis bulanan yang ditambahkan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $dataBeras->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>