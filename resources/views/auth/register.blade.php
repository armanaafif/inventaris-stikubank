<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register - Inventaris Stikubank</title>

    <!-- Favicon - Logo Stikubank -->
    <link rel="icon" type="image/webp" href="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp">
    <link rel="shortcut icon" href="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp">
    <link rel="apple-touch-icon" href="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp">

    <!-- Vite + Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100..900;1,100..900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .btn-register {
            transition: all 0.3s ease;
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.4);
        }
        
        .input-field {
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            border-color: #3b82f6;
            ring: 2px solid #3b82f6;
        }
        
        .left-section {
            animation: fadeInLeft 0.6s ease-out;
        }
        
        .right-section {
            animation: fadeInRight 0.6s ease-out;
        }
        
        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-50 min-h-screen">

    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        
        <!-- Main Container -->
        <div class="max-w-6xl w-full mx-auto">
            
            <!-- Grid 2 Kolom dengan align items-start -->
            <div class="grid md:grid-cols-2 gap-12 items-start">
                
                <!-- Left Side: Logo UNISBANK & Info -->
                <div class="left-section text-center md:text-left pr-0 md:pr-8 pt-0">
                    
                    <!-- Logo Lingkaran -->
                    <div class="flex justify-center md:justify-start mb-6">
                        <div class="w-20 h-20 rounded-full bg-blue-50 flex items-center justify-center shadow-sm">
                            <img src="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp" 
                                 alt="Logo Universitas Stikubank UNISBANK Semarang" 
                                 class="h-12 w-auto object-contain">
                        </div>
                    </div>
                    
                    <!-- Title -->
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                        Inventaris <span class="text-blue-600">Stikubank</span>
                    </h1>
                    
                    <p class="text-gray-500 text-sm mb-1">
                        Universitas Stikubank (UNISBANK) Semarang
                    </p>
                    
                    <div class="w-12 h-0.5 bg-blue-600 rounded-full mx-auto md:mx-0 my-5"></div>
                    
                    <p class="text-gray-500 leading-relaxed max-w-md mx-auto md:mx-0 text-sm">
                        Daftarkan akun Anda untuk mengakses sistem manajemen inventaris.
                    </p>
                    
                    <!-- Feature List -->
                    <div class="mt-8 space-y-2">
                        <div class="flex items-center gap-3 text-gray-600 justify-center md:justify-start text-sm">
                            <i class="fas fa-check-circle text-green-500 text-xs"></i>
                            <span>Manajemen Barang Terpusat</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-600 justify-center md:justify-start text-sm">
                            <i class="fas fa-check-circle text-green-500 text-xs"></i>
                            <span>Monitoring Stok Real-time</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-600 justify-center md:justify-start text-sm">
                            <i class="fas fa-check-circle text-green-500 text-xs"></i>
                            <span>Histori Transaksi Lengkap</span>
                        </div>
                        <div class="flex items-center gap-3 text-gray-600 justify-center md:justify-start text-sm">
                            <i class="fas fa-check-circle text-green-500 text-xs"></i>
                            <span>Analisis & Grafik Interaktif</span>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side: Register Form -->
                <div class="right-section">
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-5 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-white/20 rounded-full mb-2">
                                <i class="fas fa-user-plus text-white text-lg"></i>
                            </div>
                            <h2 class="text-lg font-bold text-white">Daftar Akun Baru</h2>
                            <p class="text-blue-100 text-xs mt-1">Isi form berikut untuk mendaftar</p>
                        </div>
                        
                        <!-- Tampilkan Session Status -->
                        @if (session('status'))
                            <div class="mx-6 mt-4">
                                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-2 rounded-lg text-xs">
                                    <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                                </div>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mx-6 mt-4">
                                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-2 rounded-lg text-xs">
                                    <i class="fas fa-exclamation-circle mr-2"></i> 
                                    @foreach ($errors->all() as $error)
                                        {{ $error }}<br>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Form Register -->
                        <form method="POST" action="{{ route('register') }}" class="px-6 py-6">
                            @csrf

                            <!-- Name -->
                            <div class="mb-4">
                                <label for="name" class="block text-xs font-medium text-gray-700 mb-1">
                                    <i class="fas fa-user mr-1 text-gray-400 text-xs"></i>Nama Lengkap
                                </label>
                                <input id="name" 
                                       type="text" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required 
                                       autofocus 
                                       autocomplete="name"
                                       placeholder="Contoh: Ahmad Fauzi"
                                       class="input-field w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none">
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="block text-xs font-medium text-gray-700 mb-1">
                                    <i class="fas fa-envelope mr-1 text-gray-400 text-xs"></i>Alamat Email
                                </label>
                                <input id="email" 
                                       type="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required 
                                       autocomplete="username"
                                       placeholder="contoh: ahmad@stikubank.ac.id"
                                       class="input-field w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none">
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <label for="password" class="block text-xs font-medium text-gray-700 mb-1">
                                    <i class="fas fa-lock mr-1 text-gray-400 text-xs"></i>Password
                                </label>
                                <input id="password" 
                                       type="password" 
                                       name="password" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Minimal 6 karakter"
                                       class="input-field w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none">
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-5">
                                <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1">
                                    <i class="fas fa-lock mr-1 text-gray-400 text-xs"></i>Konfirmasi Password
                                </label>
                                <input id="password_confirmation" 
                                       type="password" 
                                       name="password_confirmation" 
                                       required 
                                       autocomplete="new-password"
                                       placeholder="Ulangi password"
                                       class="input-field w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none">
                            </div>

                            <!-- Tombol Register -->
                            <button type="submit" 
                                    class="btn-register w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-lg transition flex items-center justify-center gap-2 text-sm">
                                <i class="fas fa-user-plus text-xs"></i>
                                Daftar
                            </button>
                            
                            <!-- Divider -->
                            <div class="relative my-5">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-100"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-3 bg-white text-gray-400 text-xs">atau</span>
                                </div>
                            </div>
                            
                            <!-- Link Login -->
                            <p class="text-center text-xs text-gray-600">
                                Sudah punya akun? 
                                <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline transition">
                                    Login Sekarang
                                </a>
                            </p>
                        </form>
                        
                        <!-- Card Footer -->
                        <div class="bg-gray-50 px-6 py-3 text-center text-xs text-gray-400 border-t border-gray-100">
                            <i class="fas fa-shield-alt mr-1"></i> Data Anda aman dan terenkripsi
                        </div>
                    </div>
                    
                    <!-- Back to Home -->
                    <div class="text-center mt-5">
                        <a href="{{ url('/') }}" class="text-xs text-gray-400 hover:text-blue-600 transition inline-flex items-center gap-1">
                            <i class="fas fa-arrow-left text-xs"></i>
                            Kembali ke Beranda
                        </a>
                    </div>
                </div>
                
            </div>
            
            <!-- Footer -->
            <div class="text-center mt-10 pt-5 border-t border-gray-200">
                <p class="text-xs text-gray-400">
                    © {{ date('Y') }} Inventaris Stikubank - Universitas Stikubank (UNISBANK) Semarang
                </p>
            </div>
            
        </div>
        
    </div>

</body>
</html>