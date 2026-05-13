<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Title -->
    <title>Inventaris Stikubank</title>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-100 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">

        <div class="max-w-7xl mx-auto px-6">

            <div class="flex items-center justify-between h-16">

                <!-- Left -->
                <div class="flex items-center gap-10">

                    <!-- Logo -->
                    <a
                        href="/dashboard"
                        class="text-xl font-bold text-blue-600"
                    >
                        Inventaris Stikubank
                    </a>

                    <!-- Navigation -->
                    <div class="hidden md:flex items-center gap-6 text-sm font-medium">

                        <a
                            href="/dashboard"
                            class="hover:text-blue-600 transition"
                        >
                            Dashboard
                        </a>

                        <a
                            href="/barang"
                            class="hover:text-blue-600 transition"
                        >
                            Barang
                        </a>

                        <a
                            href="/barang/create"
                            class="hover:text-blue-600 transition"
                        >
                            Tambah Barang
                        </a>

                        <a
                            href="/stock"
                            class="hover:text-blue-600 transition"
                        >
                            Stok
                        </a>

                        <a
                            href="/admin/requests"
                            class="hover:text-blue-600 transition"
                        >
                            Request
                        </a>

                    </div>

                </div>

                <!-- Right -->
                <div class="flex items-center gap-4">

                    <!-- User Info -->
                    <div class="text-right hidden sm:block">

                        <p class="text-sm font-semibold text-gray-800">
                            {{ auth()->user()->name ?? 'User' }}
                        </p>

                        <p class="text-xs text-gray-500 capitalize">
                            {{ auth()->user()->role ?? 'User' }}
                        </p>

                    </div>

                    <!-- User Avatar -->
                    <div class="w-11 h-11 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-sm">

                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}

                    </div>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('logout') }}">

                        @csrf

                        <button
                            type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-xl transition"
                        >
                            Logout
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-6 py-8">

        <!-- Success Message -->
        @if(session('success'))

            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">

                {{ session('success') }}

            </div>

        @endif

        <!-- Error Message -->
        @if(session('error'))

            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">

                {{ session('error') }}

            </div>

        @endif

        <!-- Page Content -->
        @yield('content')

    </main>

</body>
</html>