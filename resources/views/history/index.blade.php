@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Histori Transaksi
            </h1>

            <p class="text-gray-500 mt-2">
                Monitoring seluruh aktivitas barang masuk dan keluar
            </p>

        </div>

        <!-- Navigation -->
        <div class="flex flex-wrap gap-3">

            <a
                href="/dashboard"
                class="inline-flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium px-5 py-3 rounded-xl transition"
            >
                Dashboard
            </a>

            <a
                href="/barang"
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl transition"
            >
                Kelola Barang
            </a>

        </div>

    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <!-- Total Transaksi -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Total Transaksi
                    </p>

                    <h2 class="text-4xl font-bold text-gray-800 mt-3">
                        {{ $totalTransaksi }}
                    </h2>

                </div>

                <div class="bg-blue-100 text-blue-700 p-4 rounded-2xl">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 17v-2m3 2v-4m3 4v-6m3 6V7M4 19h16"
                        />

                    </svg>

                </div>

            </div>

            <p class="text-sm text-gray-400 mt-5">
                Seluruh aktivitas transaksi barang
            </p>

        </div>

        <!-- Barang Masuk -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Barang Masuk
                    </p>

                    <h2 class="text-4xl font-bold text-green-600 mt-3">
                        {{ $barangMasuk }}
                    </h2>

                </div>

                <div class="bg-green-100 text-green-700 p-4 rounded-2xl">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 15l7-7 7 7"
                        />

                    </svg>

                </div>

            </div>

            <p class="text-sm text-gray-400 mt-5">
                Total quantity barang masuk
            </p>

        </div>

        <!-- Barang Keluar -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Barang Keluar
                    </p>

                    <h2 class="text-4xl font-bold text-red-600 mt-3">
                        {{ $barangKeluar }}
                    </h2>

                </div>

                <div class="bg-red-100 text-red-700 p-4 rounded-2xl">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7"
                        />

                    </svg>

                </div>

            </div>

            <p class="text-sm text-gray-400 mt-5">
                Total quantity barang keluar
            </p>

        </div>

    </div>

    <!-- Placeholder Grafik -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 mb-8">

        <div class="flex items-center justify-between mb-6">

            <div>

                <h2 class="text-xl font-semibold text-gray-800">
                    Grafik Aktivitas
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Visualisasi aktivitas transaksi barang
                </p>

            </div>

            <span class="bg-yellow-100 text-yellow-700 text-xs font-medium px-3 py-1 rounded-full">
                Coming Soon
            </span>

        </div>

        <!-- Dummy Chart Area -->
        <div class="h-72 rounded-2xl border-2 border-dashed border-gray-200 flex items-center justify-center">

            <div class="text-center">

                <p class="text-gray-400 font-medium">
                    Grafik transaksi akan ditampilkan di sini
                </p>

                <p class="text-sm text-gray-400 mt-2">
                    Nantinya menggunakan Chart.js
                </p>

            </div>

        </div>

    </div>

    <!-- Filter -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-6">

        <form method="GET">

            <div class="flex flex-col lg:flex-row gap-3">

                <!-- Search -->
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama barang..."
                    class="flex-1 rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >

                <!-- Filter -->
                <select
                    name="type"
                    class="rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500"
                >

                    <option value="">
                        Semua Transaksi
                    </option>

                    <option
                        value="IN"
                        {{ request('type') == 'IN' ? 'selected' : '' }}
                    >
                        Barang Masuk
                    </option>

                    <option
                        value="OUT"
                        {{ request('type') == 'OUT' ? 'selected' : '' }}
                    >
                        Barang Keluar
                    </option>

                </select>

                <!-- Button -->
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-xl transition"
                >
                    Filter
                </button>

            </div>

        </form>

    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <!-- Header -->
                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Barang
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Tipe
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Quantity
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Catatan
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Tanggal
                        </th>

                    </tr>

                </thead>

                <!-- Body -->
                <tbody>

                    @forelse($transactions as $trx)

                        <tr class="border-b last:border-b-0 hover:bg-gray-50 transition">

                            <!-- Barang -->
                            <td class="px-6 py-5">

                                <div>

                                    <p class="font-semibold text-gray-800">
                                        {{ $trx->consumable->name ?? '-' }}
                                    </p>

                                    <p class="text-sm text-gray-400">
                                        ID Transaksi: {{ $trx->id }}
                                    </p>

                                </div>

                            </td>

                            <!-- Type -->
                            <td class="px-6 py-5">

                                @if($trx->type == 'IN')

                                    <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Barang Masuk
                                    </span>

                                @else

                                    <span class="bg-red-100 text-red-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Barang Keluar
                                    </span>

                                @endif

                            </td>

                            <!-- Quantity -->
                            <td class="px-6 py-5 text-gray-700 font-medium">

                                {{ $trx->quantity }}

                                {{ $trx->consumable->unitMeasure->name ?? '' }}

                            </td>

                            <!-- Note -->
                            <td class="px-6 py-5 text-gray-600">

                                {{ $trx->note ?? '-' }}

                            </td>

                            <!-- Date -->
                            <td class="px-6 py-5 text-gray-500">

                                {{ $trx->created_at->format('d M Y H:i') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-12 text-gray-500"
                            >
                                Histori transaksi belum tersedia
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <!-- Pagination -->
    <div class="mt-6">

        {{ $transactions->links() }}

    </div>

</div>

@endsection