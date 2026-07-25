@extends('layouts.dashboard-sidebar')

@section('title', 'Histori Transaksi - Inventaris Stikubank')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Histori Transaksi</h1>
            <p class="text-sm text-gray-500 mt-1">Riwayat barang masuk dan keluar</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('barang.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Barang
            </a>
            <button onclick="window.location.reload()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Total Transaksi</p>
            <p class="text-2xl font-bold text-gray-800">{{ number_format($totalTransaksi ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Barang Masuk</p>
            <p class="text-2xl font-bold text-green-600">{{ number_format($barangMasuk ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Barang Keluar</p>
            <p class="text-2xl font-bold text-red-600">{{ number_format($barangKeluar ?? 0) }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Total Barang</p>
            <p class="text-2xl font-bold text-blue-600">{{ number_format($totalBarang ?? 0) }}</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('history.index') }}" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Cari nama barang..." 
                class="flex-1 min-w-[200px] rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            
            <select name="type" class="rounded-lg border border-gray-300 px-4 py-2 text-sm w-36">
                <option value="">Semua Transaksi</option>
                <option value="IN" {{ request('type') == 'IN' ? 'selected' : '' }}>Barang Masuk</option>
                <option value="OUT" {{ request('type') == 'OUT' ? 'selected' : '' }}>Barang Keluar</option>
                <option value="TRANSFER" {{ request('type') == 'TRANSFER' ? 'selected' : '' }}>Transfer</option>
            </select>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('history.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-redo mr-1"></i> Reset
            </a>
        </form>
    </div>

    <!-- Tabel Histori Transaksi -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-left text-xs font-medium text-gray-500">
                        <th class="px-6 py-4">Barang</th>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4">Catatan</th>
                        <th class="px-6 py-4">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($transactions ?? [] as $trx)
                    <tr class="text-sm hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $trx->consumable->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $trx->consumable->unitMeasure->name ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs rounded-full 
                                {{ $trx->type == 'IN' ? 'bg-green-100 text-green-700' : ($trx->type == 'TRANSFER' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700') }}">
                                @if($trx->type == 'IN')
                                    <i class="fas fa-arrow-down mr-1 text-xs"></i> Masuk
                                @elseif($trx->type == 'TRANSFER')
                                    <i class="fas fa-exchange-alt mr-1 text-xs"></i> Transfer
                                @else
                                    <i class="fas fa-arrow-up mr-1 text-xs"></i> Keluar
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold {{ $trx->type == 'IN' ? 'text-green-600' : 'text-red-600' }}">
                                {{ number_format($trx->quantity) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs max-w-xs">
                            @if($trx->type == 'TRANSFER')
                                {{ $trx->note ?? '-' }}<br>
                                <span class="text-blue-600">{{ $trx->fromLocation->name ?? '-' }} → {{ $trx->toLocation->name ?? '-' }}</span>
                            @else
                                {{ $trx->note ?? '-' }}
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $trx->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-history text-3xl mb-2 block"></i>
                            Belum ada data transaksi
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>

    <!-- Grafik Analisis (Ringkasan) -->
    @if(isset($barangMasukData) && count($barangMasukData) > 0 && (array_sum($barangMasukData) > 0 || array_sum($barangKeluarData) > 0))
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
            <h3 class="font-semibold text-gray-800">📊 Grafik Aktivitas Transaksi</h3>
            <p class="text-xs text-gray-500 mt-0.5">6 bulan terakhir</p>
        </div>
        <div class="p-6">
            <canvas id="trendChart" height="120"></canvas>
        </div>
    </div>
    @endif
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trendCtx = document.getElementById('trendChart');
        if (trendCtx && @json($barangMasukData ?? []).length > 0) {
            const hasData = @json(array_sum($barangMasukData)) > 0 || @json(array_sum($barangKeluarData)) > 0;
            if (hasData) {
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: @json($chartLabels ?? []),
                        datasets: [
                            {
                                label: 'Barang Masuk',
                                data: @json($barangMasukData ?? []),
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.05)',
                                fill: true,
                                tension: 0.3,
                                pointRadius: 3,
                                pointBackgroundColor: '#10b981'
                            },
                            {
                                label: 'Barang Keluar',
                                data: @json($barangKeluarData ?? []),
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.05)',
                                fill: true,
                                tension: 0.3,
                                pointRadius: 3,
                                pointBackgroundColor: '#ef4444'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: { mode: 'index', intersect: false }
                        },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 } }
                        }
                    }
                });
            }
        }
    });
</script>
@endsection