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
                Monitoring kondisi barang, stok inventaris, dan aktivitas penggunaan
            </p>

        </div>

        <div class="flex flex-wrap gap-3">

            <a
                href="/dashboard"
                class="inline-flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium px-5 py-3 rounded-xl transition"
            >
                Dashboard
            </a>

            <a
                href="/history"
                class="inline-flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium px-5 py-3 rounded-xl transition"
            >
                Histori
            </a>

            <a
                href="/barang/create"
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl transition shadow-sm"
            >
                Tambah Barang
            </a>

        </div>

    </div>

    <!-- Search -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6 mb-6">

        <form method="GET" class="flex flex-col md:flex-row gap-3">

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

    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Barang
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Kondisi
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Status Barang
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Total Stok
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Status Stok
                        </th>

                        <th class="text-center px-6 py-4 text-sm font-semibold text-gray-600">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($data as $item)

                        <tr class="border-b last:border-b-0 hover:bg-gray-50 transition">

                            <!-- Barang -->
                            <td class="px-6 py-5">

                                <div>

                                    <p class="font-semibold text-gray-800">
                                        {{ $item->name }}
                                    </p>

                                    <p class="text-sm text-gray-400 mt-1">
                                        {{ $item->unitMeasure->name ?? '-' }}
                                    </p>

                                </div>

                            </td>

                            <!-- Kondisi -->
                            <td class="px-6 py-5">

                                @if($item->condition == 'BARU')

                                    <span class="bg-blue-100 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Baru
                                    </span>

                                @elseif($item->condition == 'BEKAS')

                                    <span class="bg-yellow-100 text-yellow-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Bekas
                                    </span>

                                @elseif($item->condition == 'LAYAK')

                                    <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Layak
                                    </span>

                                @else

                                    <span class="bg-red-100 text-red-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Rusak
                                    </span>

                                @endif

                            </td>

                            <!-- Status Barang -->
                            <td class="px-6 py-5">

                                @if($item->status == 'AKTIF')

                                    <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Aktif
                                    </span>

                                @else

                                    <span class="bg-gray-200 text-gray-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Nonaktif
                                    </span>

                                @endif

                            </td>

                            <!-- Total Stock -->
                            <td class="px-6 py-5">

                                <span class="inline-flex items-center bg-blue-100 text-blue-700 text-sm font-semibold px-3 py-1 rounded-full">
                                    {{ $item->stock }}
                                </span>

                            </td>

                            <!-- Status Stok -->
                            <td class="px-6 py-5">

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
                                class="py-16 text-center text-gray-500"
                            >

                                Belum ada data barang.

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