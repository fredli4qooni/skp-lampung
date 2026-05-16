<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Data Beras') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-[#1E3A5F]">Daftar Ketersediaan Beras</h3>
                        <a href="{{ route('admin.data-beras.create') }}" class="bg-[#1E3A5F] hover:bg-[#2E6DA4] text-white font-bold py-2 px-4 rounded">
                            + Tambah Data
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produksi (Ton)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stok (Ton)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Konsumsi (Ton)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ketersediaan (Ton)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($dataBeras as $data)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $data->tahun }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($data->produksi_ton, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($data->stok_awal_ton, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($data->konsumsi_ton, 2, ',', '.') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold {{ $data->ketersediaan_ton >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($data->ketersediaan_ton, 2, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.data-beras.edit', $data->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                        <form action="{{ route('admin.data-beras.destroy', $data->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data tahun {{ $data->tahun }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada data historis yang ditambahkan.</td>
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