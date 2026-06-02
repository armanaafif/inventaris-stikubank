@extends('layouts.dashboard-sidebar')

@section('title', 'Detail Barang - Inventaris Stikubank')
@section('page-title', 'Detail Barang')
@section('page-subtitle', 'Kelola stok dan lihat informasi barang')

@section('content')

<!--
|--------------------------------------------------------------------------
| INFORMASI BARANG (DI ATAS, FULL WIDTH)
|--------------------------------------------------------------------------
-->

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-5 py-3">
        <div class="flex items-center gap-2">
            <i class="fas fa-box text-white"></i>
            <h3 class="font-semibold text-white">Informasi Barang</h3>
        </div>
    </div>
    
    <div class="p-5">
        <div class="mb-4 pb-3 border-b border-gray-100">
            <p class="text-xs text-gray-400 uppercase tracking-wide">Nama Barang</p>
            <p class="text-xl font-bold text-gray-800">{{ $item->name }}</p>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Satuan</p>
                <p class="text-base font-semibold text-gray-700 mt-1">{{ $item->unitMeasure->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Minimum Stok</p>
                <p class="text-base font-semibold text-gray-700 mt-1">{{ number_format($item->minimum_stock) }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Kondisi</p>
                <div class="mt-1">
                    <span class="inline-flex px-2 py-1 text-xs rounded-full 
                        {{ $item->condition == 'BARU' ? 'bg-blue-100 text-blue-700' : 
                           ($item->condition == 'BEKAS' ? 'bg-yellow-100 text-yellow-700' : 
                           ($item->condition == 'LAYAK' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700')) }}">
                        {{ $item->condition }}
                    </span>
                </div>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide">Status</p>
                <div class="mt-1">
                    <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $item->status == 'AKTIF' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ $item->status }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="pt-3 border-t border-gray-100">
            <div class="flex items-center justify-between mb-2">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Stok Saat Ini</p>
                <p class="text-2xl font-bold {{ ($stock ?? 0) <= $item->minimum_stock ? 'text-red-600' : 'text-blue-600' }}">
                    {{ number_format($stock ?? 0) }}
                </p>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                @php
                    $maxStock = max($stock ?? 0, $item->minimum_stock);
                    $percentage = $maxStock > 0 ? min(100, (($stock ?? 0) / $maxStock) * 100) : 0;
                @endphp
                <div class="h-2 rounded-full transition-all duration-500 {{ ($stock ?? 0) <= $item->minimum_stock ? 'bg-red-500' : 'bg-blue-600' }}" 
                     style="width: {{ $percentage }}%"></div>
            </div>
            @if(($stock ?? 0) <= $item->minimum_stock)
                <p class="text-xs text-red-500 mt-2">
                    <i class="fas fa-exclamation-triangle mr-1"></i> Stok menipis! Segera lakukan restock.
                </p>
            @endif
        </div>
    </div>
</div>

<!--
|--------------------------------------------------------------------------
| FORM TAMBAH STOK & GUNAKAN BARANG (BERJEJER 2 KOLOM)
|--------------------------------------------------------------------------
-->

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-100 bg-gradient-to-r from-green-50 to-white px-5 py-3">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                    <i class="fas fa-plus-circle text-green-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Tambah Stok</h3>
                    <p class="text-xs text-gray-400">Menambahkan stok barang ke inventaris</p>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('stock.add') }}" class="p-5">
            @csrf
            <input type="hidden" name="consumable_id" value="{{ $item->id }}">
            
            <div class="mb-4">
                <label for="add_quantity" class="block text-sm font-medium text-gray-700 mb-1">
                    Jumlah <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       id="add_quantity" 
                       name="quantity" 
                       required
                       min="1"
                       placeholder="Masukkan jumlah stok"
                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
            </div>
            
            <div class="mb-4">
                <label for="add_note" class="block text-sm font-medium text-gray-700 mb-1">
                    Catatan
                </label>
                <textarea id="add_note" 
                          name="note" 
                          rows="2"
                          placeholder="Contoh: Restock pembelian baru"
                          class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"></textarea>
            </div>
            
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 rounded-xl transition flex items-center justify-center gap-2">
                <i class="fas fa-plus-circle"></i> Tambah Stok
            </button>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-100 bg-gradient-to-r from-red-50 to-white px-5 py-3">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                    <i class="fas fa-minus-circle text-red-600"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Gunakan Barang</h3>
                    <p class="text-xs text-gray-400">Mengurangi stok barang dari inventaris</p>
                </div>
            </div>
        </div>
        
        <form method="POST" action="{{ route('stock.take') }}" class="p-5">
            @csrf
            <input type="hidden" name="consumable_id" value="{{ $item->id }}">
            
            <div class="mb-4">
                <label for="take_quantity" class="block text-sm font-medium text-gray-700 mb-1">
                    Jumlah <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       id="take_quantity" 
                       name="quantity" 
                       required
                       min="1"
                       max="{{ $stock ?? 0 }}"
                       placeholder="Masukkan jumlah barang"
                       class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                @if(($stock ?? 0) <= 0)
                    <p class="text-xs text-red-500 mt-1">Stok habis, tidak dapat menggunakan barang.</p>
                @else
                    <p class="text-xs text-gray-400 mt-1">Maksimal: {{ number_format($stock ?? 0) }}</p>
                @endif
            </div>
            
            <div class="mb-4">
                <label for="take_note" class="block text-sm font-medium text-gray-700 mb-1">
                    Catatan
                </label>
                <textarea id="take_note" 
                          name="note" 
                          rows="2"
                          placeholder="Contoh: Digunakan untuk operasional"
                          class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 transition"></textarea>
            </div>
            
            <button type="submit" 
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 rounded-xl transition flex items-center justify-center gap-2"
                    {{ ($stock ?? 0) <= 0 ? 'disabled' : '' }}>
                <i class="fas fa-minus-circle"></i> Gunakan Barang
            </button>
        </form>
    </div>
</div>

<!--
|--------------------------------------------------------------------------
| FORM PINJAM BARANG (BARIS KETIGA) - SUDAH DIPERBAIKI
|--------------------------------------------------------------------------
-->

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="border-b border-gray-100 bg-gradient-to-r from-purple-50 to-white px-5 py-3">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                <i class="fas fa-hand-holding text-purple-600"></i>
            </div>
            <div>
                <h3 class="font-semibold text-gray-800">Pinjam Barang</h3>
                <p class="text-xs text-gray-400">Mencatat peminjaman barang oleh pengguna</p>
            </div>
        </div>
    </div>
    
    <!-- PERBAIKAN: route name sudah diganti dari borrowing.store menjadi borrow.item -->
    <form method="POST" action="{{ route('borrow.item') }}" class="p-5">
        @csrf
        <input type="hidden" name="consumable_id" value="{{ $item->id }}">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <div class="mb-4">
                    <label for="borrower_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Peminjam <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="borrower_name" 
                           name="borrower_name" 
                           required
                           placeholder="Masukkan nama peminjam"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                </div>
                
                <div class="mb-4">
                    <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">
                        Jumlah Pinjam <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="quantity" 
                           name="quantity" 
                           required
                           min="1"
                           max="{{ $stock ?? 0 }}"
                           placeholder="Masukkan jumlah barang yang dipinjam"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                    @if(($stock ?? 0) <= 0)
                        <p class="text-xs text-red-500 mt-1">Stok habis, tidak dapat melakukan peminjaman.</p>
                    @else
                        <p class="text-xs text-gray-400 mt-1">Maksimal peminjaman: {{ number_format($stock ?? 0) }}</p>
                    @endif
                </div>
            </div>
            
            <div>
                <div class="mb-4">
                    <label for="return_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Tanggal Kembali <span class="text-red-500">*</span>
                    </label>
                    <input type="date" 
                           id="return_date" 
                           name="return_date" 
                           required
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition">
                    <p class="text-xs text-gray-400 mt-1">Minimal H+1 dari hari ini</p>
                </div>
                
                <div class="mb-4">
                    <label for="note" class="block text-sm font-medium text-gray-700 mb-1">
                        Catatan Peminjaman
                    </label>
                    <textarea id="note" 
                              name="note" 
                              rows="2"
                              placeholder="Contoh: Untuk keperluan rapat / event"
                              class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition"></textarea>
                </div>
            </div>
        </div>
        
        <button type="submit" 
                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 rounded-xl transition flex items-center justify-center gap-2 mt-2"
                {{ ($stock ?? 0) <= 0 ? 'disabled' : '' }}>
            <i class="fas fa-hand-holding"></i> Pinjam Barang
        </button>
    </form>
</div>

<!--
|--------------------------------------------------------------------------
| TOMBOL KEMBALI
|--------------------------------------------------------------------------
-->

<div class="flex justify-start mb-8">
    <a href="{{ route('barang.index') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 transition text-sm font-medium">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Barang
    </a>
</div>

<!--
|--------------------------------------------------------------------------
| RIWAYAT TRANSAKSI BARANG INI
|--------------------------------------------------------------------------
-->

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="border-b border-gray-100 bg-gray-50 px-5 py-3">
        <div class="flex items-center gap-2">
            <i class="fas fa-history text-gray-500"></i>
            <h3 class="font-semibold text-gray-800">Riwayat Transaksi</h3>
            <span class="text-xs text-gray-400">Histori barang masuk dan keluar</span>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b">
                <tr class="text-left text-xs font-medium text-gray-500">
                    <th class="px-5 py-3">Tipe</th>
                    <th class="px-5 py-3">Jumlah</th>
                    <th class="px-5 py-3">Catatan</th>
                    <th class="px-5 py-3">Tanggal</th>
                  </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($transactions as $trx)
                <tr class="text-sm hover:bg-gray-50 transition">
                    <td class="px-5 py-3">
                        @if($trx->type == 'IN')
                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                <i class="fas fa-arrow-down text-xs"></i> Masuk
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                <i class="fas fa-arrow-up text-xs"></i> Keluar
                            </span>
                        @endif
                      </td>
                    <td class="px-5 py-3 font-semibold {{ $trx->type == 'IN' ? 'text-green-600' : 'text-red-600' }}">
                        {{ number_format($trx->quantity) }}
                      </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">
                        {{ $trx->note ?? '-' }}
                      </td>
                    <td class="px-5 py-3 text-gray-500 text-xs">
                        {{ $trx->created_at->format('d/m/Y H:i') }}
                      </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="4" class="px-5 py-10 text-center text-gray-400">
                        <i class="fas fa-history text-3xl mb-2 block text-gray-300"></i>
                        Belum ada transaksi untuk barang ini
                      </td>
                  </tr>
                @endforelse
            </tbody>
          </table>
    </div>
</div>

<!--
|--------------------------------------------------------------------------
| SWEETALERT NOTIFIKASI
|--------------------------------------------------------------------------
-->

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#10b981',
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#ef4444'
    });
</script>
@endif
@endsection