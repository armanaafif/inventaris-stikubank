<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Inventaris Stikubank')</title>

    <link rel="icon" type="image/webp" href="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp">
    <link rel="shortcut icon" href="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
        }

        .sidebar-item {
            transition: all .3s ease;
        }

        .sidebar-item:hover,
        .sidebar-item.active {
            background: linear-gradient(135deg,#3b82f6 0%,#2563eb 100%);
            color: white;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-100">

<!-- Overlay Mobile -->
<div id="sidebarOverlay"
     onclick="toggleSidebar()"
     class="hidden fixed inset-0 bg-black/50 z-40 lg:hidden">
</div>

<div class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-50
                  w-72 bg-white shadow-lg flex flex-col
                  transform -translate-x-full
                  lg:translate-x-0
                  transition-transform duration-300">

        <div class="flex items-center gap-3 px-6 py-6 border-b">

            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                <img src="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp"
                     class="h-6 w-auto object-contain">
            </div>

            <div>
                <h1 class="font-bold text-gray-800">
                    Inventaris
                </h1>

                <p class="text-xs text-gray-400">
                    Stikubank Semarang
                </p>
            </div>

        </div>

        <nav class="flex-1 px-4 py-6 space-y-1">

            <a href="{{ route('dashboard') }}"
               onclick="closeSidebarMobile()"
               class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt w-5"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('barang.index') }}"
               onclick="closeSidebarMobile()"
               class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 {{ request()->routeIs('barang.*') ? 'active' : '' }}">
                <i class="fas fa-boxes w-5"></i>
                <span>Barang</span>
            </a>

            <a href="{{ route('stock.index') }}"
               onclick="closeSidebarMobile()"
               class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 {{ request()->routeIs('stock.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line w-5"></i>
                <span>Monitoring Stok</span>
            </a>

            <a href="{{ route('history.index') }}"
               onclick="closeSidebarMobile()"
               class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 {{ request()->routeIs('history.*') ? 'active' : '' }}">
                <i class="fas fa-history w-5"></i>
                <span>Histori Transaksi</span>
            </a>

            @if(auth()->user() && auth()->user()->role === 'admin')

            <a href="{{ route('admin.users') }}"
               onclick="closeSidebarMobile()"
               class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="fas fa-users w-5"></i>
                <span>Manajemen User</span>
            </a>

            <a href="{{ route('admin.requests') }}"
               onclick="closeSidebarMobile()"
               class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 {{ request()->routeIs('admin.requests*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list w-5"></i>
                <span>Approval Request</span>
            </a>

            <a href="{{ route('admin.borrowings') }}"
               onclick="closeSidebarMobile()"
               class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 {{ request()->routeIs('admin.borrowings*') ? 'active' : '' }}">
                <i class="fas fa-hand-holding w-5"></i>
                <span>Peminjaman</span>
            </a>

            @endif

        </nav>
    </aside>

    <!-- Main -->
    <main class="flex-1 overflow-y-auto lg:ml-72">

        <!-- Navbar -->
        <div class="bg-white shadow-sm sticky top-0 z-30 border-b px-4 lg:px-8 py-4">

            <div class="flex justify-between items-center">

                <div class="flex items-center gap-3">

                    <button onclick="toggleSidebar()"
                            class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div>
                        <h2 class="text-lg lg:text-xl font-semibold text-gray-800">
                            @yield('page-title', 'Dashboard')
                        </h2>

                        <p class="text-xs lg:text-sm text-gray-500">
                            @yield('page-subtitle', 'Selamat datang')
                        </p>
                    </div>

                </div>

                <!-- Profile -->
                <div class="relative">

                    <button onclick="toggleDropdown()"
                            class="flex items-center gap-3">

                        <div class="hidden sm:block text-right">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-xs text-gray-400 capitalize">
                                {{ auth()->user()->role }}
                            </p>
                        </div>

                        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U',0,1)) }}
                        </div>

                        <i id="dropdownArrow"
                           class="fas fa-chevron-down text-xs text-gray-400">
                        </i>

                    </button>

                    <div id="profileDropdown"
                         class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border">

                        <div class="px-4 py-3 border-b">
                            <p class="text-sm font-semibold">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-xs text-gray-500">
                                {{ auth()->user()->email }}
                            </p>
                        </div>

                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50">
                            <i class="fas fa-user-circle"></i>
                            My Profile
                        </a>

                        <form method="POST"
                              action="{{ route('logout') }}">
                            @csrf

                            <button type="submit"
                                    class="w-full text-left flex items-center gap-3 px-4 py-2 text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </button>
                        </form>

                    </div>

                </div>

            </div>

        </div>

        <!-- Content -->
        <div class="p-4 lg:p-8">
            @yield('content')
        </div>

    </main>

</div>

<script>

function toggleSidebar()
{
    document.getElementById('sidebar')
        .classList.toggle('-translate-x-full');

    document.getElementById('sidebarOverlay')
        .classList.toggle('hidden');
}

function closeSidebarMobile()
{
    if(window.innerWidth < 1024)
    {
        document.getElementById('sidebar')
            .classList.add('-translate-x-full');

        document.getElementById('sidebarOverlay')
            .classList.add('hidden');
    }
}

function toggleDropdown()
{
    document.getElementById('profileDropdown')
        .classList.toggle('hidden');

    document.getElementById('dropdownArrow')
        .classList.toggle('rotate-180');
}

document.addEventListener('click', function(event)
{
    const dropdown =
        document.getElementById('profileDropdown');

    const trigger =
        event.target.closest('button');

    if (
        !trigger ||
        !trigger.getAttribute('onclick') ||
        !trigger.getAttribute('onclick').includes('toggleDropdown')
    ) {
        dropdown.classList.add('hidden');

        document
            .getElementById('dropdownArrow')
            .classList.remove('rotate-180');
    }
});

</script>

@stack('scripts')

</body>
</html>