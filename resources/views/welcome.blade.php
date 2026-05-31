<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Inventaris Stikubank - Sistem Manajemen Inventaris</title>

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
        
        .hero-gradient {
            background: linear-gradient(135deg, #3b82f6 0%, #1e3a5f 100%);
        }
        
        .feature-card {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.1);
        }
        
        .btn-login {
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.4);
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Navbar Landing Page -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                
                <!-- Logo & Brand -->
                <div class="flex items-center gap-3">
                    <img src="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp" 
                         alt="Logo UNISBANK" 
                         class="h-8 w-auto object-contain">
                    <span class="text-gray-700 font-semibold text-sm hidden sm:inline">Inventaris Stikubank</span>
                </div>

                <!-- Tombol Login di Navbar -->
                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2 rounded-lg text-sm transition">
                        Login
                    </a>
                    <a href="{{ route('register') }}" 
                       class="border border-blue-600 text-blue-600 hover:bg-blue-50 font-medium px-5 py-2 rounded-lg text-sm transition">
                        Daftar
                    </a>
                </div>

            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-gradient">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
            <div class="text-center max-w-3xl mx-auto">
                
                <!-- Logo Besar -->
                <div class="flex justify-center mb-6">
                    <img src="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp" 
                         alt="Logo UNISBANK" 
                         class="h-20 w-auto object-contain bg-white/10 rounded-2xl p-3">
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                    Inventaris <span class="text-blue-200">Stikubank</span>
                </h1>
                
                <p class="text-blue-100 text-lg mb-2">
                    Universitas Stikubank (UNISBANK) Semarang
                </p>
                
                <div class="w-20 h-1 bg-blue-300 rounded-full mx-auto my-5"></div>
                
                <p class="text-blue-100 leading-relaxed text-base max-w-2xl mx-auto mb-8">
                    Sistem manajemen inventaris modern untuk mengelola barang, stok, dan transaksi secara efisien dan terintegrasi.
                </p>
                
                <div class="flex flex-wrap gap-4 justify-center">
                    <a href="{{ route('login') }}" 
                       class="btn-login bg-white text-blue-600 hover:bg-gray-100 font-semibold px-8 py-3 rounded-xl transition shadow-lg inline-flex items-center gap-2">
                        <i class="fas fa-sign-in-alt"></i>
                        Mulai Sekarang
                    </a>
                    <a href="#fitur" 
                       class="border-2 border-white text-white hover:bg-white/10 font-semibold px-8 py-3 rounded-xl transition inline-flex items-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        Pelajari Lebih
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Section -->
    <section id="fitur" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-800">Fitur Unggulan</h2>
                <div class="w-16 h-1 bg-blue-600 rounded-full mx-auto my-3"></div>
                <p class="text-gray-500 max-w-2xl mx-auto">
                    Solusi lengkap pengelolaan inventaris untuk lingkungan kampus yang lebih efisien
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Fitur 1 -->
                <div class="feature-card bg-gray-50 rounded-xl p-6 text-center border border-gray-100 hover:shadow-lg transition">
                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-database text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Manajemen Barang</h3>
                    <p class="text-sm text-gray-500">Catat dan kelola semua data barang inventaris dengan rapi.</p>
                </div>
                
                <!-- Fitur 2 -->
                <div class="feature-card bg-gray-50 rounded-xl p-6 text-center border border-gray-100 hover:shadow-lg transition">
                    <div class="w-16 h-16 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Monitoring Stok</h3>
                    <p class="text-sm text-gray-500">Pantau stok real-time dan peringatan stok menipis.</p>
                </div>
                
                <!-- Fitur 3 -->
                <div class="feature-card bg-gray-50 rounded-xl p-6 text-center border border-gray-100 hover:shadow-lg transition">
                    <div class="w-16 h-16 bg-yellow-100 text-yellow-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-history text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Histori Transaksi</h3>
                    <p class="text-sm text-gray-500">Lacak riwayat barang masuk dan keluar secara detail.</p>
                </div>
                
                <!-- Fitur 4 -->
                <div class="feature-card bg-gray-50 rounded-xl p-6 text-center border border-gray-100 hover:shadow-lg transition">
                    <div class="w-16 h-16 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-pie text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Analisis & Grafik</h3>
                    <p class="text-sm text-gray-500">Visualisasi data dengan grafik tren barang.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-2xl font-bold mb-3">Siap Mengelola Inventaris Kampus?</h2>
            <p class="text-gray-300 mb-6 max-w-2xl mx-auto">
                Bergabunglah dan rasakan kemudahan sistem inventaris kami.
            </p>
            <a href="{{ route('register') }}" 
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-xl transition">
                <i class="fas fa-user-plus"></i>
                Daftar Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <img src="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp" 
                         alt="Logo UNISBANK" 
                         class="h-6 w-auto object-contain">
                    <p class="text-xs text-gray-400">© {{ date('Y') }} Inventaris Stikubank - UNISBANK Semarang</p>
                </div>
                <div class="flex gap-4 text-gray-400">
                    <a href="#" class="hover:text-blue-600 transition"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="hover:text-blue-600 transition"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover:text-blue-600 transition"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>