@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">

    {{-- ALERT --}}
    @if($stock <= 0)
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            Stok habis
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- INFO --}}
    <div class="bg-white p-5 rounded-xl shadow mb-6">
        <h1 class="text-2xl font-bold mb-2">{{ $item->name }}</h1>
        <p class="text-gray-600">
            Stok:
            <span class="font-semibold">
                {{ $stock }} {{ $item->unitMeasure->name ?? '-' }}
            </span>
        </p>
    </div>

    {{-- FORM --}}
    <div class="grid grid-cols-2 gap-6 mb-6">

        {{-- TAMBAH --}}
        <form method="POST" action="/add-stock" class="bg-white p-5 rounded-xl shadow">
            @csrf
            <input type="hidden" name="consumable_id" value="{{ $item->id }}">

            <input type="number" name="quantity"
                class="w-full border p-2 rounded mb-2"
                placeholder="Jumlah" required>

            <input type="text" name="note"
                class="w-full border p-2 rounded mb-2"
                placeholder="Catatan">

            <button class="bg-green-600 text-white px-4 py-2 rounded w-full">
                Tambah
            </button>
        </form>

        {{-- PAKAI --}}
        <form method="POST" action="/take-stock" class="bg-white p-5 rounded-xl shadow">
            @csrf
            <input type="hidden" name="consumable_id" value="{{ $item->id }}">

            <input type="number" name="quantity"
                max="{{ $stock }}"
                class="w-full border p-2 rounded mb-2"
                placeholder="Jumlah" required>

            <input type="text" name="note"
                class="w-full border p-2 rounded mb-2"
                placeholder="Catatan">

            <button class="bg-red-600 text-white px-4 py-2 rounded w-full"
                {{ $stock <= 0 ? 'disabled' : '' }}>
                Gunakan
            </button>
        </form>

    </div>

    {{-- FILTER --}}
    <form method="GET" class="mb-4">
        <select name="type" class="border p-2 rounded">
            <option value="">Semua</option>
            <option value="IN" {{ request('type') == 'IN' ? 'selected' : '' }}>Masuk</option>
            <option value="OUT" {{ request('type') == 'OUT' ? 'selected' : '' }}>Keluar</option>
        </select>

        <button class="bg-gray-700 text-white px-3 py-2 rounded">
            Filter
        </button>
    </form>

    {{-- HISTORI --}}
    <div class="bg-white p-5 rounded-xl shadow">

        <h2 class="font-semibold mb-4">Histori Transaksi</h2>

        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Tipe</th>
                    <th class="p-3 text-left">Jumlah</th>
                    <th class="p-3 text-left">Catatan</th>
                    <th class="p-3 text-left">Tanggal</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transactions as $trx)
                <tr class="border-b">
                    <td class="p-3">{{ $trx->type }}</td>
                    <td class="p-3">{{ $trx->quantity }}</td>
                    <td class="p-3">{{ $trx->note ?? '-' }}</td>
                    <td class="p-3 text-gray-500">{{ $trx->created_at }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center p-6 text-gray-500">
                        Belum ada transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>
@endsection