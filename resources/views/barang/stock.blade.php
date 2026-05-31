@extends('layouts.dashboard-sidebar')

@section('title', 'Monitoring Stok - Inventaris Stikubank')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Monitoring Stok</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau stok barang inventaris</p>
        </div>
        <a href="{{ route('barang.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Barang
        </a>
    </div>

    <!-- ========== PERINGATAN DARURAT DI ATAS ========== -->
    @php
        $menipisItems = $data->filter(function($item) { 
            return ($item->stock ?? 0) <= $item->minimum_stock && ($item->stock ?? 0) > 0; 
        });
        $habisItems = $data->filter(function($item) { 
            return ($item->stock ?? 0) <= 0; 
        });
        $menipisCount = $menipisItems->count();
        $habisCount = $habisItems->count();
    @endphp

    @if($habisCount > 0)
    <!-- Alert Stok Habis (Prioritas Tertinggi) -->
    <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <i class="fas fa-times-circle text-red-500 text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-red-800">⚠️ Stok Habis!</h3>
                <p class="text-sm text-red-700 mt-1">Terdapat <strong>{{ $habisCount }}</strong> barang dengan stok habis. Segera lakukan pengadaan!</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($habisItems->take(5) as $item)
                    <span class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">
                        {{ $item->name }}
                    </span>
                    @endforeach
                    @if($habisCount > 5)
                    <span class="inline-flex items-center px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">
                        +{{ $habisCount - 5 }} lainnya
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($menipisCount > 0)
    <!-- Alert Stok Menipis -->
    <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-r-xl p-4 shadow-sm">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-xl"></i>
            </div>
            <div class="flex-1">
                <h3 class="font-semibold text-yellow-800">⚠️ Stok Menipis!</h3>
                <p class="text-sm text-yellow-700 mt-1">Terdapat <strong>{{ $menipisCount }}</strong> barang dengan stok menipis. Segera lakukan restock!</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($menipisItems->take(5) as $item)
                    <span class="inline-flex items-center gap-1 px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">
                        {{ $item->name }} ({{ number_format($item->stock) }})
                    </span>
                    @endforeach
                    @if($menipisCount > 5)
                    <span class="inline-flex items-center px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full">
                        +{{ $menipisCount - 5 }} lainnya
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($habisCount == 0 && $menipisCount == 0)
    <!-- Kondisi Aman -->
    <div class="bg-green-50 border-l-4 border-green-500 rounded-r-xl p-4 shadow-sm">
        <div class="flex items-center gap-3">
            <i class="fas fa-check-circle text-green-500 text-xl"></i>
            <div>
                <p class="text-sm text-green-700">Semua stok dalam kondisi aman. Tidak ada barang yang perlu direstock.</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistik Cards Simple -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Total Stok</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($data->sum(function($item) { return $item->stock ?? 0; })) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Stok Aman</p>
            <p class="text-2xl font-bold text-green-600">{{ $data->filter(function($item) { return ($item->stock ?? 0) > $item->minimum_stock; })->count() }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Stok Menipis</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $menipisCount }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Stok Habis</p>
            <p class="text-2xl font-bold text-red-600">{{ $habisCount }}</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('stock.index') }}" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Cari nama barang..." 
                class="flex-1 min-w-[200px] rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            
            <select name="stock_status" class="rounded-lg border border-gray-300 px-4 py-2 text-sm w-36">
                <option value="">Semua Stok</option>
                <option value="aman" {{ request('stock_status') == 'aman' ? 'selected' : '' }}>Stok Aman</option>
                <option value="menipis" {{ request('stock_status') == 'menipis' ? 'selected' : '' }}>Stok Menipis</option>
                <option value="habis" {{ request('stock_status') == 'habis' ? 'selected' : '' }}>Stok Habis</option>
            </select>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
            <a href="{{ route('stock.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">Reset</a>
        </form>
    </div>

    <!-- Tabel Stok -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-left text-xs font-medium text-gray-500">
                        <th class="px-6 py-4">Nama Barang</th>
                        <th class="px-6 py-4">Satuan</th>
                        <th class="px-6 py-4">Stok Saat Ini</th>
                        <th class="px-6 py-4">Min Stok</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $filteredData = $data;
                        if (request('stock_status') == 'aman') {
                            $filteredData = $filteredData->filter(function($item) { return ($item->stock ?? 0) > $item->minimum_stock; });
                        } elseif (request('stock_status') == 'menipis') {
                            $filteredData = $filteredData->filter(function($item) { return ($item->stock ?? 0) <= $item->minimum_stock && ($item->stock ?? 0) > 0; });
                        } elseif (request('stock_status') == 'habis') {
                            $filteredData = $filteredData->filter(function($item) { return ($item->stock ?? 0) <= 0; });
                        }
                    @endphp

                    @forelse($filteredData as $item)
                    @php
                        $stock = $item->stock ?? 0;
                        $minStock = $item->minimum_stock;
                        $status = $stock <= 0 ? 'habis' : ($stock <= $minStock ? 'menipis' : 'aman');
                    @endphp
                    <tr class="text-sm hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $item->name }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->unitMeasure->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="font-semibold {{ $status == 'habis' ? 'text-red-600' : ($status == 'menipis' ? 'text-yellow-600' : 'text-gray-800') }}">
                                {{ number_format($stock) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ number_format($minStock) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs rounded-full 
                                {{ $status == 'aman' ? 'bg-green-100 text-green-700' : 
                                   ($status == 'menipis' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                @if($status == 'aman')
                                    <i class="fas fa-check-circle mr-1 text-xs"></i> Aman
                                @elseif($status == 'menipis')
                                    <i class="fas fa-exclamation-triangle mr-1 text-xs"></i> Menipis
                                @else
                                    <i class="fas fa-times-circle mr-1 text-xs"></i> Habis
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('barang.show', $item->id) }}" class="text-blue-600 hover:text-blue-800" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                     </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-box-open text-3xl mb-2 block"></i>
                            Tidak ada data barang
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection