<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Inventaris Stikubank - Sistem Manajemen Inventaris</title>

    <!-- Vite + Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100..900;1,100..900&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }
        
        .bg-gradient-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
        }
        
        .btn-login {
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.4);
        }
        
        .logo-wrapper {
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .feature-item {
            transition: all 0.3s ease;
        }
        
        .feature-item:hover {
            transform: translateX(5px);
            color: #3b82f6;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-50 min-h-screen">

    <div class="min-h-screen flex items-center justify-center px-4 py-12">
        
        <!-- Main Container -->
        <div class="max-w-6xl w-full mx-auto">
            
            <!-- Grid 2 Kolom: Logo/Info + Form Login -->
            <div class="grid md:grid-cols-2 gap-8 items-center">
                
                <!-- Left Side: Logo & Info -->
                <div class="text-center md:text-left logo-wrapper">
                    <!-- Logo -->
                    <div class="flex justify-center md:justify-start mb-6">
                        <div class="bg-white rounded-2xl shadow-lg p-3 inline-block">
                            <img src="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp" 
                                 alt="Logo Universitas Stikubank" 
                                 class="h-16 w-auto object-contain">
                        </div>
                    </div>
                    
                    <!-- Title -->
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-3">
                        Inventaris <span class="text-blue-600">Stikubank</span>
                    </h1>
                    
                    <p class="text-gray-500 text-lg mb-2">
                        Universitas Stikubank (UNISBANK) Semarang
                    </p>
                    
                    <div class="w-20 h-1 bg-blue-600 rounded-full mx-auto md:mx-0 my-5"></div>
                    
                    <p class="text-gray-500 leading-relaxed max-w-md mx-auto md:mx-0">
                        Sistem manajemen inventaris modern untuk mengelola barang, stok, dan transaksi secara efisien dan terintegrasi.
                    </p>
                    
                    <!-- Feature List -->
                    <div class="mt-8 space-y-3">
                        <div class="feature-item flex items-center gap-3 text-gray-600 justify-center md:justify-start">
                            <i class="fas fa-check-circle text-green-500 text-sm"></i>
                            <span class="text-sm">Manajemen Barang Terpusat</span>
                        </div>
                        <div class="feature-item flex items-center gap-3 text-gray-600 justify-center md:justify-start">
                            <i class="fas fa-check-circle text-green-500 text-sm"></i>
                            <span class="text-sm">Monitoring Stok Real-time</span>
                        </div>
                        <div class="feature-item flex items-center gap-3 text-gray-600 justify-center md:justify-start">
                            <i class="fas fa-check-circle text-green-500 text-sm"></i>
                            <span class="text-sm">Histori Transaksi Lengkap</span>
                        </div>
                        <div class="feature-item flex items-center gap-3 text-gray-600 justify-center md:justify-start">
                            <i class="fas fa-check-circle text-green-500 text-sm"></i>
                            <span class="text-sm">Analisis & Grafik Interaktif</span>
                        </div>
                    </div>
                </div>
                
                <!-- Right Side: Login Card -->
                <div>
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        
                        <!-- Card Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-3">
                                <i class="fas fa-lock text-white text-2xl"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-white">Selamat Datang</h2>
                            <p class="text-blue-100 text-sm mt-1">Silakan login untuk mengakses sistem</p>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="px-8 py-8">
                            <!-- Tombol Login -->
                            <a href="{{ route('login') }}" 
                               class="btn-login block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition text-center flex items-center justify-center gap-2">
                                <i class="fas fa-sign-in-alt"></i>
                                Login ke Akun
                            </a>
                            
                            <!-- Divider -->
                            <div class="relative my-6">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-200"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-3 bg-white text-gray-400">atau</span>
                                </div>
                            </div>
                            
                            <!-- Link Register -->
                            <p class="text-center text-sm text-gray-600">
                                Belum punya akun? 
                                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline transition">
                                    Daftar Sekarang
                                </a>
                            </p>
                        </div>
                        
                        <!-- Card Footer -->
                        <div class="bg-gray-50 px-8 py-4 text-center text-xs text-gray-400 border-t border-gray-100">
                            <i class="fas fa-shield-alt mr-1"></i> Sistem aman dan terenkripsi
                        </div>
                        
                    </div>
                    
                    <!-- Info Bantuan -->
                    <div class="text-center mt-6">
                        <p class="text-xs text-gray-400">
                            <i class="fas fa-headset mr-1"></i> Butuh bantuan? 
                            <a href="#" class="text-blue-500 hover:underline">Hubungi Admin</a>
                        </p>
                    </div>
                </div>
                
            </div>
            
            <!-- Footer -->
            <div class="text-center mt-12 pt-6 border-t border-gray-200">
                <p class="text-xs text-gray-400">
                    © {{ date('Y') }} Inventaris Stikubank - Universitas Stikubank (UNISBANK) Semarang
                </p>
            </div>
            
        </div>
        
    </div>

</body>
</html>