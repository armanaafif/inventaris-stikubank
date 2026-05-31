@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Dashboard Inventaris
            </h1>
            <p class="text-gray-500 mt-2">
                Ringkasan stok, transaksi, dan aktivitas inventaris
            </p>
        </div>

        <!-- Action Button - Hanya Admin yang bisa tambah barang -->
        @if(auth()->user()->role === 'admin')
        <div>
            <a href="{{ route('barang.create') }}"
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl transition shadow-sm">
                + Tambah Barang
            </a>
        </div>
        @endif
    </div>

    <!-- Insight Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">

        <!-- Total Barang -->
        <a href="{{ route('barang.index') }}"
            class="group bg-white border border-gray-100 rounded-2xl shadow-sm p-5 hover:shadow-lg hover:-translate-y-1 transition">

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total Barang</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalBarang ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2h-3V3H9v2H6a2 2 0 00-2 2v6m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0H4" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs text-blue-600 group-hover:translate-x-1 transition">Lihat detail →</div>
        </a>

        <!-- Total Stok -->
        <a href="{{ route('stock.index') }}"
            class="group bg-white border border-gray-100 rounded-2xl shadow-sm p-5 hover:shadow-lg hover:-translate-y-1 transition">

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Total Stok</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalStock ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 12h14M5 16h14" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs text-emerald-600 group-hover:translate-x-1 transition">Lihat detail →</div>
        </a>

        <!-- Barang Masuk -->
        <a href="{{ route('history.index', ['type' => 'IN']) }}"
            class="group bg-white border border-gray-100 rounded-2xl shadow-sm p-5 hover:shadow-lg hover:-translate-y-1 transition">

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Barang Masuk</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($barangMasuk ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs text-green-600 group-hover:translate-x-1 transition">Lihat detail →</div>
        </a>

        <!-- Barang Keluar -->
        <a href="{{ route('history.index', ['type' => 'OUT']) }}"
            class="group bg-white border border-gray-100 rounded-2xl shadow-sm p-5 hover:shadow-lg hover:-translate-y-1 transition">

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wide">Barang Keluar</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($barangKeluar ?? 0) }}</p>
                </div>
                <div class="w-10 h-10 bg-rose-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs text-rose-600 group-hover:translate-x-1 transition">Lihat detail →</div>
        </a>

    </div>

    <!-- Admin Menu Khusus (Hanya untuk Admin) -->
    @if(auth()->user()->role === 'admin')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <!-- Manajemen User Card -->
        <a href="{{ route('admin.users') }}"
            class="group bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-2xl shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition">

            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-purple-800">Manajemen User</h2>
                    <p class="text-sm text-purple-600 mt-1">Kelola akun staff dan approval user baru</p>
                    <div class="mt-4 flex items-center gap-2 text-purple-700 group-hover:translate-x-1 transition">
                        <span class="text-sm font-medium">Kelola User</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
                <div class="bg-purple-200 text-purple-700 p-4 rounded-2xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Approval Request Card -->
        <a href="{{ route('admin.requests') }}"
            class="group bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-2xl shadow-sm p-6 hover:shadow-lg hover:-translate-y-1 transition">

            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-yellow-800">Approval Request</h2>
                    <p class="text-sm text-yellow-600 mt-1">Approve atau reject permintaan barang</p>
                    <div class="mt-4 flex items-center gap-2 text-yellow-700 group-hover:translate-x-1 transition">
                        <span class="text-sm font-medium">Lihat Request</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
                <div class="bg-yellow-200 text-yellow-700 p-4 rounded-2xl">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </a>

    </div>
    @endif

    <!-- Menu Cepat (Untuk Semua User) -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="border-b px-6 py-5">
            <h2 class="text-xl font-semibold text-gray-800">Menu Cepat</h2>
            <p class="text-sm text-gray-500 mt-1">Akses fitur utama inventaris</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">

            <!-- Barang -->
            <a href="{{ route('barang.index') }}"
                class="p-6 border-b sm:border-b-0 sm:border-r hover:bg-gray-50 transition">
                <h3 class="font-semibold text-gray-800 mb-2">Kelola Barang</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Lihat, tambah, dan kelola seluruh data barang inventaris.</p>
            </a>

            <!-- Monitoring Stok -->
            <a href="{{ route('stock.index') }}"
                class="p-6 border-b sm:border-b-0 sm:border-r hover:bg-gray-50 transition">
                <h3 class="font-semibold text-gray-800 mb-2">Monitoring Stok</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Pantau stok aman, menipis, atau habis secara realtime.</p>
            </a>

            <!-- Histori Transaksi -->
            <a href="{{ route('history.index') }}"
                class="p-6 border-b sm:border-b-0 sm:border-r hover:bg-gray-50 transition">
                <h3 class="font-semibold text-gray-800 mb-2">Histori Transaksi</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Analisa aktivitas barang masuk dan keluar.</p>
            </a>

            <!-- Grafik & Analisis (Link ke history dengan scroll) -->
            <a href="{{ route('history.index') }}#grafik"
                class="p-6 hover:bg-gray-50 transition">
                <h3 class="font-semibold text-gray-800 mb-2">Grafik & Analisis</h3>
                <p class="text-sm text-gray-500 leading-relaxed">Lihat grafik trend barang masuk dan keluar.</p>
            </a>

        </div>
    </div>

    <!-- Peringatan Stok Menipis -->
    @if(($barangMenipis ?? 0) > 0)
    <div class="mt-6 bg-amber-50 rounded-xl border border-amber-100 p-5">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-semibold text-amber-800">Peringatan Stok Menipis</h3>
                <p class="text-sm text-amber-700 mt-1">Terdapat <strong>{{ $barangMenipis }}</strong> barang dengan stok di bawah batas minimum.</p>
            </div>
            <a href="{{ route('stock.index') }}" class="text-sm text-amber-700 hover:text-amber-800 font-medium">Cek Stok →</a>
        </div>
    </div>
    @endif

    <!-- Recent Activity -->
    <div class="mt-8 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700">Aktivitas Terbaru</h3>
                    <p class="text-xs text-gray-400 mt-0.5">5 transaksi terakhir</p>
                </div>
                <a href="{{ route('history.index') }}" class="text-xs text-blue-600 hover:text-blue-700">Lihat semua →</a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr class="text-left text-xs font-medium text-gray-500">
                        <th class="px-5 py-3">Barang</th>
                        <th class="px-5 py-3">Tipe</th>
                        <th class="px-5 py-3">Jumlah</th>
                        <th class="px-5 py-3">Tanggal</th>
                     </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentTransactions ?? [] as $transaction)
                    <tr class="text-sm">
                        <td class="px-5 py-3 text-gray-800">{{ $transaction->consumable->name ?? '-' }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2 py-0.5 text-xs rounded-full {{ $transaction->type == 'IN' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $transaction->type == 'IN' ? 'Masuk' : 'Keluar' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-800">{{ number_format($transaction->quantity) }}</td>
                        <td class="px-5 py-3 text-gray-400 text-xs">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                     </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada aktivitas</td>
                     </tr>
                    @endforelse
                </tbody>
             </table>
        </div>
    </div>

</div>

@endsection