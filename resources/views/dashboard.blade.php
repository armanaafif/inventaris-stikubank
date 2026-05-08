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
                Monitoring data inventaris, stok barang, dan request approval
            </p>

        </div>

        <!-- Navigasi -->
        <div class="flex flex-wrap gap-3">

            <a
                href="/barang"
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl transition"
            >
                Kelola Barang
            </a>

            <a
                href="/stock"
                class="inline-flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium px-5 py-3 rounded-xl transition"
            >
                Monitoring Stok
            </a>

            <a
                href="/admin/requests"
                class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-medium px-5 py-3 rounded-xl transition"
            >
                Approval Request
            </a>

        </div>

    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">

        <!-- Total Barang -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Total Barang
                    </p>

                    <h2 class="text-4xl font-bold text-gray-800 mt-3">
                        {{ $totalBarang ?? 0 }}
                    </h2>

                </div>

                <div class="bg-blue-100 text-blue-700 p-4 rounded-2xl text-xl">
                    📦
                </div>

            </div>

            <p class="text-sm text-gray-400 mt-5">
                Total jenis barang inventaris
            </p>

        </div>

        <!-- Total Stok -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Total Stok
                    </p>

                    <h2 class="text-4xl font-bold text-gray-800 mt-3">
                        {{ $totalStock ?? 0 }}
                    </h2>

                </div>

                <div class="bg-green-100 text-green-700 p-4 rounded-2xl text-xl">
                    📊
                </div>

            </div>

            <p class="text-sm text-gray-400 mt-5">
                Total seluruh stok aktif
            </p>

        </div>

        <!-- Pending Request -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Request Pending
                    </p>

                    <h2 class="text-4xl font-bold text-gray-800 mt-3">
                        {{ $pendingRequest ?? 0 }}
                    </h2>

                </div>

                <div class="bg-yellow-100 text-yellow-700 p-4 rounded-2xl text-xl">
                    ⏳
                </div>

            </div>

            <p class="text-sm text-gray-400 mt-5">
                Menunggu approval admin
            </p>

        </div>

    </div>

    <!-- Informasi Sistem -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 mb-8">

        <h2 class="text-xl font-semibold text-gray-800 mb-3">
            Informasi Sistem
        </h2>

        <p class="text-gray-600 leading-relaxed">
            Sistem inventaris digunakan untuk mengelola data barang,
            monitoring stok, serta mencatat histori barang masuk dan keluar.
            Gunakan menu navigasi untuk mengakses setiap fitur inventaris.
        </p>

    </div>

    <!-- Shortcut -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Barang -->
        <a
            href="/barang"
            class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-md transition"
        >

            <div class="flex items-center justify-between mb-4">

                <div class="bg-blue-100 text-blue-700 p-4 rounded-2xl text-xl">
                    📁
                </div>

                <span class="text-sm text-gray-400">
                    Menu
                </span>

            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Kelola Barang
            </h3>

            <p class="text-sm text-gray-500 leading-relaxed">
                Melihat daftar barang dan detail histori transaksi.
            </p>

        </a>

        <!-- Stock -->
        <a
            href="/stock"
            class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-md transition"
        >

            <div class="flex items-center justify-between mb-4">

                <div class="bg-green-100 text-green-700 p-4 rounded-2xl text-xl">
                    📦
                </div>

                <span class="text-sm text-gray-400">
                    Menu
                </span>

            </div>

            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                Monitoring Stok
            </h3>

            <p class="text-sm text-gray-500 leading-relaxed">
                Memantau kondisi stok barang secara keseluruhan.
            </p>

        </a>

        <!-- Approval -->
        <a
            href="/admin/requests"
            class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 hover:shadow-md transition"
        >

            <div class="flex items-center justify-between mb-4">

                <div class="bg-yellow-100 text-yellow-700 p-4 rounded-2xl text-xl">
                    📝
                </div>

                <span class="text-sm text-gray-400">
                    Menu
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

</div>

@endsection