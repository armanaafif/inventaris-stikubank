@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Approval Request
            </h1>

            <p class="text-gray-500 mt-2">
                Kelola approval barang masuk dan keluar inventaris
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

        <!-- Pending -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Pending Request
                    </p>

                    <h2 class="text-4xl font-bold text-yellow-500 mt-3">
                        {{ $totalPending }}
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
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />

                    </svg>

                </div>

            </div>

            <p class="text-sm text-gray-400 mt-5">
                Menunggu approval admin
            </p>

        </div>

        <!-- Approved -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Approved
                    </p>

                    <h2 class="text-4xl font-bold text-green-600 mt-3">
                        {{ $totalApproved }}
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
                Request berhasil disetujui
            </p>

        </div>

        <!-- Rejected -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="flex items-start justify-between">

                <div>

                    <p class="text-sm font-medium text-gray-500">
                        Rejected
                    </p>

                    <h2 class="text-4xl font-bold text-red-600 mt-3">
                        {{ $totalRejected }}
                    </h2>

                </div>

                <div class="bg-red-100 text-red-700 p-4 rounded-2xl">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-7 h-7"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />

                    </svg>

                </div>

            </div>

            <p class="text-sm text-gray-400 mt-5">
                Request ditolak admin
            </p>

        </div>

    </div>

    <!-- Filter -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5 mb-6">

        <form method="GET">

            <div class="flex flex-col xl:flex-row gap-3">

                <!-- Search -->
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama barang..."
                    class="flex-1 rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >

                <!-- Filter Type -->
                <select
                    name="type"
                    class="rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500"
                >

                    <option value="">
                        Semua Tipe
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

                <!-- Submit -->
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-6 py-3 rounded-xl transition"
                >
                    Filter Data
                </button>

            </div>

        </form>

    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-5 border-b bg-gray-50">

            <div>

                <h2 class="text-lg font-semibold text-gray-800">
                    Data Approval Request
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Approval transaksi barang masuk dan keluar
                </p>

            </div>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <!-- Table Header -->
                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Barang
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Quantity
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Tipe
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            User
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Catatan
                        </th>

                        <th class="text-center px-6 py-4 text-sm font-semibold text-gray-600">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <!-- Table Body -->
                <tbody>

                    @forelse($requests as $req)

                        <tr class="border-b last:border-b-0 hover:bg-gray-50 transition">

                            <!-- Barang -->
                            <td class="px-6 py-5">

                                <div>

                                    <p class="font-semibold text-gray-800">
                                        {{ $req->consumable->name ?? '-' }}
                                    </p>

                                    <p class="text-sm text-gray-400">
                                        Request ID: {{ $req->id }}
                                    </p>

                                </div>

                            </td>

                            <!-- Quantity -->
                            <td class="px-6 py-5">

                                <div class="flex items-center gap-2">

                                    <span class="inline-flex items-center bg-blue-100 text-blue-700 text-sm font-medium px-3 py-1 rounded-full">
                                        {{ $req->quantity }}
                                    </span>

                                    <span class="text-gray-500 text-sm">
                                        {{ $req->consumable->unitMeasure->name ?? '' }}
                                    </span>

                                </div>

                            </td>

                            <!-- Type -->
                            <td class="px-6 py-5">

                                @if($req->type == 'IN')

                                    <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Barang Masuk
                                    </span>

                                @else

                                    <span class="bg-red-100 text-red-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Barang Keluar
                                    </span>

                                @endif

                            </td>

                            <!-- User -->
                            <td class="px-6 py-5 text-gray-700 font-medium">

                                {{ $req->user->name ?? '-' }}

                            </td>

                            <!-- Note -->
                            <td class="px-6 py-5 text-gray-600">

                                {{ $req->note ?? '-' }}

                            </td>

                            <!-- Action -->
                            <td class="px-6 py-5">

                                <div class="flex flex-wrap justify-center gap-2">

                                    <!-- Approve -->
                                    <form
                                        method="POST"
                                        action="/admin/requests/{{ $req->id }}/approve"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition"
                                        >
                                            Approve
                                        </button>

                                    </form>

                                    <!-- Reject -->
                                    <form
                                        method="POST"
                                        action="/admin/requests/{{ $req->id }}/reject"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition"
                                        >
                                            Reject
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="text-center py-14 text-gray-500"
                            >
                                Tidak ada request tersedia
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection