@extends('layouts.dashboard-sidebar')

@section('title', 'Manajemen Peminjaman - Inventaris Stikubank')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Manajemen Peminjaman
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Approval dan pengembalian barang pinjaman
            </p>
        </div>

        <a href="{{ route('barang.index') }}"
           class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
        </a>
    </div>

    <!-- Statistik (5 CARD) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">

        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs text-gray-400">Total</p>
            <p class="text-2xl font-bold text-gray-800">
                {{ $totalBorrowings ?? $borrowings->total() }}
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs text-gray-400">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">
                {{ $pendingBorrowings ?? 0 }}
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs text-gray-400">Dipinjam</p>
            <p class="text-2xl font-bold text-blue-600">
                {{ $activeBorrowings ?? 0 }}
            </p>
        </div>

        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs text-gray-400">Dikembalikan</p>
            <p class="text-2xl font-bold text-green-600">
                {{ $returnedBorrowings ?? 0 }}
            </p>
        </div>

        <!-- Card Terlambat -->
        <div class="bg-white rounded-xl border border-gray-100 p-4 shadow-sm">
            <p class="text-xs text-gray-400">Terlambat</p>
            <p class="text-2xl font-bold text-red-600">
                {{ $lateBorrowings ?? 0 }}
            </p>
        </div>

    </div>

    <!-- Form Filter -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('admin.borrowings') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs text-gray-500 mb-1">Cari</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Nama peminjam atau nama barang..."
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div class="w-40">
                <label class="block text-xs text-gray-500 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="BORROWED" {{ request('status') == 'BORROWED' ? 'selected' : '' }}>Dipinjam</option>
                    <option value="RETURNED" {{ request('status') == 'RETURNED' ? 'selected' : '' }}>Dikembalikan</option>
                    <option value="REJECTED" {{ request('status') == 'REJECTED' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            
            @if(request('search') || (request('status') && request('status') != 'all'))
            <a href="{{ route('admin.borrowings') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-times mr-1"></i> Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Tabel Data Peminjaman -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-left text-xs font-medium text-gray-500">
                        <th class="px-6 py-4">Barang</th>
                        <th class="px-6 py-4">Peminjam</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4">Tanggal Pengajuan</th>
                        <th class="px-6 py-4">Tanggal Peminjaman</th>
                        <th class="px-6 py-4">Tanggal Pengembalian</th>
                        <th class="px-6 py-4">Tanggal Dikembalikan</th>
                        <th class="px-6 py-4">Keterlambatan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Catatan</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($borrowings as $borrowing)
                    @php
                        $submissionDate = $borrowing->created_at;
                        $borrowDate = $borrowing->borrow_date ? \Carbon\Carbon::parse($borrowing->borrow_date) : null;
                        $returnDate = $borrowing->return_date ? \Carbon\Carbon::parse($borrowing->return_date) : null;
                        $returnedDate = $borrowing->actual_return_date
                            ? \Carbon\Carbon::parse($borrowing->actual_return_date)
                            : ($borrowing->returned_at ? \Carbon\Carbon::parse($borrowing->returned_at) : null);
                        $lateDays = 0;

                        if ($returnDate) {
                            if ($returnedDate && $returnedDate->startOfDay()->gt($returnDate->copy()->startOfDay())) {
                                $lateDays = $returnDate->copy()->startOfDay()->diffInDays($returnedDate->copy()->startOfDay());
                            } elseif (!$returnedDate && $borrowing->status == 'BORROWED' && now()->startOfDay()->gt($returnDate->copy()->startOfDay())) {
                                $lateDays = $returnDate->copy()->startOfDay()->diffInDays(now()->startOfDay());
                            }
                        }
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">
                                {{ $borrowing->consumable->name ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $borrowing->consumable->unitMeasure->name ?? '' }}
                            </p>
                        </td>

                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">
                                {{ $borrowing->borrower_name }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $borrowing->user->name ?? '-' }}
                            </p>
                        </td>

                        <td class="px-6 py-4 font-semibold">
                            {{ number_format($borrowing->quantity) }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $submissionDate ? $submissionDate->format('d/m/Y H:i') : '-' }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $borrowDate ? $borrowDate->format('d/m/Y') : '-' }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $returnDate ? $returnDate->format('d/m/Y') : '-' }}
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $returnedDate ? $returnedDate->format('d/m/Y') : '-' }}
                        </td>

                        <!-- Kolom Keterlambatan -->
                        <td class="px-6 py-4 text-sm">
                            @if($lateDays > 0)
                                <span class="font-semibold text-red-600">
                                    {{ $lateDays }} hari
                                </span>
                            @else
                                -
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            @if($borrowing->status == 'PENDING')
                                <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">
                                    Pending
                                </span>
                            @elseif($borrowing->status == 'BORROWED')
                                @if($lateDays > 0)
                                    <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                        Terlambat
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                                        Dipinjam
                                    </span>
                                @endif
                            @elseif($borrowing->status == 'RETURNED')
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">
                                    Dikembalikan
                                </span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                    Ditolak
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $borrowing->note ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            @if($borrowing->status == 'PENDING')
                                <div class="flex flex-col gap-2 min-w-[220px]">
                                    <form method="POST" action="{{ route('admin.borrowings.approve', $borrowing->id) }}" class="space-y-2">
                                        @csrf
                                        <select name="consumable_stock_id" required class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                                            <option value="">Pilih lokasi asal</option>
                                            @foreach(($borrowing->consumable->stocks ?? collect())->where('quantity', '>', 0) as $stockLocation)
                                                <option value="{{ $stockLocation->id }}">
                                                    {{ $stockLocation->location->name ?? '-' }} ({{ number_format($stockLocation->quantity) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit"
                                                onclick="return confirm('Approve peminjaman ini?')"
                                                class="w-full bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2.5 rounded-lg">
                                            <i class="fas fa-check mr-1"></i> Approve
                                        </button>
                                    </form>

                                    <button type="button"
                                            onclick="showRejectModal({{ $borrowing->id }})"
                                            class="w-full bg-red-600 hover:bg-red-700 text-white text-sm px-4 py-2.5 rounded-lg">
                                        <i class="fas fa-times mr-1"></i> Reject
                                    </button>
                                </div>
                            @elseif($borrowing->status == 'BORROWED')
                                <form method="POST" action="{{ route('admin.borrowings.return', $borrowing->id) }}">
                                    @csrf
                                    <button type="submit"
                                            onclick="return confirm('Barang sudah dikembalikan?')"
                                            class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2.5 rounded-lg">
                                        <i class="fas fa-undo mr-1"></i>
                                        Konfirmasi Kembali
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-gray-400">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-12 text-gray-400">
                            <i class="fas fa-box-open text-3xl mb-3 block"></i>
                            Belum ada data peminjaman
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($borrowings->hasPages())
            <div class="p-4 border-t">
                {{ $borrowings->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md">
        <h3 class="text-lg font-bold mb-4">Tolak Peminjaman</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Alasan Penolakan
                </label>
                <textarea name="rejection_reason" rows="3" 
                          placeholder="Masukkan alasan penolakan..."
                          class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"></textarea>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeRejectModal()" 
                        class="border border-gray-300 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                    Batal
                </button>
                <button type="submit" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm transition">
                    Ya, Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript untuk Modal -->
<script>
    // Fungsi untuk menampilkan modal reject dan mengatur action form secara manual
    function showRejectModal(borrowingId) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        
        // Membuat URL manual sesuai pattern route admin.borrowings.reject
        // Pattern: admin/borrowings/{id}/reject
        form.action = '/admin/borrowings/' + borrowingId + '/reject';
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Fungsi untuk menutup modal reject
    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        
        // Reset form action agar tidak bentrok untuk next use
        const form = document.getElementById('rejectForm');
        form.action = '';
    }
</script>

@endsection
