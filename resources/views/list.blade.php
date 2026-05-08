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
                Kelola data barang inventaris dan akses detail transaksi
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
                href="/stock"
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl transition"
            >
                Monitoring Stok
            </a>

        </div>

    </div>

    <!-- Search -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-6">

        <form method="GET">

            <div class="flex flex-col md:flex-row gap-3">

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
                            Aksi
                        </th>

                    </tr>

                </thead>

                <!-- Body -->
                <tbody>

                    @forelse($data as $item)

                        <tr class="border-b last:border-b-0 hover:bg-gray-50 transition">

                            <!-- Nama -->
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

                            <!-- Stock -->
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
                                    class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition"
                                >
                                    Detail
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-12 text-gray-500"
                            >
                                Data barang belum tersedia
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