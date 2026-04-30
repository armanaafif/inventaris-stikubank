@extends('layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">Dashboard Inventaris</h1>

    <div class="grid grid-cols-3 gap-6">

        <!-- Card Barang -->
        <div class="bg-white p-5 rounded-xl shadow">
            <h2 class="text-sm text-gray-500">Total Barang</h2>
            <p class="text-2xl font-bold mt-2">{{ $totalBarang ?? 0 }}</p>
        </div>

        <!-- Card Stok -->
        <div class="bg-white p-5 rounded-xl shadow">
            <h2 class="text-sm text-gray-500">Total Stok</h2>
            <p class="text-2xl font-bold mt-2">{{ $totalStock ?? 0 }}</p>
        </div>

        <!-- Card Request -->
        <div class="bg-white p-5 rounded-xl shadow">
            <h2 class="text-sm text-gray-500">Request Pending</h2>
            <p class="text-2xl font-bold mt-2">{{ $pendingRequest ?? 0 }}</p>
        </div>

    </div>

    <!-- Shortcut -->
    <div class="mt-8 flex gap-4">
        <a href="/barang" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Kelola Barang
        </a>

        <a href="/admin/requests" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Approval Request
        </a>
    </div>

</div>
@endsection