@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Monitoring Stok
            </h1>

            <p class="text-gray-500 mt-2">
                Monitoring kondisi stok seluruh barang inventaris
            </p>

        </div>

        <!-- Navigasi -->
        <div class="flex flex-wrap gap-3">

            <a
                href="/dashboard"
                class="inline-flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium px-5 py-3 rounded-xl transition"
            >
                Dashboard
            </a>

            <a
                href="/barang"
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl transition shadow-sm"
            >
                Kelola Barang
            </a>

        </div>

    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <!-- Total Barang -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Total Barang
                    </p>

                    <h2 class="text-4xl font-bold text-gray-800 mt-3">
                        {{ $data->count() }}
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
                Total barang inventaris terdaftar
            </p>

        </div>

        <!-- Barang Aman -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Stock Aman
                    </p>

                    <h2 class="text-4xl font-bold text-green-600 mt-3">
                        {{ $data->where('stock', '>', 10)->count() }}
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
                            d="M5 13l4 4L19 7" />

                    </svg>

                </div>

            </div>

            <p class="text-sm text-gray-400 mt-5">
                Barang dengan stok aman
            </p>

        </div>

        <!-- Barang Menipis -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Stock Menipis
                    </p>

                    <h2 class="text-4xl font-bold text-yellow-500 mt-3">
                        {{ $data->where('stock', '<=', 10)->where('stock', '>', 0)->count() }}
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
                            d="M12 9v2m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />

                    </svg>

                </div>

            </div>

            <p class="text-sm text-gray-400 mt-5">
                Barang yang perlu restock
            </p>

        </div>

    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        <!-- Header Table -->
        <div class="flex items-center justify-between px-6 py-5 border-b bg-gray-50">

            <div>

                <h2 class="text-lg font-semibold text-gray-800">
                    Daftar Monitoring Stok
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Informasi stok barang inventaris secara realtime
                </p>

            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <!-- Header -->
                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Nama Barang
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Total Stok
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Satuan
                        </th>

                        <th class="text-center px-6 py-4 text-sm font-semibold text-gray-600">
                            Status
                        </th>

                        <th class="text-center px-6 py-4 text-sm font-semibold text-gray-600">
                            Detail
                        </th>

                    </tr>

                </thead>

                <!-- Body -->
                <tbody>

                    @forelse($data as $item)

                        <tr class="border-b last:border-b-0 hover:bg-gray-50 transition">

                            <!-- Nama Barang -->
                            <td class="px-6 py-5">

                                <div>

                                    <p class="font-semibold text-gray-800">
                                        {{ $item->name }}
                                    </p>

                                    <p class="text-sm text-gray-400">
                                        ID Barang: {{ $item->id }}
                                    </p>

                                </div>

                            </td>

                            <!-- Total Stock -->
                            <td class="px-6 py-5">

                                <span class="inline-flex items-center bg-blue-100 text-blue-700 text-sm font-medium px-3 py-1 rounded-full">
                                    {{ $item->stock }}
                                </span>

                            </td>

                            <!-- Unit -->
                            <td class="px-6 py-5 text-gray-600">

                                {{ $item->unitMeasure->name ?? '-' }}

                            </td>

                            <!-- Status -->
                            <td class="px-6 py-5 text-center">

                                @if($item->stock <= 0)

                                    <span class="bg-red-100 text-red-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Habis
                                    </span>

                                @elseif($item->stock <= 10)

                                    <span class="bg-yellow-100 text-yellow-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Menipis
                                    </span>

                                @else

                                    <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Aman
                                    </span>

                                @endif

                            </td>

                            <!-- Action -->
                            <td class="px-6 py-5 text-center">

                                <a
                                    href="/barang/{{ $item->id }}"
                                    class="inline-flex items-center justify-center bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium px-4 py-2 rounded-xl transition"
                                >
                                    Detail
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-14 text-gray-500"
                            >
                                Data stok belum tersedia
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection