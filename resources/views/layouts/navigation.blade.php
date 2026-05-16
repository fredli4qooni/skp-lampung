<div class="w-64 bg-[#1E3A5F] text-white flex flex-col min-h-screen shadow-lg">
    <div class="p-6 border-b border-[#2E6DA4] flex flex-col items-center justify-center bg-[#172E4D]">
        <span class="text-xl font-bold tracking-wider text-white">SKP LAMPUNG</span>
        <span class="text-xs text-gray-300 mt-1 uppercase font-semibold tracking-widest">Panel Admin</span>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-2">

        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-[#2E6DA4] text-white shadow-md' : 'text-gray-300 hover:bg-[#2E6DA4]/50 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
            <span>Dashboard Overview</span>
        </a>

        <a href="{{ route('admin.data-beras.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-all duration-200 {{ request()->routeIs('admin.data-beras.*') ? 'bg-[#2E6DA4] text-white shadow-md' : 'text-gray-300 hover:bg-[#2E6DA4]/50 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
            </svg>
            <span>Manajemen Data Beras</span>
        </a>

        <a href="{{ route('admin.prediksi.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-all duration-200 {{ request()->routeIs('admin.prediksi.*') ? 'bg-[#2E6DA4] text-white shadow-md' : 'text-gray-300 hover:bg-[#2E6DA4]/50 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
            </svg>
            <span>Prediksi ARIMA</span>
        </a>

        <a href="{{ route('admin.laporan.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium rounded-md transition-all duration-200 {{ request()->routeIs('admin.laporan.*') ? 'bg-[#2E6DA4] text-white shadow-md' : 'text-gray-300 hover:bg-[#2E6DA4]/50 hover:text-white' }}">
            <svg class="w-5 h-5 mr-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <span>Laporan & Export</span>
        </a>

    </nav>
</div>