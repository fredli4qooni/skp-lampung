<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-[#F8F9FA]">
    <div class="min-h-screen flex">

        @include('layouts.navigation')

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <header class="bg-white border-b border-gray-200 z-10">
                <div class="mx-auto py-4 px-6 flex justify-between items-center">
                    <div>
                        @if (isset($header))
                        {{ $header }}
                        @endif
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="flex items-center text-sm text-gray-700 font-medium">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ Auth::user()->name }}
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-red-600 hover:text-red-900 font-semibold bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-md transition-colors flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                </svg>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 relative z-0 overflow-y-auto focus:outline-none p-6">
                {{ $slot }}
            </main>

            <footer class="bg-white border-t border-gray-200 py-3 text-center text-xs text-gray-500">
                Dashboard Ketahanan Pangan Provinsi Lampung &copy; 2026
            </footer>
        </div>
    </div>
</body>

</html>