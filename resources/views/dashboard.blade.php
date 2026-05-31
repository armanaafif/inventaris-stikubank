<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard - Inventaris Stikubank</title>

    <!-- Favicon Stikubank -->
    <link rel="icon" type="image/webp" href="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp">
    <link rel="shortcut icon" href="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,100..900;1,100..900&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
        }
        .sidebar-item {
            transition: all 0.3s ease;
        }
        .sidebar-item:hover, .sidebar-item.active {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }
        .stat-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside class="w-72 bg-white shadow-lg flex flex-col fixed inset-y-0 z-50">
            <div class="flex items-center gap-3 px-6 py-6 border-b">
                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <img src="https://bloguna.com/wp-content/uploads/2025/12/Universitas-Stikuban.webp" class="h-6 w-auto object-contain">
                </div>
                <div>
                    <h1 class="font-bold text-gray-800">Inventaris</h1>
                    <p class="text-xs text-gray-400">Stikubank Semarang</p>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1">
                <a href="{{ route('dashboard') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 transition-all duration-200 active">
                    <i class="fas fa-tachometer-alt w-5"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('barang.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 transition-all duration-200">
                    <i class="fas fa-boxes w-5"></i><span>Barang</span>
                </a>
                <a href="{{ route('stock.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 transition-all duration-200">
                    <i class="fas fa-chart-line w-5"></i><span>Monitoring Stok</span>
                </a>
                <a href="{{ route('history.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 transition-all duration-200">
                    <i class="fas fa-history w-5"></i><span>Histori Transaksi</span>
                </a>
                @if(auth()->user() && auth()->user()->role === 'admin')
                <a href="{{ route('admin.users') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 transition-all duration-200">
                    <i class="fas fa-users w-5"></i><span>Manajemen User</span>
                </a>
                <a href="{{ route('admin.requests') }}" class="sidebar-item flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 transition-all duration-200">
                    <i class="fas fa-clipboard-list w-5"></i><span>Approval Request</span>
                </a>
                @endif
            </nav>

            <div class="p-4 border-t">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ auth()->user()->name ?? 'User' }}</p>
                        <p class="text-xs text-gray-400 capitalize">{{ auth()->user()->role ?? 'User' }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-gray-400 hover:text-red-500"><i class="fas fa-sign-out-alt"></i></button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="flex-1 ml-72 overflow-y-auto p-8">
            
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                <p class="text-sm text-gray-500 mt-1">Selamat datang, {{ auth()->user()->name ?? 'User' }}!</p>
            </div>

            <!-- Stats Cards - Bisa Diklik -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <!-- Card Total Barang -> Klik ke halaman barang -->
                <a href="{{ route('barang.index') }}" class="stat-card bg-white rounded-xl p-6 shadow-sm border border-gray-100 block transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400">Total Barang</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalBarang ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-boxes text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-blue-500 flex items-center gap-1">
                        <span>Lihat detail</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </div>
                </a>

                <!-- Card Total Stok -> Klik ke halaman stock -->
                <a href="{{ route('stock.index') }}" class="stat-card bg-white rounded-xl p-6 shadow-sm border border-gray-100 block transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400">Total Stok</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalStock ?? 0) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-line text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-green-500 flex items-center gap-1">
                        <span>Lihat detail</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </div>
                </a>

                <!-- Card Barang Masuk -> Klik ke history dengan filter IN -->
                <a href="{{ route('history.index', ['type' => 'IN']) }}" class="stat-card bg-white rounded-xl p-6 shadow-sm border border-gray-100 block transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400">Barang Masuk</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($barangMasuk ?? 0) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-arrow-down text-emerald-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-emerald-500 flex items-center gap-1">
                        <span>Lihat detail</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </div>
                </a>

                <!-- Card Barang Keluar -> Klik ke history dengan filter OUT -->
                <a href="{{ route('history.index', ['type' => 'OUT']) }}" class="stat-card bg-white rounded-xl p-6 shadow-sm border border-gray-100 block transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-400">Barang Keluar</p>
                            <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($barangKeluar ?? 0) }}</p>
                        </div>
                        <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-arrow-up text-rose-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-rose-500 flex items-center gap-1">
                        <span>Lihat detail</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                    </div>
                </a>

            </div>

            <!-- Chart & Info -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <div><h3 class="font-semibold text-gray-800">Aktivitas Transaksi</h3><p class="text-xs text-gray-400">Grafik barang masuk & keluar 6 bulan terakhir</p></div>
                        <div class="flex gap-3"><span class="text-xs flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> Masuk</span><span class="text-xs flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span> Keluar</span></div>
                    </div>
                    @if(isset($barangMasukData) && count($barangMasukData) > 0 && (array_sum($barangMasukData) > 0 || array_sum($barangKeluarData) > 0))
                        <canvas id="trendChart" height="180"></canvas>
                    @else
                        <div class="h-56 flex flex-col items-center justify-center text-gray-400 bg-gray-50 rounded-xl"><i class="fas fa-chart-line text-5xl mb-3 text-gray-300"></i><p class="text-sm">Belum ada data transaksi</p><p class="text-xs mt-1">Lakukan transaksi masuk atau keluar untuk melihat grafik</p></div>
                    @endif
                </div>
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-sm p-6 text-white">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center"><i class="fas fa-user-shield text-2xl"></i></div>
                        <div><h3 class="font-bold text-lg">{{ auth()->user()->name ?? 'Administrator' }}</h3><p class="text-blue-100 text-xs">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Staff' }}</p></div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div><p class="text-2xl font-bold">{{ $totalBarang ?? 0 }}</p><p class="text-xs text-blue-100">Total Barang</p></div>
                        <div><p class="text-2xl font-bold">{{ $totalTransaksi ?? 0 }}</p><p class="text-xs text-blue-100">Total Transaksi</p></div>
                    </div>
                    <div class="bg-white/10 rounded-lg p-3">
                        <div class="flex justify-between text-sm mb-1"><span>Stok Aman</span><span>{{ ($totalBarang ?? 0) - ($barangMenipis ?? 0) }} / {{ $totalBarang ?? 0 }}</span></div>
                        <div class="w-full bg-white/20 rounded-full h-1.5"><div class="bg-white h-1.5 rounded-full" style="width: {{ $totalBarang > 0 ? ((($totalBarang - ($barangMenipis ?? 0)) / $totalBarang) * 100) : 0 }}%"></div></div>
                        @if(($barangMenipis ?? 0) > 0)<p class="text-xs text-blue-100 mt-2"><i class="fas fa-exclamation-triangle"></i> {{ $barangMenipis }} barang stok menipis</p>@endif
                    </div>
                </div>
            </div>

            <!-- Top Items -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Top 5 Barang Paling Sering Dipakai</h3>
                    @if(isset($topUsedLabels) && count($topUsedLabels) > 0 && array_sum($topUsedData) > 0)
                        <canvas id="topUsedChart" height="200"></canvas>
                    @else
                        <div class="h-48 flex flex-col items-center justify-center text-gray-400 bg-gray-50 rounded-xl"><i class="fas fa-chart-bar text-4xl mb-2 text-gray-300"></i><p class="text-sm">Belum ada data barang keluar</p></div>
                    @endif
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Top 5 Barang Paling Sering Masuk</h3>
                    @if(isset($topInLabels) && count($topInLabels) > 0 && array_sum($topInData) > 0)
                        <canvas id="topInChart" height="200"></canvas>
                    @else
                        <div class="h-48 flex flex-col items-center justify-center text-gray-400 bg-gray-50 rounded-xl"><i class="fas fa-chart-bar text-4xl mb-2 text-gray-300"></i><p class="text-sm">Belum ada data barang masuk</p></div>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div><h3 class="font-semibold text-gray-800">Aktivitas Terbaru</h3><p class="text-xs text-gray-400 mt-1">5 transaksi terakhir</p></div>
                    <a href="{{ route('history.index') }}" class="text-xs text-blue-600 hover:text-blue-700">Lihat semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50"><tr class="text-left text-xs font-medium text-gray-500"><th class="px-6 py-3">Barang</th><th class="px-6 py-3">Tipe</th><th class="px-6 py-3">Jumlah</th><th class="px-6 py-3">Tanggal</th></tr></thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentTransactions ?? [] as $transaction)
                            <tr class="text-sm hover:bg-gray-50 transition">
                                <td class="px-6 py-3 text-gray-800">{{ $transaction->consumable->name ?? '-' }}</td>
                                <td class="px-6 py-3"><span class="inline-flex px-2 py-0.5 text-xs rounded-full {{ $transaction->type == 'IN' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $transaction->type == 'IN' ? 'Masuk' : 'Keluar' }}</span></td>
                                <td class="px-6 py-3 text-gray-800">{{ number_format($transaction->quantity) }}</td>
                                <td class="px-6 py-3 text-gray-400 text-xs">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada aktivitas</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trendCtx = document.getElementById('trendChart');
            if (trendCtx && @json($barangMasukData ?? []).length > 0) {
                const hasData = @json(array_sum($barangMasukData)) > 0 || @json(array_sum($barangKeluarData)) > 0;
                if (hasData) {
                    new Chart(trendCtx, { type: 'line', data: { labels: @json($chartLabels ?? []), datasets: [{ label: 'Barang Masuk', data: @json($barangMasukData ?? []), borderColor: '#10b981', backgroundColor: 'rgba(16, 185, 129, 0.05)', fill: true, tension: 0.3 }, { label: 'Barang Keluar', data: @json($barangKeluarData ?? []), borderColor: '#ef4444', backgroundColor: 'rgba(239, 68, 68, 0.05)', fill: true, tension: 0.3 }] }, options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } } });
                }
            }
            const usedCtx = document.getElementById('topUsedChart');
            if (usedCtx && @json($topUsedLabels ?? []).length > 0 && @json(array_sum($topUsedData)) > 0) {
                new Chart(usedCtx, { type: 'bar', data: { labels: @json($topUsedLabels ?? []), datasets: [{ label: 'Jumlah Dipakai (Unit)', data: @json($topUsedData ?? []), backgroundColor: '#f59e0b', borderRadius: 8 }] }, options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } } });
            }
            const inCtx = document.getElementById('topInChart');
            if (inCtx && @json($topInLabels ?? []).length > 0 && @json(array_sum($topInData)) > 0) {
                new Chart(inCtx, { type: 'bar', data: { labels: @json($topInLabels ?? []), datasets: [{ label: 'Jumlah Masuk (Unit)', data: @json($topInData ?? []), backgroundColor: '#10b981', borderRadius: 8 }] }, options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } } });
            }
        });
    </script>
</body>
</html>