@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto">

    <!-- Navigasi -->
    <div class="flex flex-wrap gap-3 mb-6">

        <a
            href="/barang"
            class="inline-flex items-center gap-2 bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium px-4 py-2 rounded-xl transition"
        >
            Daftar Barang
        </a>

        <a
            href="/stock"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-xl transition"
        >
            Monitoring Stok
        </a>

    </div>

    <!-- Alert -->
    @if($stock <= 0)
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            Stok barang sedang habis
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <!-- Informasi Barang -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>

                <h1 class="text-3xl font-bold text-gray-800">
                    {{ $item->name }}
                </h1>

                <p class="mt-2 text-gray-500">
                    Detail stok dan histori transaksi barang
                </p>

            </div>

            <div class="bg-blue-50 px-6 py-4 rounded-xl">

                <p class="text-sm text-blue-600 mb-1">
                    Total Stok
                </p>

                <p class="text-3xl font-bold text-blue-700">
                    {{ $stock }}
                </p>

                <p class="text-sm text-gray-500">
                    {{ $item->unitMeasure->name ?? '-' }}
                </p>

            </div>

        </div>

    </div>

    <!-- Form -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        <!-- Tambah Stok -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            <h2 class="text-xl font-semibold text-gray-800 mb-5">
                Tambah Stok
            </h2>

            <form method="POST" action="/add-stock">

                @csrf

                <input
                    type="hidden"
                    name="consumable_id"
                    value="{{ $item->id }}"
                >

                <!-- Quantity -->
                <div class="mb-4">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        min="1"
                        required
                        placeholder="Masukkan jumlah"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >

                </div>

                <!-- Catatan -->
                <div class="mb-5">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan
                    </label>

                    <textarea
                        name="note"
                        rows="3"
                        placeholder="Tambahkan catatan"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    ></textarea>

                </div>

                <!-- Button -->
                <button
                    type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 rounded-xl transition"
                >
                    Tambah Stok
                </button>

            </form>

        </div>

        <!-- Gunakan Barang -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

            <h2 class="text-xl font-semibold text-gray-800 mb-5">
                Gunakan Barang
            </h2>

            <form method="POST" action="/take-stock">

                @csrf

                <input
                    type="hidden"
                    name="consumable_id"
                    value="{{ $item->id }}"
                >

                <!-- Quantity -->
                <div class="mb-4">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jumlah
                    </label>

                    <input
                        type="number"
                        name="quantity"
                        min="1"
                        max="{{ $stock }}"
                        required
                        placeholder="Masukkan jumlah"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    >

                </div>

                <!-- Catatan -->
                <div class="mb-5">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan
                    </label>

                    <textarea
                        name="note"
                        rows="3"
                        placeholder="Tambahkan catatan"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-red-500 focus:border-red-500"
                    ></textarea>

                </div>

                <!-- Button -->
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

    <!-- Header Histori -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">

        <div>

            <h2 class="text-2xl font-bold text-gray-800">
                Riwayat Transaksi
            </h2>

            <p class="text-gray-500 text-sm mt-1">
                Histori barang masuk dan keluar
            </p>

        </div>

        <!-- Filter -->
        <form method="GET" class="flex gap-3">

            <select
                name="type"
                class="rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500"
            >
                <option value="">Semua Transaksi</option>

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

    <!-- Table Histori -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <!-- Header -->
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

                <!-- Body -->
                <tbody>

                    @forelse($transactions as $trx)

                        <tr class="border-b last:border-b-0 hover:bg-gray-50 transition">

                            <!-- Tipe -->
                            <td class="px-6 py-4">

                                @if($trx->type == 'IN')

                                    <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Masuk
                                    </span>

                                @else

                                    <span class="bg-red-100 text-red-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Keluar
                                    </span>

                                @endif

                            </td>

                            <!-- Quantity -->
                            <td class="px-6 py-4 font-medium text-gray-700">
                                {{ $trx->quantity }}
                            </td>

                            <!-- Note -->
                            <td class="px-6 py-4 text-gray-600">
                                {{ $trx->note ?? '-' }}
                            </td>

                            <!-- Date -->
                            <td class="px-6 py-4 text-gray-500">
                                {{ $trx->created_at }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="4"
                                class="text-center py-10 text-gray-500"
                            >
                                Belum ada transaksi
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection