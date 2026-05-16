@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5 mb-8">

        <div>

            <div class="flex items-center gap-3 mb-3">

                <a
                    href="/barang"
                    class="text-sm text-blue-600 hover:text-blue-700 font-medium"
                >
                    Daftar Barang
                </a>

                <span class="text-gray-300">
                    /
                </span>

                <span class="text-sm text-gray-500">
                    Detail Barang
                </span>

            </div>

            <h1 class="text-3xl font-bold text-gray-800">
                {{ $item->name }}
            </h1>

            <p class="text-gray-500 mt-2">
                Monitoring stok dan histori transaksi barang inventaris
            </p>

        </div>

        <!-- Quick Action -->
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

        </div>

    </div>

    <!-- Alert -->
    @if($stock <= 0)

        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">

            <div class="flex items-center justify-between">

                <div>

                    <h3 class="font-semibold mb-1">
                        Stok Barang Habis
                    </h3>

                    <p class="text-sm text-red-600">
                        Barang tidak dapat digunakan sampai stok ditambahkan kembali.
                    </p>

                </div>

            </div>

        </div>

    @elseif($stock <= $item->minimum_stock)

        <div class="mb-6 rounded-2xl border border-yellow-200 bg-yellow-50 px-5 py-4 text-yellow-700">

            <div class="flex items-center justify-between">

                <div>

                    <h3 class="font-semibold mb-1">
                        Stok Mulai Menipis
                    </h3>

                    <p class="text-sm text-yellow-600">
                        Segera lakukan restock untuk menjaga ketersediaan barang.
                    </p>

                </div>

            </div>

        </div>

    @endif

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <!-- Total Stock -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <p class="text-sm font-medium text-gray-500">
                Total Stok
            </p>

            <h2 class="text-4xl font-bold text-gray-800 mt-3">
                {{ $stock }}
            </h2>

            <p class="text-sm text-gray-400 mt-3">
                {{ $item->unitMeasure->name ?? '-' }}
            </p>

        </div>

        <!-- Minimum Stock -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <p class="text-sm font-medium text-gray-500">
                Minimum Stok
            </p>

            <h2 class="text-4xl font-bold text-gray-800 mt-3">
                {{ $item->minimum_stock }}
            </h2>

            <p class="text-sm text-gray-400 mt-3">
                Batas minimum stok aman
            </p>

        </div>

        <!-- Status -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <p class="text-sm font-medium text-gray-500 mb-4">
                Status Barang
            </p>

            @if($stock <= 0)

                <span class="bg-red-100 text-red-700 text-sm font-semibold px-4 py-2 rounded-full">
                    Stok Habis
                </span>

            @elseif($stock <= $item->minimum_stock)

                <span class="bg-yellow-100 text-yellow-700 text-sm font-semibold px-4 py-2 rounded-full">
                    Stok Menipis
                </span>

            @else

                <span class="bg-green-100 text-green-700 text-sm font-semibold px-4 py-2 rounded-full">
                    Stok Aman
                </span>

            @endif

        </div>

    </div>

    <!-- Action Form -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">

        <!-- Tambah Stock -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="mb-6">

                <h2 class="text-xl font-semibold text-gray-800">
                    Tambah Stok
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Menambahkan stok barang ke inventaris
                </p>

            </div>

            <form method="POST" action="/add-stock">

                @csrf

                <input
                    type="hidden"
                    name="consumable_id"
                    value="{{ $item->id }}"
                >

                <div class="mb-5">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        min="1"
                        required
                        placeholder="Masukkan jumlah stok"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >

                </div>

                <div class="mb-6">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan
                    </label>

                    <textarea
                        name="note"
                        rows="4"
                        placeholder="Contoh: Restock pembelian baru"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    ></textarea>

                </div>

                <button
                    type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 rounded-xl transition"
                >
                    Tambah Stok
                </button>

            </form>

        </div>

        <!-- Gunakan Barang -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="mb-6">

                <h2 class="text-xl font-semibold text-gray-800">
                    Gunakan Barang
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Mengurangi stok barang dari inventaris
                </p>

            </div>

            <form method="POST" action="/take-stock">

                @csrf

                <input
                    type="hidden"
                    name="consumable_id"
                    value="{{ $item->id }}"
                >

                <div class="mb-5">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        min="1"
                        max="{{ $stock }}"
                        required
                        placeholder="Masukkan jumlah barang"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    >

                </div>

                <div class="mb-6">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan
                    </label>

                    <textarea
                        name="note"
                        rows="4"
                        placeholder="Contoh: Digunakan untuk operasional"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    ></textarea>

                </div>

                <button
                    type="submit"
                    {{ $stock <= 0 ? 'disabled' : '' }}
                    class="w-full bg-red-600 hover:bg-red-700 disabled:bg-gray-400 text-white font-medium py-3 rounded-xl transition"
                >
                    Gunakan Barang
                </button>

            </form>

        </div>

    </div>

    <!-- History Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-5">

        <div>

            <h2 class="text-2xl font-bold text-gray-800">
                Riwayat Transaksi
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Histori perubahan stok barang
            </p>

        </div>

        <!-- Filter -->
        <form method="GET" class="flex gap-3">

            <select
                name="type"
                class="rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500"
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

            <button
                type="submit"
                class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2 rounded-xl transition"
            >
                Filter
            </button>

        </form>

    </div>

    <!-- History Table -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="text-left px-6 py-4 font-semibold text-gray-600">
                            Tipe
                        </th>

                        <th class="text-left px-6 py-4 font-semibold text-gray-600">
                            Jumlah
                        </th>

                        <th class="text-left px-6 py-4 font-semibold text-gray-600">
                            Catatan
                        </th>

                        <th class="text-left px-6 py-4 font-semibold text-gray-600">
                            Tanggal
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($transactions as $trx)

                        <tr class="border-b last:border-b-0 hover:bg-gray-50 transition">

                            <td class="px-6 py-4">

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

                            <td class="px-6 py-4 font-semibold text-gray-700">
                                {{ $trx->quantity }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $trx->note ?? '-' }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $trx->created_at->format('d M Y - H:i') }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="text-center py-16"
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
                                                d="M9 17v-6h13v6M9 5v6h13V5M3 5h2v12H3V5z" />

                                        </svg>

                                    </div>

                                    <h3 class="text-lg font-semibold text-gray-700 mb-2">
                                        Belum ada transaksi
                                    </h3>

                                    <p class="text-sm text-gray-500">
                                        Riwayat transaksi barang akan muncul di sini.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection