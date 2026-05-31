@extends('layouts.dashboard-sidebar')

@section('title', 'Approval Request - Inventaris Stikubank')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Approval Request</h1>
            <p class="text-sm text-gray-500 mt-1">Approve atau reject permintaan barang</p>
        </div>
        <button onclick="window.location.reload()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Total Request</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalRequests ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $totalPending ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Approved</p>
            <p class="text-2xl font-bold text-green-600">{{ $totalApproved ?? 0 }}</p>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('admin.requests') }}" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Cari nama barang..." 
                class="flex-1 min-w-[200px] rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            
            <select name="type" class="rounded-lg border border-gray-300 px-4 py-2 text-sm w-36">
                <option value="">Semua Tipe</option>
                <option value="IN" {{ request('type') == 'IN' ? 'selected' : '' }}>Barang Masuk</option>
                <option value="OUT" {{ request('type') == 'OUT' ? 'selected' : '' }}>Barang Keluar</option>
            </select>

            <select name="status" class="rounded-lg border border-gray-300 px-4 py-2 text-sm w-32">
                <option value="">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.requests') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-redo mr-1"></i> Reset
            </a>
        </form>
    </div>

    <!-- Tabel Request -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-left text-xs font-medium text-gray-500">
                        <th class="px-6 py-4">Barang</th>
                        <th class="px-6 py-4">Jumlah</th>
                        <th class="px-6 py-4">Tipe</th>
                        <th class="px-6 py-4">Diminta Oleh</th>
                        <th class="px-6 py-4">Catatan</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $req)
                    <tr class="text-sm hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $req->consumable->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">Request #{{ $req->id }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800">{{ number_format($req->quantity) }}</span>
                            <span class="text-xs text-gray-400 ml-1">{{ $req->consumable->unitMeasure->name ?? '' }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($req->type == 'IN')
                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                    <i class="fas fa-arrow-down mr-1 text-xs"></i> Masuk
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-red-100 text-red-700">
                                    <i class="fas fa-arrow-up mr-1 text-xs"></i> Keluar
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-800">{{ $req->user->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $req->user->email ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs max-w-xs truncate">
                            {{ $req->note ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $req->created_at ? $req->created_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($req->status === 'pending')
                                <div class="flex items-center justify-center gap-2">
                                    <form method="POST" action="{{ route('admin.requests.approve', $req->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1.5 rounded-lg transition"
                                            onclick="return confirm('Approve request barang {{ $req->consumable->name ?? '' }}?')">
                                            <i class="fas fa-check mr-1"></i> Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.requests.reject', $req->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1.5 rounded-lg transition"
                                            onclick="return confirm('Reject request barang {{ $req->consumable->name ?? '' }}?')">
                                            <i class="fas fa-times mr-1"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs rounded-full 
                                    {{ $req->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    @if($req->status === 'approved')
                                        <i class="fas fa-check-circle mr-1 text-xs"></i> Approved
                                    @else
                                        <i class="fas fa-times-circle mr-1 text-xs"></i> Rejected
                                    @endif
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-clipboard-list text-3xl mb-2 block"></i>
                            Tidak ada data request
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($requests->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $requests->links() }}
        </div>
        @endif
    </div>

    <!-- Informasi -->
    <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-r-xl p-4">
        <div class="flex items-center gap-3">
            <i class="fas fa-info-circle text-yellow-500"></i>
            <div class="text-sm text-yellow-800">
                Request yang sudah <strong>Approved</strong> akan otomatis menambah/mengurangi stok barang. 
                Request yang <strong>Rejected</strong> akan dihapus.
            </div>
        </div>
    </div>
</div>
@endsection