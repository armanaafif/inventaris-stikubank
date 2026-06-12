@extends('layouts.dashboard-sidebar')

@section('title', 'Daftar Barang - Inventaris Stikubank')
@section('page-title', 'Daftar Barang')
@section('page-subtitle', 'Kelola data barang inventaris')

@section('content')
<div class="space-y-6">
    
    <!--
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    -->

    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Daftar Barang</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola data barang inventaris</p>
        </div>
        <a href="{{ route('barang.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Barang
        </a>
    </div>

    <!--
    |--------------------------------------------------------------------------
    | STATISTIK CARDS
    |--------------------------------------------------------------------------
    -->

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Total Barang</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $data->total() }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-boxes text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Total Stok</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($data->sum(function($item) { return $item->stock ?? 0; })) }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Barang Aktif</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $data->where('status', 'AKTIF')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400">Barang Nonaktif</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $data->where('status', 'NONAKTIF')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-rose-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-ban text-rose-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!--
    |--------------------------------------------------------------------------
    | SEARCH & FILTER
    |--------------------------------------------------------------------------
    -->

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('barang.index') }}" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Cari nama barang..." 
                class="flex-1 min-w-[200px] rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            
            <select name="condition" class="rounded-lg border border-gray-300 px-4 py-2 text-sm w-36">
                <option value="">Semua Kondisi</option>
                <option value="BARU" {{ request('condition') == 'BARU' ? 'selected' : '' }}>Baru</option>
                <option value="BEKAS" {{ request('condition') == 'BEKAS' ? 'selected' : '' }}>Bekas</option>
                <option value="LAYAK" {{ request('condition') == 'LAYAK' ? 'selected' : '' }}>Layak</option>
                <option value="RUSAK" {{ request('condition') == 'RUSAK' ? 'selected' : '' }}>Rusak</option>
            </select>

            <select name="status" class="rounded-lg border border-gray-300 px-4 py-2 text-sm w-36">
                <option value="">Semua Status</option>
                <option value="AKTIF" {{ request('status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                <option value="NONAKTIF" {{ request('status') == 'NONAKTIF' ? 'selected' : '' }}>Nonaktif</option>
            </select>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('barang.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-redo mr-1"></i> Reset
            </a>
        </form>
    </div>

    <!--
    |--------------------------------------------------------------------------
    | TABEL BARANG
    |--------------------------------------------------------------------------
    -->

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-left text-xs font-medium text-gray-500">
                        <th class="px-6 py-4">Nama Barang</th>
                        <th class="px-6 py-4">Satuan</th>
                        <th class="px-6 py-4">Stok</th>
                        <th class="px-6 py-4">Min Stok</th>
                        <th class="px-6 py-4">Kondisi</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data as $item)
                    <tr class="text-sm hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">{{ $item->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">ID: {{ $item->id }}</p>
                         </td>
                        <td class="px-6 py-4 text-gray-600">{{ $item->unitMeasure->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="font-semibold {{ ($item->stock ?? 0) <= $item->minimum_stock ? 'text-red-600' : 'text-gray-800' }}">
                                {{ number_format($item->stock ?? 0) }}
                            </span>
                         </td>
                        <td class="px-6 py-4 text-gray-600">{{ number_format($item->minimum_stock) }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs rounded-full 
                                {{ $item->condition == 'BARU' ? 'bg-blue-100 text-blue-700' : 
                                   ($item->condition == 'BEKAS' ? 'bg-yellow-100 text-yellow-700' : 
                                   ($item->condition == 'LAYAK' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700')) }}">
                                {{ $item->condition }}
                            </span>
                         </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $item->status == 'AKTIF' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $item->status }}
                            </span>
                         </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <!-- Tombol Detail -->
                                <a href="{{ route('barang.show', $item->id) }}" class="text-blue-600 hover:text-blue-800 transition" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                <!-- Tombol Hapus dengan Alert Konfirmasi -->
                                <form method="POST" action="{{ route('barang.destroy', $item->id) }}" class="inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" 
                                            class="text-red-600 hover:text-red-800 transition delete-btn"
                                            data-name="{{ $item->name }}"
                                            data-id="{{ $item->id }}"
                                            title="Hapus">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                          </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-box-open text-4xl mb-2 block"></i>
                            Belum ada data barang
                          </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($data->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $data->links() }}
        </div>
        @endif
    </div>

    <!--
    |--------------------------------------------------------------------------
    | INFORMASI
    |--------------------------------------------------------------------------
    -->

    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-r-xl p-4">
        <div class="flex items-center gap-3">
            <i class="fas fa-info-circle text-blue-500"></i>
            <div class="text-sm text-blue-800">
                <p><strong>Catatan:</strong> Menghapus barang akan menghapus seluruh riwayat transaksi barang tersebut. Pastikan data yang akan dihapus sudah benar.</p>
            </div>
        </div>
    </div>
</div>

<!--
|--------------------------------------------------------------------------
| SWEETALERT UNTUK KONFIRMASI HAPUS
|--------------------------------------------------------------------------
-->

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Konfigurasi SweetAlert untuk tombol hapus
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const form = this.closest('.delete-form');
            const namaBarang = this.getAttribute('data-name');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                html: `Anda akan menghapus barang <strong>${namaBarang}</strong>.<br>Data yang terkait akan hilang!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

<!--
|--------------------------------------------------------------------------
| SWEETALERT NOTIFIKASI SUKSES / ERROR
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

@if(session('approval_pending'))
<script>
    Swal.fire({
        icon: 'info',
        title: 'Menunggu Approval Admin',
        text: '{{ session('approval_pending') }}',
        confirmButtonColor: '#2563eb'
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
