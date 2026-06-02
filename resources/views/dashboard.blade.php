@extends('layouts.dashboard-sidebar')

@section('title', 'Dashboard - Inventaris Stikubank')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang, ' . (auth()->user()->name ?? 'User'))

@section('content')

<!--
|==========================================================================
| BAGIAN 1: STATISTIK CARD
|==========================================================================
| Tampilan angka-angka penting di halaman depan.
| Terbagi menjadi 2 baris:
| - Baris 1: Total Barang, Total Stok, Barang Masuk, Barang Keluar
| - Baris 2: Peminjaman Aktif, Pending Pinjam, Terlambat
| 
| Card peminjaman bisa diklik langsung menuju halaman manajemen peminjaman.
| Ini memudahkan admin untuk melihat detail tanpa perlu mencari menu.
|==========================================================================
-->

<!-- BARIS PERTAMA: 4 CARD STATISTIK STOK -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-5">
    
    <!-- Card Total Barang -->
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400">Total Barang</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalBarang ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-boxes text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Card Total Stok -->
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400">Total Stok</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalStock ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fas fa-chart-line text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Card Barang Masuk -->
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400">Barang Masuk</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($barangMasuk ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                <i class="fas fa-arrow-down text-emerald-600 text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Card Barang Keluar -->
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400">Barang Keluar</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($barangKeluar ?? 0) }}</p>
            </div>
            <div class="w-12 h-12 bg-rose-100 rounded-full flex items-center justify-center">
                <i class="fas fa-arrow-up text-rose-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- BARIS KEDUA: 3 CARD STATISTIK PEMINJAMAN -->
<!-- Card ini bisa diklik dan akan membawa admin ke halaman manajemen peminjaman -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
    
    <!-- Card untuk menampilkan jumlah barang yang sedang dipinjam -->
    <a href="{{ route('admin.borrowings') }}" class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition block">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400">Peminjaman Aktif</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">
                    {{ $totalBorrowed ?? 0 }}
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fas fa-hand-holding text-blue-600 text-xl"></i>
            </div>
        </div>
    </a>

    <!-- Card untuk menampilkan jumlah pengajuan pinjam yang masih antri -->
    <a href="{{ route('admin.borrowings') }}" class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition block">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400">Pending Pinjam</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">
                    {{ $pendingBorrowings ?? 0 }}
                </p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
        </div>
    </a>

    <!-- Card untuk menampilkan jumlah peminjaman yang melewati batas tanggal kembali -->
    <a href="{{ route('admin.borrowings') }}" class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition block">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-400">Terlambat</p>
                <p class="text-2xl font-bold text-red-600 mt-1">
                    {{ $lateBorrowings ?? 0 }}
                </p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
        </div>
    </a>
</div>

<!--
|==========================================================================
| BAGIAN 2: MENU CEPAT
|==========================================================================
| Dua kartu besar yang memudahkan staff/admin untuk langsung:
| - Tambah stok barang
| - Gunakan barang (mengurangi stok)
| 
| Keduanya mengarah ke halaman daftar barang, karena user harus pilih dulu
| barang mana yang mau ditambah atau digunakan.
|==========================================================================
-->

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    
    <!-- Card shortcut Tambah Stok -->
    <a href="{{ route('barang.index') }}" class="group bg-gradient-to-r from-green-50 to-white rounded-xl p-5 border border-green-100 hover:shadow-md transition block">
        <div class="flex items-start justify-between">
            <div>
                <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center mb-3 group-hover:scale-110 transition">
                    <i class="fas fa-plus-circle text-green-600 text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-lg">Tambah Stok</h3>
                <p class="text-sm text-gray-500 mt-1">Menambahkan stok barang ke inventaris</p>
                <p class="text-xs text-gray-400 mt-2">Pilih barang, lalu tambah stok</p>
            </div>
            <div class="text-green-600 group-hover:translate-x-1 transition">
                <i class="fas fa-arrow-right text-lg"></i>
            </div>
        </div>
    </a>

    <!-- Card shortcut Gunakan Barang -->
    <a href="{{ route('barang.index') }}" class="group bg-gradient-to-r from-red-50 to-white rounded-xl p-5 border border-red-100 hover:shadow-md transition block">
        <div class="flex items-start justify-between">
            <div>
                <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center mb-3 group-hover:scale-110 transition">
                    <i class="fas fa-minus-circle text-red-600 text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800 text-lg">Gunakan Barang</h3>
                <p class="text-sm text-gray-500 mt-1">Mengurangi stok barang dari inventaris</p>
                <p class="text-xs text-gray-400 mt-2">Pilih barang, lalu gunakan barang</p>
            </div>
            <div class="text-red-600 group-hover:translate-x-1 transition">
                <i class="fas fa-arrow-right text-lg"></i>
            </div>
        </div>
    </a>
</div>

<!--
|==========================================================================
| BAGIAN 3: GRAFIK AKTIVITAS
|==========================================================================
| Menampilkan tren transaksi barang masuk dan keluar dalam 6 bulan terakhir.
| Grafik ini membantu melihat pola pergerakan stok dari waktu ke waktu.
|==========================================================================
-->

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Area Grafik Utama (mengambil 2/3 lebar di layar besar) -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 class="font-semibold text-gray-800">Aktivitas Transaksi</h3>
                <p class="text-xs text-gray-400 mt-1">Grafik barang masuk & keluar 6 bulan terakhir</p>
            </div>
            <div class="flex gap-3">
                <span class="text-xs flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> Masuk</span>
                <span class="text-xs flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-red-500"></span> Keluar</span>
            </div>
        </div>
        
        <!-- Jika ada data transaksi, tampilkan grafik. Jika tidak, tampilkan pesan kosong -->
        @if(isset($barangMasukData) && count($barangMasukData) > 0 && (array_sum($barangMasukData) > 0 || array_sum($barangKeluarData) > 0))
            <canvas id="trendChart" height="180"></canvas>
        @else
            <div class="h-56 flex flex-col items-center justify-center text-gray-400 bg-gray-50 rounded-xl">
                <i class="fas fa-chart-line text-4xl mb-2 text-gray-300"></i>
                <p class="text-sm">Belum ada data transaksi</p>
                <p class="text-xs mt-1">Lakukan transaksi masuk atau keluar untuk melihat grafik</p>
            </div>
        @endif
    </div>

    <!-- Profil User + Ringkasan Singkat (mengambil 1/3 lebar di layar besar) -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl shadow-sm p-5 text-white">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center">
                <i class="fas fa-user-shield text-2xl"></i>
            </div>
            <div>
                <h3 class="font-bold text-lg">{{ auth()->user()->name ?? 'Administrator' }}</h3>
                <p class="text-blue-100 text-xs">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Staff' }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <p class="text-2xl font-bold">{{ $totalBarang ?? 0 }}</p>
                <p class="text-xs text-blue-100">Total Barang</p>
            </div>
            <div>
                <p class="text-2xl font-bold">{{ $totalTransaksi ?? 0 }}</p>
                <p class="text-xs text-blue-100">Total Transaksi</p>
            </div>
        </div>
        
        <!-- Indikator kesehatan stok -->
        <div class="bg-white/10 rounded-lg p-3">
            <div class="flex justify-between text-sm mb-1">
                <span>Stok Aman</span>
                <span>{{ ($totalBarang ?? 0) - ($barangMenipis ?? 0) }} / {{ $totalBarang ?? 0 }}</span>
            </div>
            <div class="w-full bg-white/20 rounded-full h-1.5">
                <div class="bg-white h-1.5 rounded-full" style="width: {{ $totalBarang > 0 ? ((($totalBarang - ($barangMenipis ?? 0)) / $totalBarang) * 100) : 0 }}%"></div>
            </div>
            @if(($barangMenipis ?? 0) > 0)
            <p class="text-xs text-blue-100 mt-2">
                <i class="fas fa-exclamation-triangle"></i> {{ $barangMenipis }} barang stok menipis
            </p>
            @endif
        </div>
    </div>
</div>

<!--
|==========================================================================
| BAGIAN 4: TOP 5 BARANG
|==========================================================================
| Menampilkan barang apa saja yang paling sering:
| - Dipakai (keluar)
| - Masuk (ditambahkan stoknya)
| 
| Ini membantu mengetahui barang apa yang paling aktif digunakan.
|==========================================================================
-->

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    <!-- Grafik Top 5 Barang Paling Sering Dipakai -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Top 5 Barang Paling Sering Dipakai</h3>
        @if(isset($topUsedLabels) && count($topUsedLabels) > 0 && array_sum($topUsedData) > 0)
            <canvas id="topUsedChart" height="200"></canvas>
        @else
            <div class="h-48 flex flex-col items-center justify-center text-gray-400 bg-gray-50 rounded-xl">
                <i class="fas fa-chart-bar text-4xl mb-2 text-gray-300"></i>
                <p class="text-sm">Belum ada data barang keluar</p>
            </div>
        @endif
    </div>

    <!-- Grafik Top 5 Barang Paling Sering Masuk -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Top 5 Barang Paling Sering Masuk</h3>
        @if(isset($topInLabels) && count($topInLabels) > 0 && array_sum($topInData) > 0)
            <canvas id="topInChart" height="200"></canvas>
        @else
            <div class="h-48 flex flex-col items-center justify-center text-gray-400 bg-gray-50 rounded-xl">
                <i class="fas fa-chart-bar text-4xl mb-2 text-gray-300"></i>
                <p class="text-sm">Belum ada data barang masuk</p>
            </div>
        @endif
    </div>
</div>

<!--
|==========================================================================
| BAGIAN 5: AKTIVITAS TERBARU
|==========================================================================
| Tabel 5 transaksi terakhir untuk memantau kegiatan terkini.
| User bisa klik "Lihat semua" untuk melihat history lengkap.
|==========================================================================
-->

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <div>
            <h3 class="font-semibold text-gray-800">Aktivitas Terbaru</h3>
            <p class="text-xs text-gray-400 mt-1">5 transaksi terakhir</p>
        </div>
        <a href="{{ route('history.index') }}" class="text-xs text-blue-600 hover:text-blue-700">Lihat semua →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr class="text-left text-xs font-medium text-gray-500">
                    <th class="px-6 py-3">Barang</th>
                    <th class="px-6 py-3">Tipe</th>
                    <th class="px-6 py-3">Jumlah</th>
                    <th class="px-6 py-3">Tanggal</th>
                \)	
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($recentTransactions ?? [] as $transaction)
                <tr class="text-sm hover:bg-gray-50 transition">
                    <td class="px-6 py-3 text-gray-800">{{ $transaction->consumable->name ?? '-' }}</td>
                    <td class="px-6 py-3">
                        <span class="inline-flex px-2 py-0.5 text-xs rounded-full {{ $transaction->type == 'IN' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $transaction->type == 'IN' ? 'Masuk' : 'Keluar' }}
                        </span>
                    </td>
                    <td class="px-6 py-3 text-gray-800">{{ number_format($transaction->quantity) }}</td>
                    <td class="px-6 py-3 text-gray-400 text-xs">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada aktivitas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!--
|==========================================================================
| BAGIAN 6: SCRIPT CHART.JS
|==========================================================================
| Kode JavaScript untuk menggambar grafik menggunakan library Chart.js
| - trendChart: grafik garis untuk aktivitas 6 bulan
| - topUsedChart: grafik batang untuk barang paling sering dipakai
| - topInChart: grafik batang untuk barang paling sering masuk
|==========================================================================
-->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Grafik tren transaksi 6 bulan terakhir (jenis garis)
    const trendCtx = document.getElementById('trendChart');
    if (trendCtx && @json($barangMasukData ?? []).length > 0) {
        const hasData = @json(array_sum($barangMasukData)) > 0 || @json(array_sum($barangKeluarData)) > 0;
        if (hasData) {
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels ?? []),
                    datasets: [
                        { label: 'Barang Masuk', data: @json($barangMasukData ?? []), borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.05)', fill: true, tension: 0.3, pointRadius: 3, pointBackgroundColor: '#10b981' },
                        { label: 'Barang Keluar', data: @json($barangKeluarData ?? []), borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.05)', fill: true, tension: 0.3, pointRadius: 3, pointBackgroundColor: '#ef4444' }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
            });
        }
    }
    
    // Grafik Top 5 Barang Paling Sering Dipakai (jenis batang)
    const usedCtx = document.getElementById('topUsedChart');
    if (usedCtx && @json($topUsedLabels ?? []).length > 0 && @json(array_sum($topUsedData)) > 0) {
        new Chart(usedCtx, { type: 'bar', data: { labels: @json($topUsedLabels ?? []), datasets: [{ label: 'Jumlah Dipakai', data: @json($topUsedData ?? []), backgroundColor: '#f59e0b', borderRadius: 8, barPercentage: 0.6 }] }, options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } } });
    }
    
    // Grafik Top 5 Barang Paling Sering Masuk (jenis batang)
    const inCtx = document.getElementById('topInChart');
    if (inCtx && @json($topInLabels ?? []).length > 0 && @json(array_sum($topInData)) > 0) {
        new Chart(inCtx, { type: 'bar', data: { labels: @json($topInLabels ?? []), datasets: [{ label: 'Jumlah Masuk', data: @json($topInData ?? []), backgroundColor: '#10b981', borderRadius: 8, barPercentage: 0.6 }] }, options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } } });
    }
});
</script>
@endsection