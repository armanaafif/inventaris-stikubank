<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Inventaris Stikubank')</title>

    <!-- Favicon Stikubank -->
    <link rel="icon" type="image/webp" href="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp">
    <link rel="shortcut icon" href="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100..900;1,100..900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
        }
        .sidebar-item {
            transition: all 0.3s ease;
        }
        .sidebar-item:hover, .sidebar-item.active {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }
        .profile-dropdown {
            transition: all 0.2s ease;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen bg-gray-100">
        <div id="sidebarOverlay" class="fixed inset-0 z-40 hidden bg-gray-900/50 lg:hidden" onclick="closeSidebar()"></div>
        
        <!-- SIDEBAR (Tanpa Profile di Bawah) -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col bg-white shadow-lg transition-transform duration-300 ease-in-out lg:translate-x-0">
            <div class="flex items-center justify-between gap-3 border-b px-5 py-5 sm:px-6 sm:py-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <img src="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp" class="h-6 w-auto object-contain" alt="Logo Stikubank">
                    </div>
                    <div>
                        <h1 class="font-bold text-gray-800">Inventaris</h1>
                        <p class="text-xs text-gray-400">Stikubank Semarang</p>
                    </div>
                </div>
                <button type="button" onclick="closeSidebar()" class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 hover:bg-gray-100 lg:hidden" aria-label="Tutup menu">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-4 py-6">
                <a href="{{ route('dashboard') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt w-5"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('barang.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 transition-all duration-200 {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                    <i class="fas fa-boxes w-5"></i><span>Barang</span>
                </a>
                <a href="{{ route('stock.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 transition-all duration-200 {{ request()->routeIs('stock.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line w-5"></i><span>Monitoring Stok</span>
                </a>
                <a href="{{ route('history.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 transition-all duration-200 {{ request()->routeIs('history.*') ? 'active' : '' }}">
                    <i class="fas fa-history w-5"></i><span>Histori Transaksi</span>
                </a>
                @if(auth()->user() && auth()->user()->role === 'admin')
                <a href="{{ route('admin.users') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 transition-all duration-200 {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="fas fa-users w-5"></i><span>Manajemen User</span>
                </a>
                <a href="{{ route('admin.requests') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 transition-all duration-200 {{ request()->routeIs('admin.requests*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list w-5"></i><span>Approval Request</span>
                </a>
                <a href="{{ route('admin.borrowings') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 transition-all duration-200 {{ request()->routeIs('admin.borrowings*') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding w-5"></i><span>Peminjaman</span>
                </a>
                @endif
            </nav>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="min-w-0 flex-1 lg:ml-72">
            
            <!-- Top Navbar dengan Profile -->
            <div class="sticky top-0 z-30 flex items-center justify-between gap-3 border-b bg-white px-4 py-3 shadow-sm sm:px-6 lg:px-8 lg:py-4">
                <div class="flex min-w-0 items-center gap-3">
                    <button type="button" onclick="openSidebar()" class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg text-gray-600 hover:bg-gray-100 lg:hidden" aria-label="Buka menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="min-w-0">
                        <h2 class="truncate text-lg font-semibold text-gray-800 sm:text-xl">@yield('page-title', 'Dashboard')</h2>
                        <p class="mt-0.5 truncate text-xs text-gray-500 sm:text-sm">@yield('page-subtitle', 'Selamat datang, ' . (auth()->user()->name ?? 'User'))</p>
                    </div>
                </div>
                
                <!-- Profile Dropdown di Kanan Atas -->
                <div class="relative shrink-0">
                    <button onclick="toggleDropdown()" class="flex items-center gap-3 focus:outline-none group">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role ?? 'User' }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200" id="dropdownArrow"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 hidden z-50">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email ?? 'user@example.com' }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <i class="fas fa-user-circle w-4"></i> My Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                <i class="fas fa-sign-out-alt w-4"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Content -->
            <div class="p-4 sm:p-6 lg:p-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function openSidebar() {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeSidebar() {
            document.getElementById('sidebar').classList.add('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function toggleDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            const arrow = document.getElementById('dropdownArrow');
            dropdown.classList.toggle('hidden');
            arrow.classList.toggle('rotate-180');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const button = event.target.closest('button');
            if (!button || !button.onclick || button.onclick.toString().indexOf('toggleDropdown') === -1) {
                if (!dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                    document.getElementById('dropdownArrow').classList.remove('rotate-180');
                }
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeSidebar();

                const dropdown = document.getElementById('profileDropdown');
                if (!dropdown.classList.contains('hidden')) {
                    dropdown.classList.add('hidden');
                    document.getElementById('dropdownArrow').classList.remove('rotate-180');
                }
            }
        });

        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
    </script>

    <style>
        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>

    @stack('scripts')
</body>
</html>
