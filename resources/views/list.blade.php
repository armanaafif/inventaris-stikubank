@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Daftar Barang
            </h1>

            <p class="text-gray-500 mt-2">
                Kelola data inventaris, monitoring stok, dan akses detail transaksi barang
            </p>

        </div>

        <!-- Action -->
        <div class="flex flex-wrap gap-3">

            <a
                href="/dashboard"
                class="inline-flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium px-5 py-3 rounded-xl transition"
            >
                Dashboard
            </a>

            <a
                href="/stock"
                class="inline-flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium px-5 py-3 rounded-xl transition"
            >
                Monitoring Stok
            </a>

            <a
                href="/barang/create"
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl transition shadow-sm"
            >
                Tambah Barang
            </a>

        </div>

    </div>

    <!-- Search & Summary -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 mb-6">

        <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5">

            <!-- Search -->
            <form method="GET" class="flex flex-col md:flex-row gap-3 flex-1">

                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama barang..."
                    class="flex-1 rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >

                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-xl transition"
                >
                    Cari
                </button>

            </form>

            <!-- Summary -->
            <div class="flex flex-wrap gap-3">

                <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                    <p class="text-xs text-gray-500">
                        Total Barang
                    </p>

                    <p class="font-semibold text-gray-800">
                        {{ $data->total() }}
                    </p>
                </div>

                <div class="bg-green-50 border border-green-100 rounded-xl px-4 py-3">
                    <p class="text-xs text-green-600">
                        Stok Aman
                    </p>

                    <p class="font-semibold text-green-700">
                        {{
                            $data->filter(fn($item) => $item->stock > $item->minimum_stock)->count()
                        }}
                    </p>
                </div>

                <div class="bg-red-50 border border-red-100 rounded-xl px-4 py-3">
                    <p class="text-xs text-red-600">
                        Perlu Restock
                    </p>

                    <p class="font-semibold text-red-700">
                        {{
                            $data->filter(fn($item) => $item->stock <= $item->minimum_stock)->count()
                        }}
                    </p>
                </div>

            </div>

        </div>

    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <!-- Table Head -->
                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Barang
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Total Stok
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Minimum
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Satuan
                        </th>

                        <th class="text-center px-6 py-4 text-sm font-semibold text-gray-600">
                            Status
                        </th>

                        <th class="text-center px-6 py-4 text-sm font-semibold text-gray-600">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <!-- Table Body -->
                <tbody>

                    @forelse($data as $item)

                        <tr class="border-b last:border-b-0 hover:bg-gray-50 transition">

                            <!-- Nama Barang -->
                            <td class="px-6 py-5">

                                <div>

                                    <p class="font-semibold text-gray-800">
                                        {{ $item->name }}
                                    </p>

                                    <p class="text-sm text-gray-400 mt-1">
                                        ID Barang: {{ $item->id }}
                                    </p>

                                </div>

                            </td>

                            <!-- Stock -->
                            <td class="px-6 py-5">

                                <span class="inline-flex items-center bg-blue-100 text-blue-700 text-sm font-semibold px-3 py-1 rounded-full">
                                    {{ $item->stock }}
                                </span>

                            </td>

                            <!-- Minimum -->
                            <td class="px-6 py-5 text-gray-600">

                                {{ $item->minimum_stock }}

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

                                @elseif($item->stock <= $item->minimum_stock)

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
                                    class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition"
                                >
                                    Detail
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="py-16 text-center"
                            >

                                <div class="flex flex-col items-center">

                                    <div class="bg-gray-100 text-gray-400 p-5 rounded-2xl mb-4">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-10 h-10"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">

                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M20 13V7a2 2 0 00-2-2h-3V3H9v2H6a2 2 0 00-2 2v6m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4" />

                                        </svg>

                                    </div>

                                    <h3 class="text-lg font-semibold text-gray-700 mb-2">
                                        Belum ada barang
                                    </h3>

                                    <p class="text-sm text-gray-500 mb-5">
                                        Tambahkan barang inventaris pertama untuk mulai menggunakan sistem.
                                    </p>

                                    <a
                                        href="/barang/create"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl transition"
                                    >
                                        Tambah Barang
                                    </a>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <!-- Pagination -->
    <div class="mt-6">

        {{ $data->links() }}

    </div>

</div>

@endsection