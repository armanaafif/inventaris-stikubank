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
                Ringkasan stok, transaksi, dan aktivitas inventaris
            </p>

        </div>

        <!-- Action -->
        <div class="flex flex-wrap gap-3">

            <a
                href="/barang/create"
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl transition shadow-sm"
            >
                + Tambah Barang
            </a>

        </div>

    </div>

    <!-- Insight Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">

        <!-- Total Barang -->
        <a
            href="/barang"
            class="group bg-white border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition"
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

            <div class="mt-5 text-sm font-medium text-blue-600 group-hover:translate-x-1 transition">
                Kelola Barang →
            </div>

        </a>

        <!-- Total Stok -->
        <a
            href="/stock"
            class="group bg-white border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition"
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
                            d="M5 8h14M5 12h14M5 16h14" />

                    </svg>

                </div>

            </div>

            <div class="mt-5 text-sm font-medium text-green-600 group-hover:translate-x-1 transition">
                Monitoring Stok →
            </div>

        </a>

        <!-- Histori -->
        <a
            href="/history"
            class="group bg-white border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition"
        >

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Histori Transaksi
                    </p>

                    <h2 class="text-4xl font-bold text-gray-800 mt-3">
                        {{ $totalTransaksi ?? 0 }}
                    </h2>

                </div>

                <div class="bg-purple-100 text-purple-700 p-4 rounded-2xl">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10m-11 8h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v10a2 2 0 002 2z" />

                    </svg>

                </div>

            </div>

            <div class="mt-5 text-sm font-medium text-purple-600 group-hover:translate-x-1 transition">
                Lihat Histori →
            </div>

        </a>

        <!-- Pending Request -->
        <a
            href="/admin/requests"
            class="group bg-white border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition"
        >

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Pending Request
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

            <div class="mt-5 text-sm font-medium text-yellow-600 group-hover:translate-x-1 transition">
                Approval Request →
            </div>

        </a>

    </div>

    <!-- Shortcut Menu -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

        <div class="border-b px-6 py-5">

            <h2 class="text-xl font-semibold text-gray-800">
                Menu Cepat
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Akses fitur utama inventaris
            </p>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-3">

            <!-- Barang -->
            <a
                href="/barang"
                class="p-6 border-b md:border-b-0 md:border-r hover:bg-gray-50 transition"
            >

                <h3 class="font-semibold text-gray-800 mb-2">
                    Kelola Barang
                </h3>

                <p class="text-sm text-gray-500 leading-relaxed">
                    Lihat, tambah, dan kelola seluruh data barang inventaris.
                </p>

            </a>

            <!-- Stock -->
            <a
                href="/stock"
                class="p-6 border-b md:border-b-0 md:border-r hover:bg-gray-50 transition"
            >

                <h3 class="font-semibold text-gray-800 mb-2">
                    Monitoring Stok
                </h3>

                <p class="text-sm text-gray-500 leading-relaxed">
                    Pantau stok aman, menipis, atau habis secara realtime.
                </p>

            </a>

            <!-- History -->
            <a
                href="/history"
                class="p-6 hover:bg-gray-50 transition"
            >

                <h3 class="font-semibold text-gray-800 mb-2">
                    Histori Transaksi
                </h3>

                <p class="text-sm text-gray-500 leading-relaxed">
                    Analisa aktivitas barang masuk dan keluar.
                </p>

            </a>

        </div>

    </div>

</div>

@endsection