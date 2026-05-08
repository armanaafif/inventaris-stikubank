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
                Kelola approval barang masuk dan keluar
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
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl transition"
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
                        Pending
                    </p>

                    <h2 class="text-4xl font-bold text-gray-800 mt-3">
                        {{ $totalPending }}
                    </h2>

                </div>

                <div class="bg-yellow-100 text-yellow-700 p-4 rounded-2xl text-xl">
                    ⏳
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

                    <h2 class="text-4xl font-bold text-gray-800 mt-3">
                        {{ $totalApproved }}
                    </h2>

                </div>

                <div class="bg-green-100 text-green-700 p-4 rounded-2xl text-xl">
                    ✔
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

                    <h2 class="text-4xl font-bold text-gray-800 mt-3">
                        {{ $totalRejected }}
                    </h2>

                </div>

                <div class="bg-red-100 text-red-700 p-4 rounded-2xl text-xl">
                    ✖
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

            <div class="flex flex-col lg:flex-row gap-3">

                <!-- Search -->
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari barang..."
                    class="flex-1 rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >

                <!-- Filter -->
                <select
                    name="type"
                    class="rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Semua Tipe</option>

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

                <!-- Body -->
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
                                        ID Request: {{ $req->id }}
                                    </p>

                                </div>

                            </td>

                            <!-- Quantity -->
                            <td class="px-6 py-5">

                                <span class="font-medium text-gray-700">
                                    {{ $req->quantity }}
                                </span>

                                <span class="text-gray-500 text-sm">
                                    {{ $req->consumable->unitMeasure->name ?? '' }}
                                </span>

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
                            <td class="px-6 py-5 text-gray-700">

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
                                class="text-center py-12 text-gray-500"
                            >
                                Tidak ada request pending
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection