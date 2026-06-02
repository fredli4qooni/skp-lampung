<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Data Beras (Ribu Ton)') }}
            </h2>
            <a href="{{ route('admin.data-beras.create') }}" class="bg-[#1E3A5F] hover:bg-[#2E6DA4] text-white px-4 py-2 rounded shadow transition-colors font-bold text-sm whitespace-nowrap">
                + Tambah Data
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                <span class="block sm:inline font-medium">{{ session('success') }}</span>
            </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-[#1E3A5F] uppercase tracking-wider">Periode (Bulan/Tahun)</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-[#1E3A5F] uppercase tracking-wider">Produksi</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-[#1E3A5F] uppercase tracking-wider bg-blue-50">Ketersediaan</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-[#1E3A5F] uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php
                                $namaBulan = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
                            @endphp

                            @forelse ($dataBeras as $data)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">
                                    {{ $namaBulan[$data->bulan] ?? '-' }} {{ $data->tahun }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-gray-700">
                                    {{ number_format($data->produksi_ton, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-[#2E6DA4] bg-blue-50/50">
                                    {{ number_format($data->ketersediaan_ton, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('admin.data-beras.edit', $data->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('admin.data-beras.destroy', $data->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 italic">
                                    Belum ada riwayat data beras.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if(method_exists($dataBeras, 'links'))
                <div class="mt-4">
                    {{ $dataBeras->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>