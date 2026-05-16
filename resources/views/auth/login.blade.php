<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Administrator - SKP Lampung</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-[#F8F9FA]">
    <div class="min-h-screen flex">
        
        <div class="hidden lg:flex lg:w-1/2 bg-[#1E3A5F] flex-col justify-center items-center p-12 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <svg class="absolute top-0 left-0 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50"></circle></svg>
                <svg class="absolute bottom-0 right-0 transform translate-x-1/3 translate-y-1/3 w-96 h-96" fill="currentColor" viewBox="0 0 100 100"><circle cx="50" cy="50" r="50"></circle></svg>
            </div>

            <div class="relative z-10 text-center">
                <div class="text-7xl mb-6">🌾</div>
                <h1 class="text-4xl font-extrabold tracking-wider mb-4">SKP LAMPUNG</h1>
                <p class="text-lg text-blue-100 max-w-md mx-auto leading-relaxed">
                    Sistem Ketahanan Pangan & Prediksi Ketersediaan Beras Provinsi Lampung Berbasis Machine Learning (ARIMA).
                </p>
                
                <div class="mt-12 flex items-center justify-center space-x-2 text-sm text-blue-200/80">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span>Sistem Terenkripsi & Aman</span>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12">
            <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
                
                <div class="text-center mb-8 lg:hidden">
                    <div class="text-5xl mb-2">🌾</div>
                    <h2 class="text-2xl font-extrabold text-[#1E3A5F] tracking-wider">SKP LAMPUNG</h2>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang 👋</h2>
                    <p class="text-gray-500 text-sm">Silakan masukkan kredensial administrator Anda untuk masuk ke panel kendali.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-5">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                        <input id="email" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2E6DA4] focus:ring-[#2E6DA4] sm:text-sm px-4 py-3 bg-gray-50 text-gray-900" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="admin@lampung.go.id" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-600 text-sm" />
                    </div>

                    <div class="mb-5">
                        <div class="flex justify-between items-center mb-1.5">
                            <label for="password" class="block text-sm font-semibold text-gray-700">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a class="text-xs text-[#2E6DA4] hover:text-[#1E3A5F] hover:underline font-medium transition-colors" href="{{ route('password.request') }}">
                                    Lupa sandi?
                                </a>
                            @endif
                        </div>
                        <input id="password" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-[#2E6DA4] focus:ring-[#2E6DA4] sm:text-sm px-4 py-3 bg-gray-50 text-gray-900" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-600 text-sm" />
                    </div>

                    <div class="block mb-6">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#1E3A5F] shadow-sm focus:ring-[#1E3A5F] w-4 h-4 cursor-pointer" name="remember">
                            <span class="ms-2 text-sm text-gray-600 font-medium">Ingat sesi saya</span>
                        </label>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-lg shadow-md text-sm font-bold text-white bg-[#1E3A5F] hover:bg-[#2E6DA4] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#2E6DA4] transition-all duration-200">
                            Masuk ke Sistem
                        </button>
                    </div>
                </form>
                
                <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                    <a href="{{ url('/') }}" class="text-sm text-gray-500 hover:text-[#1E3A5F] font-medium flex items-center justify-center transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Halaman Publik
                    </a>
                </div>

            </div>
        </div>
        
    </div>
</body>
</html>