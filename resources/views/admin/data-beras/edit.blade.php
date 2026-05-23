<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Beras Bulanan') }}
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
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('admin.data-beras.update', $dataBeras->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="tahun" :value="__('Tahun')" />
                                <x-text-input id="tahun" class="block mt-1 w-full bg-gray-100" type="number" name="tahun" :value="old('tahun', $dataBeras->tahun)" required readonly />
                            </div>

                            <div>
                                <x-input-label :value="__('Bulan')" />
                                <x-text-input class="block mt-1 w-full bg-gray-100" type="text" :value="$namaBulan[$dataBeras->bulan]" readonly />
                                <input type="hidden" name="bulan" value="{{ $dataBeras->bulan }}">
                            </div>

                            <div>
                                <x-input-label for="produksi_ton" :value="__('Produksi (Ton)')" />
                                <x-text-input id="produksi_ton" class="block mt-1 w-full input-hitung" type="number" step="0.01" name="produksi_ton" :value="old('produksi_ton', $dataBeras->produksi_ton)" required />
                                <x-input-error :messages="$errors->get('produksi_ton')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="stok_awal_ton" :value="__('Stok Awal (Ton)')" />
                                <x-text-input id="stok_awal_ton" class="block mt-1 w-full input-hitung" type="number" step="0.01" name="stok_awal_ton" :value="old('stok_awal_ton', $dataBeras->stok_awal_ton)" />
                                <x-input-error :messages="$errors->get('stok_awal_ton')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="konsumsi_ton" :value="__('Konsumsi (Ton)')" />
                                <x-text-input id="konsumsi_ton" class="block mt-1 w-full input-hitung" type="number" step="0.01" name="konsumsi_ton" :value="old('konsumsi_ton', $dataBeras->konsumsi_ton)" required />
                                <x-input-error :messages="$errors->get('konsumsi_ton')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="impor_ton" :value="__('Impor (Ton)')" />
                                <x-text-input id="impor_ton" class="block mt-1 w-full input-hitung" type="number" step="0.01" name="impor_ton" :value="old('impor_ton', $dataBeras->impor_ton)" />
                            </div>

                            <div>
                                <x-input-label for="ekspor_ton" :value="__('Ekspor (Ton)')" />
                                <x-text-input id="ekspor_ton" class="block mt-1 w-full input-hitung" type="number" step="0.01" name="ekspor_ton" :value="old('ekspor_ton', $dataBeras->ekspor_ton)" />
                            </div>

                            <div>
                                <x-input-label for="ketersediaan_ton" :value="__('Ketersediaan Bersih (Ton)')" />
                                <x-text-input id="ketersediaan_ton" class="block mt-1 w-full bg-gray-50 border-gray-300 font-bold text-[#1E3A5F]" type="number" step="0.01" name="ketersediaan_ton" :value="old('ketersediaan_ton', $dataBeras->ketersediaan_ton)" required readonly />
                            </div>
                            
                            <div class="md:col-span-2">
                                <x-input-label for="catatan" :value="__('Catatan Tambahan (Opsional)')" />
                                <textarea id="catatan" name="catatan" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('catatan', $dataBeras->catatan) }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.data-beras.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                            <x-primary-button class="ml-4 bg-[#1E3A5F]">
                                {{ __('Perbarui Data Bulanan') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.input-hitung');
            const ketersediaanInput = document.getElementById('ketersediaan_ton');

            function hitungKetersediaan() {
                let produksi = parseFloat(document.getElementById('produksi_ton').value) || 0;
                let stok = parseFloat(document.getElementById('stok_awal_ton').value) || 0;
                let impor = parseFloat(document.getElementById('impor_ton').value) || 0;
                let konsumsi = parseFloat(document.getElementById('konsumsi_ton').value) || 0;
                let ekspor = parseFloat(document.getElementById('ekspor_ton').value) || 0;

                let ketersediaan = (produksi + stok + impor) - (konsumsi + ekspor);
                ketersediaanInput.value = ketersediaan.toFixed(2);
            }

            hitungKetersediaan();
            inputs.forEach(input => input.addEventListener('input', hitungKetersediaan));
        });
    </script>
</x-app-layout>