<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Beras Bulanan') }}
        </h2>
    </x-slot>

    @php
        $namaBulan = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="mb-8 bg-blue-50 border-l-4 border-[#2E6DA4] p-4 rounded-r-md shadow-sm">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-0.5">
                                <svg class="h-5 w-5 text-[#2E6DA4]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-[#1E3A5F]">Mode Edit Ringkas</h3>
                                <p class="text-sm text-gray-700 mt-1">Anda dapat mengubah nilai Produksi dan Impor jika ada kekeliruan data. Ketersediaan akan dikalkulasi ulang secara otomatis berdasarkan beban Konsumsi yang dikunci.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.data-beras.update', $dataBeras->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <x-input-label for="tahun" :value="__('Tahun')" />
                                <x-text-input id="tahun" class="block mt-1 w-full bg-gray-100" type="number" name="tahun" :value="old('tahun', $dataBeras->tahun)" required readonly />
                            </div>

                            <div>
                                <x-input-label :value="__('Bulan')" />
                                <x-text-input class="block mt-1 w-full bg-gray-100" type="text" :value="$namaBulan[$dataBeras->bulan]" readonly />
                                <input type="hidden" name="bulan" value="{{ $dataBeras->bulan }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="produksi_ton" :value="__('Produksi (Ribu Ton)')" />
                                <x-text-input id="produksi_ton" class="block mt-1 w-full input-hitung font-semibold text-[#1E3A5F]" type="number" step="0.01" name="produksi_ton" :value="old('produksi_ton', $dataBeras->produksi_ton)" required />
                            </div>

                            <div>
                                <x-input-label for="impor_ton" :value="__('Impor (Ribu Ton)')" />
                                <x-text-input id="impor_ton" class="block mt-1 w-full input-hitung font-semibold text-green-600" type="number" step="0.01" name="impor_ton" :value="old('impor_ton', $dataBeras->impor_ton ?? '')" required />
                            </div>

                            <div>
                                <x-input-label for="konsumsi_ton" :value="__('Konsumsi (Ribu Ton)')" />
                                <x-text-input id="konsumsi_ton" class="block mt-1 w-full input-hitung font-semibold text-orange-600 bg-white" type="number" step="0.01" name="konsumsi_ton" :value="old('konsumsi_ton', $dataBeras->konsumsi_ton ?? '')" required />
                            </div>

                            <div class="md:col-span-3 border-t pt-4">
                                <x-input-label for="ketersediaan_ton" :value="__('Ketersediaan Bersih (Ribu Ton)')" />
                                <x-text-input id="ketersediaan_ton" class="block mt-1 w-full bg-blue-50 border-blue-300 font-bold text-[#1E3A5F] text-lg" type="number" step="0.01" name="ketersediaan_ton" :value="old('ketersediaan_ton', $dataBeras->ketersediaan_ton)" required readonly placeholder="Kalkulasi Otomatis..." />
                                <p class="text-xs text-gray-500 mt-1">* Rumus: (Produksi + Impor) - Konsumsi</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
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
                let impor = parseFloat(document.getElementById('impor_ton').value) || 0;
                let konsumsi = parseFloat(document.getElementById('konsumsi_ton').value) || 73.75;

                let ketersediaan = (produksi + impor) - konsumsi;
                ketersediaanInput.value = ketersediaan.toFixed(2);
            }

            hitungKetersediaan();

            inputs.forEach(input => input.addEventListener('input', hitungKetersediaan));
        });
    </script>
</x-app-layout>