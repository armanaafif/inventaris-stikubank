@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Dashboard Inventaris
            </h1>

            <p class="text-gray-500 mt-2">
                Monitoring data inventaris, stok barang, dan aktivitas sistem
            </p>

        </div>

        <!-- Navigasi Cepat -->
        <div class="flex flex-wrap gap-3">

            <a
                href="/barang/create"
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl transition shadow-sm"
            >
                Tambah Barang
            </a>

            <a
                href="/barang"
                class="inline-flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium px-5 py-3 rounded-xl transition"
            >
                Kelola Barang
            </a>

            <a
                href="/stock"
                class="inline-flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium px-5 py-3 rounded-xl transition"
            >
                Monitoring Stok
            </a>

        </div>

    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">

        <!-- Total Barang -->
        <a
            href="/barang"
            class="group bg-white border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition duration-200"
        >

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Total Barang
                    </p>

                    <h2 class="text-4xl font-bold text-gray-800 mt-3">
                        {{ $totalBarang ?? 0 }}
                    </h2>

                </div>

                <div class="bg-blue-100 text-blue-700 p-4 rounded-2xl">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 13V7a2 2 0 00-2-2h-3V3H9v2H6a2 2 0 00-2 2v6m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4" />

                    </svg>

                </div>

            </div>

            <p class="text-sm text-gray-400 mt-5">
                Total jenis barang inventaris
            </p>

            <div class="mt-5 text-sm font-medium text-blue-600 group-hover:translate-x-1 transition">
                Lihat Barang →
            </div>

        </a>

        <!-- Total Stock -->
        <a
            href="/stock"
            class="group bg-white border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition duration-200"
        >

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Total Stok
                    </p>

                    <h2 class="text-4xl font-bold text-gray-800 mt-3">
                        {{ $totalStock ?? 0 }}
                    </h2>

                </div>

                <div class="bg-green-100 text-green-700 p-4 rounded-2xl">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V13z" />

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 3v4M8 3v4M4 11h16" />

                    </svg>

                </div>

            </div>

            <p class="text-sm text-gray-400 mt-5">
                Total seluruh stok aktif
            </p>

            <div class="mt-5 text-sm font-medium text-green-600 group-hover:translate-x-1 transition">
                Monitoring Stok →
            </div>

        </a>

        <!-- Pending Request -->
        <a
            href="/admin/requests"
            class="group bg-white border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition duration-200"
        >

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Request Pending
                    </p>

                    <h2 class="text-4xl font-bold text-gray-800 mt-3">
                        {{ $pendingRequest ?? 0 }}
                    </h2>

                </div>

                <div class="bg-yellow-100 text-yellow-700 p-4 rounded-2xl">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />

                    </svg>

                </div>

            </div>

            <p class="text-sm text-gray-400 mt-5">
                Menunggu approval admin
            </p>

            <div class="mt-5 text-sm font-medium text-yellow-600 group-hover:translate-x-1 transition">
                Lihat Request →
            </div>

        </a>

    </div>

    <!-- Quick Access -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

        <!-- Tambah Barang -->
        <a
            href="/barang/create"
            class="bg-blue-600 hover:bg-blue-700 text-white rounded-2xl shadow-sm p-6 transition hover:shadow-lg"
        >

            <div class="flex items-center justify-between mb-5">

                <div class="bg-white/20 p-4 rounded-2xl">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4" />

                    </svg>

                </div>

                <span class="text-sm text-blue-100">
                    Aksi Cepat
                </span>

            </div>

            <h3 class="text-lg font-semibold mb-2">
                Tambah Barang
            </h3>

            <p class="text-sm text-blue-100 leading-relaxed">
                Menambahkan barang inventaris baru beserta stok awal.
            </p>

        </a>

        <!-- Kelola Barang -->
        <a
            href="/barang"
            class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-lg transition"
        >

            <div class="flex items-center justify-between mb-5">

                <div class="bg-blue-100 text-blue-700 p-4 rounded-2xl">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />

                    </svg>

                </div>

                <span class="text-sm text-gray-400">
                    Menu
                </span>

            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Kelola Barang
            </h3>

            <p class="text-sm text-gray-500 leading-relaxed">
                Melihat daftar barang dan histori transaksi inventaris.
            </p>

        </a>

        <!-- Monitoring -->
        <a
            href="/stock"
            class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-lg transition"
        >

            <div class="flex items-center justify-between mb-5">

                <div class="bg-green-100 text-green-700 p-4 rounded-2xl">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 8h14M5 12h14M5 16h14" />

                        <circle cx="7" cy="8" r="1" fill="currentColor"/>
                        <circle cx="7" cy="12" r="1" fill="currentColor"/>
                        <circle cx="7" cy="16" r="1" fill="currentColor"/>

                    </svg>

                </div>

                <span class="text-sm text-gray-400">
                    Menu
                </span>

            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Monitoring Stok
            </h3>

            <p class="text-sm text-gray-500 leading-relaxed">
                Memantau kondisi stok barang secara realtime.
            </p>

        </a>

        <!-- Approval -->
        <a
            href="/admin/requests"
            class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-lg transition"
        >

            <div class="flex items-center justify-between mb-5">

                <div class="bg-yellow-100 text-yellow-700 p-4 rounded-2xl">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-3-3v6m9-3a9 9 0 11-18 0 9 9 0 0118 0z" />

                    </svg>

                </div>

                <span class="text-sm text-gray-400">
                    Admin
                </span>

            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Approval Request
            </h3>

            <p class="text-sm text-gray-500 leading-relaxed">
                Approval request barang masuk dan keluar.
            </p>

        </a>

    </div>

    <!-- Informasi Sistem -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

        <h2 class="text-xl font-semibold text-gray-800 mb-3">
            Informasi Sistem
        </h2>

        <p class="text-gray-600 leading-relaxed">
            Sistem inventaris digunakan untuk mengelola data barang,
            monitoring stok, serta mencatat histori barang masuk dan keluar.
            Seluruh perubahan stok tersimpan dalam histori transaksi untuk
            menjaga konsistensi data inventaris.
        </p>

    </div>

</div>

@endsection