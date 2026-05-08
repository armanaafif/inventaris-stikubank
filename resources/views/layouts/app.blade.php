<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Judul Website -->
    <title>Inventaris</title>

    <!-- Asset CSS dan JS dari Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-white shadow p-4 flex justify-between items-center">

        <!-- Menu Navigasi -->
        <div class="flex gap-4">

            <a 
                href="/dashboard"
                class="font-semibold hover:text-blue-500"
            >
                Dashboard
            </a>

            <a 
                href="/barang"
                class="hover:text-blue-500"
            >
                Barang
            </a>

            <a 
                href="/admin/requests"
                class="hover:text-blue-500"
            >
                Request
            </a>

        </div>

        <!-- Tombol Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded"
            >
                Logout
            </button>
        </form>

    </nav>

    <!-- Konten Utama -->
    <main class="p-6">

        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Notifikasi Error -->
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Isi Halaman -->
        @yield('content')

    </main>

</body>
</html>