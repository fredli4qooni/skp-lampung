<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Beras') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.data-beras.update', $dataBeras->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="tahun" :value="__('Tahun')" />
                                <x-text-input id="tahun" class="block mt-1 w-full bg-gray-100" type="number" name="tahun" :value="old('tahun', $dataBeras->tahun)" required readonly />
                            </div>

                            <div>
                                <x-input-label for="bulan" :value="__('Bulan')" />
                                <input type="hidden" name="bulan" value="{{ old('bulan', $dataBeras->bulan) }}">
                                <x-text-input id="bulan_display" class="block mt-1 w-full bg-gray-100" type="text" :value="date('F', mktime(0, 0, 0, old('bulan', $dataBeras->bulan), 1))" required readonly />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <x-input-label for="produksi_ton" :value="__('Produksi (Ribu Ton)')" />
                                <x-text-input id="produksi_ton" class="block mt-1 w-full input-hitung font-semibold text-[#1E3A5F]" type="number" step="0.01" name="produksi_ton" :value="old('produksi_ton', $dataBeras->produksi_ton)" required />
                            </div>

                            <div>
                                <x-input-label for="impor_ton" :value="__('Impor (Ribu Ton)')" />
                                <x-text-input id="impor_ton" class="block mt-1 w-full input-hitung font-semibold text-green-600" type="number" step="0.01" name="impor_ton" :value="old('impor_ton', $dataBeras->impor_ton)" required />
                            </div>

                            <div>
                                <x-input-label for="konsumsi_ton" :value="__('Konsumsi (Ribu Ton)')" />
                                <x-text-input id="konsumsi_ton" class="block mt-1 w-full input-hitung font-semibold text-orange-600" type="number" step="0.01" name="konsumsi_ton" :value="old('konsumsi_ton', $dataBeras->konsumsi_ton)" required />
                            </div>

                            <div class="md:col-span-3 border-t pt-4">
                                <x-input-label for="ketersediaan_ton" :value="__('Ketersediaan Bersih (Ribu Ton)')" />
                                <x-text-input id="ketersediaan_ton" class="block mt-1 w-full bg-blue-50 border-blue-300 font-bold text-[#1E3A5F] text-lg" type="number" step="0.01" name="ketersediaan_ton" :value="old('ketersediaan_ton', $dataBeras->ketersediaan_ton)" required readonly />
                                <p class="text-xs text-gray-500 mt-1">* Rumus: (Produksi + Impor) - Konsumsi</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4 gap-4">
                            <a href="{{ route('admin.data-beras.index') }}" class="text-gray-500 hover:text-gray-700 font-medium">Batal</a>
                            <button type="submit" class="bg-[#1E3A5F] hover:bg-[#2E6DA4] text-white font-bold py-2 px-6 rounded transition-colors">
                                Perbarui Data
                            </button>
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
                let impor = parseFloat(document.getElementById('impor_ton').value) || 0;
                let konsumsi = parseFloat(document.getElementById('konsumsi_ton').value) || 0;

                let ketersediaan = (produksi + impor) - konsumsi;
                
                ketersediaanInput.value = ketersediaan.toFixed(2);
            }

            hitungKetersediaan();
            
            inputs.forEach(input => input.addEventListener('input', hitungKetersediaan));
        });
    </script>
</x-app-layout>