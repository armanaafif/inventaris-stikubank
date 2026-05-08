@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Manajemen Stok
            </h1>

            <p class="text-gray-500 mt-1">
                Monitoring total stok seluruh barang inventaris
            </p>

        </div>

        <!-- Navigation -->
        <div>

            <a
                href="/dashboard"
                class="inline-flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium px-5 py-3 rounded-xl transition"
            >
                ← Dashboard
            </a>

        </div>

    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

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

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Status
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

                            <!-- Total Stok -->
                            <td class="px-6 py-5">

                                <span class="inline-flex items-center bg-blue-100 text-blue-700 text-sm font-medium px-3 py-1 rounded-full">
                                    {{ $item->stock }}
                                </span>

                            </td>

                            <!-- Satuan -->
                            <td class="px-6 py-5 text-gray-600">
                                {{ $item->unitMeasure->name ?? '-' }}
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-5">

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

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="text-center py-12 text-gray-500"
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