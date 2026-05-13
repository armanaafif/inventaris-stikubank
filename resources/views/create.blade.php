@extends('layouts.app')

@section('content')

<div class="max-w-3xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Tambah Barang
            </h1>

            <p class="text-gray-500 mt-2">
                Tambahkan data barang inventaris baru
            </p>

        </div>

        <!-- Navigasi -->
        <div class="flex flex-wrap gap-3">

            <a
                href="/dashboard"
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl transition"
            >
                Dashboard
            </a>

            <a
                href="/barang"
                class="inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-5 py-3 rounded-xl transition"
            >
                Daftar Barang
            </a>

        </div>

    </div>

    <!-- Error Validasi -->
    @if($errors->any())

        <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4">

            <h2 class="font-semibold text-red-700 mb-2">
                Validasi gagal
            </h2>

            <ul class="list-disc pl-5 text-sm text-red-600 space-y-1">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <!-- Form -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-8">

        <form method="POST" action="/barang/store">

            @csrf

            <!-- Nama Barang -->
            <div class="mb-6">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Barang
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Contoh: Kabel LAN"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                >

            </div>

            <!-- Satuan -->
            <div class="mb-6">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Satuan Barang
                </label>

                <select
                    name="unit_measure_id"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                >

                    <option value="">
                        Pilih satuan
                    </option>

                    @forelse($units as $unit)

                        <option
                            value="{{ $unit->id }}"
                            {{ old('unit_measure_id') == $unit->id ? 'selected' : '' }}
                        >
                            {{ $unit->name }}
                        </option>

                    @empty

                        <option disabled>
                            Data satuan belum tersedia
                        </option>

                    @endforelse

                </select>

            </div>

            <!-- Minimum Stock -->
            <div class="mb-6">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Minimum Stok
                </label>

                <input
                    type="number"
                    name="minimum_stock"
                    value="{{ old('minimum_stock') }}"
                    placeholder="Contoh: 5"
                    min="0"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                >

                <p class="text-sm text-gray-500 mt-2">
                    Digunakan sebagai batas minimum peringatan stok.
                </p>

            </div>

            <!-- Stok Awal -->
            <div class="mb-8">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Stok Awal
                </label>

                <input
                    type="number"
                    name="initial_stock"
                    value="{{ old('initial_stock', 0) }}"
                    placeholder="Contoh: 15"
                    min="0"
                    class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                >

                <p class="text-sm text-gray-500 mt-2">
                    Sistem akan otomatis membuat histori transaksi stok masuk.
                </p>

            </div>

            <!-- Button -->
            <div class="flex flex-wrap gap-3">

                <button
                    type="submit"
                    class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-xl transition"
                >
                    Simpan Barang
                </button>

                <a
                    href="/barang"
                    class="inline-flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium px-6 py-3 rounded-xl transition"
                >
                    Batal
                </a>

            </div>

        </form>

    </div>

</div>

@endsection