@extends('layouts.dashboard-sidebar')

@section('title', 'Daftar Barang - Inventaris Stikubank')
@section('page-title', 'Daftar Barang')
@section('page-subtitle', 'Kelola data barang inventaris')

@section('content')
<div id="barangListContent" class="space-y-6">
    
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
        <button type="button" onclick="openRemoteFormVariant('{{ route('barang.create') }}', 'Tambah Barang', 'add-item')" class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Barang
        </button>
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
                        <th class="px-6 py-4">Kode Barang</th>
                        <th class="px-6 py-4">Nama Barang</th>
                        <th class="px-6 py-4">Inventory Type</th>
                        <th class="px-6 py-4">Stock</th>
                        <th class="px-6 py-4">Lokasi Utama</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($data as $item)
                    <tr class="text-sm hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $item->item_code ?? '-' }}</td>
                        <td class="px-6 py-4"><p class="font-semibold text-gray-900">{{ $item->name }}</p><p class="mt-0.5 text-xs text-gray-400">{{ $item->category->name ?? '-' }}@if($item->brand) · {{ $item->brand }}@endif</p></td>
                        <td class="px-6 py-4">
                            <span class="rounded-full px-2 py-1 text-xs font-semibold {{ ($item->inventory_type ?? 'UNIT') === 'CONTINUOUS' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ $item->inventory_type ?? 'UNIT' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold {{ !is_null($item->minimum_stock) && ($item->stock ?? 0) <= $item->minimum_stock ? 'text-red-600' : 'text-gray-800' }}">
                                {{ $item->stock_display ?? (number_format($item->stock ?? 0) . ' ' . ($item->unitMeasure->name ?? '')) }}
                            </span>
                         </td>
                        <td class="px-6 py-4 text-gray-600"><p>{{ $item->stocks->first()?->location?->name ?? '-' }}</p>@if($item->stocks->count() > 1)<p class="text-xs text-blue-600">+{{ $item->stocks->count() - 1 }} lokasi</p>@endif</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs rounded-full {{ $item->status == 'AKTIF' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $item->status }}
                            </span>
                         </td>
                        <td class="px-6 py-4 text-center">
                            <div class="relative inline-block text-left item-actions">
                                <button type="button" onclick="toggleItemMenu({{ $item->id }}, this)" class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-gray-800" aria-label="Menu aksi"><i class="fas fa-ellipsis-v"></i></button>
                                <div id="item-menu-{{ $item->id }}" class="absolute right-0 z-50 mt-2 hidden w-48 overflow-hidden rounded-xl border border-gray-100 bg-white py-1 text-left shadow-xl">
                                    <button type="button" onclick="openDetail({{ $item->id }});closeItemMenus()" class="menu-action text-blue-700"><i class="fas fa-eye"></i> Detail Barang</button>
                                    <button type="button" onclick="openRemoteFormVariant('{{ route('barang.edit', $item->id) }}', 'Edit Barang', 'edit-item');closeItemMenus()" class="menu-action text-amber-700"><i class="fas fa-pen"></i> Edit Barang</button>
                                    <button type="button" onclick="openAction({{ $item->id }}, 'add');closeItemMenus()" class="menu-action text-emerald-700"><i class="fas fa-plus"></i> Tambah Stok</button>
                                    <button type="button" onclick="openAction({{ $item->id }}, 'transfer');closeItemMenus()" class="menu-action text-indigo-700"><i class="fas fa-exchange-alt"></i> Transfer Lokasi</button>
                                    <button type="button" onclick="openAction({{ $item->id }}, 'borrow');closeItemMenus()" class="menu-action text-sky-700"><i class="fas fa-hand-holding"></i> Pinjam Barang</button>
                                    <button type="button" onclick="openAction({{ $item->id }}, 'take');closeItemMenus()" class="menu-action text-orange-700"><i class="fas fa-minus"></i> Gunakan Barang</button>
                                    <div class="my-1 border-t"></div><button type="button" data-name="{{ $item->name }}" onclick="confirmDelete({{ $item->id }}, this.dataset.name, this);closeItemMenus()" class="menu-action text-red-700"><i class="fas fa-trash"></i> Hapus Barang</button>
                                </div>
                                <form method="POST" action="{{ route('barang.destroy', $item->id) }}" class="inline delete-form hidden">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="inline-flex items-center gap-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700 shadow-sm transition hover:bg-red-100 delete-btn"
                                            data-name="{{ $item->name }}"
                                            data-id="{{ $item->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M3 6h18"></path>
                                            <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                            <path d="M10 11v6"></path>
                                            <path d="M14 11v6"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                                </div>
                            </div>
                          </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
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

<style>
    .action-btn { display:inline-flex; align-items:center; gap:.25rem; border-radius:.5rem; padding:.5rem .625rem; font-size:.75rem; font-weight:600; color:#fff; box-shadow:0 1px 2px rgba(0,0,0,.08); transition:filter .15s; }
    .action-btn:hover { filter:brightness(.94); }
    #barangModal .field { display:block; width:100%; margin-top:.25rem; border:1px solid #d1d5db; border-radius:.75rem; padding:.625rem .75rem; font-size:.875rem; }
    .menu-action { display:flex; width:100%; align-items:center; gap:.65rem; padding:.6rem 1rem; font-size:.875rem; text-align:left; }
    .menu-action:hover { background:#f8fafc; }
    .detail-card { border:1px solid #e2e8f0; border-radius:.75rem; background:#fff; padding:.75rem; }
    .detail-card p { font-size:.72rem; color:#94a3b8; }
    .detail-card b { display:block; margin-top:.2rem; font-size:.95rem; color:#1e293b; }
    .detail-line { display:flex; justify-content:space-between; gap:1rem; }
    .detail-line dt { color:#94a3b8; }.detail-line dd { color:#334155; font-weight:500; text-align:right; }
    #barangModal .field { display:block; width:100%; margin-top:.5rem; border:1px solid #d1d5db; border-radius:1rem; padding:.75rem 1rem; font-size:.9375rem; }
    .modal-section { border:1px solid #e2e8f0; border-radius:1rem; background:#ffffff; padding:1rem; }
    .modal-section h4 { margin-bottom:.75rem; font-size:.95rem; color:#1f2937; font-weight:700; }
</style>
<div id="barangModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 p-4" role="dialog" aria-modal="true" onclick="if(event.target===this)closeBarangModal()">
    <div id="barangModalDialog" class="flex w-full max-w-3xl flex-col overflow-hidden rounded-xl bg-white shadow-xl animate-[fadeIn_.15s_ease-out]">
        <div class="flex items-center justify-between border-b px-6 py-3"><h3 id="barangModalTitle" class="text-[20px] font-semibold text-gray-800">Barang</h3><button type="button" onclick="closeBarangModal()" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100"><i class="fas fa-times"></i></button></div>
        <div id="barangModalBody" class="min-h-[140px] overflow-y-auto px-6 py-4"><div class="py-14 text-center text-gray-400"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-3">Memuat data...</p></div></div>
        <div id="barangModalFooter" class="sticky bottom-0 flex justify-end border-t bg-gray-50 px-6 py-3"><button type="button" onclick="closeBarangModal()" class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700">Tutup</button></div>
    </div>
</div>

<script>
const modal = document.getElementById('barangModal'), dialog = document.getElementById('barangModalDialog'), modalBody = document.getElementById('barangModalBody'), modalTitle = document.getElementById('barangModalTitle');
const endpoint = {add:'{{ route('stock.add') }}', take:'{{ route('stock.take') }}', transfer:'{{ route('stock.transfer') }}', borrow:'{{ route('borrow.item') }}'};

function applyModalVariant(variant){
    // reset known size classes
    dialog.classList.remove('max-w-3xl','max-w-2xl','max-w-xl');
    // default sizes
    let maxw = 'max-w-3xl';
    let maxh = '85vh';
    switch(variant){
        case 'add-stock': maxw='max-w-2xl'; maxh='70vh'; break;
        case 'transfer': maxw='max-w-xl'; maxh='50vh'; break;
        case 'borrow': maxw='max-w-2xl'; maxh='75vh'; break;
        case 'detail': maxw='max-w-3xl'; maxh='70vh'; break;
        case 'add-item': maxw='max-w-3xl'; maxh='85vh'; break;
        case 'edit-item': maxw='max-w-3xl'; maxh='85vh'; break;
        default: maxw='max-w-3xl'; maxh='80vh';
    }
    dialog.classList.add(maxw);
    dialog.style.maxHeight = maxh;
}

function openBarangModal(title, variant='default'){ modalTitle.textContent=title; applyModalVariant(variant); modal.classList.replace('hidden','flex'); document.body.classList.add('overflow-hidden'); }
function closeBarangModal(){ modal.classList.replace('flex','hidden'); document.body.classList.remove('overflow-hidden'); }
document.addEventListener('keydown', e=>{if(e.key==='Escape'&&!modal.classList.contains('hidden'))closeBarangModal();});
function loading(){modalBody.innerHTML='<div class="py-14 text-center text-gray-400"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-3">Memuat data...</p></div>';}
const esc=v=>String(v??'-').replace(/[&<>'"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[c]));
async function itemData(id){const r=await fetch(`/barang/${id}`,{headers:{Accept:'application/json'}});if(!r.ok)throw new Error();return r.json();}
function locationOptions(locations, onlyStock=false){return locations.filter(l=>!onlyStock||l.quantity>0).map(l=>`<option value="${l.id}">${esc(l.name)}${l.quantity!==undefined?' ('+l.quantity+')':''}</option>`).join('');}
function toggleQuickLocationPanel(){
    const panel = document.getElementById('quickLocationPanel');
    panel?.classList.toggle('hidden');
}
async function submitQuickLocation(button){
    const panel = document.getElementById('quickLocationPanel');
    const error = document.getElementById('quickLocationError');
    const select = document.getElementById('addStockLocationSelect');
    if (!panel || !select) return;
    const data = new FormData();
    data.append('name', panel.querySelector('[name="quick_location_name"]').value);
    data.append('building', panel.querySelector('[name="quick_location_building"]').value);
    data.append('floor', panel.querySelector('[name="quick_location_floor"]').value);
    data.append('room', panel.querySelector('[name="quick_location_room"]').value);
    button.disabled = true;
    try {
        const r = await fetch('{{ route('locations.quick-store') }}', {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}', Accept: 'application/json'},
            body: data
        });
        if (!r.ok) throw new Error('Lokasi gagal disimpan atau sudah ada.');
        const location = await r.json();
        select.add(new Option(location.name, location.id, true, true));
        panel.querySelectorAll('input').forEach(input => input.value = '');
        error?.classList.add('hidden');
        panel.classList.add('hidden');
    } catch (err) {
        if (error) {
            error.textContent = err.message;
            error.classList.remove('hidden');
        }
    } finally {
        button.disabled = false;
    }
}
async function openDetail(id){
  openBarangModal('Detail Barang');
  loading();
  try {
    const d = await itemData(id),
          i = d.item,
          rows = d.locations || [],
          locationList = rows.slice(0, 4).map(l => `<li class="flex justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 text-sm"><span>${esc(l.name)}</span><strong class="text-slate-700">${l.quantity} ${esc(i.unit)}</strong></li>`).join('') || '<li class="text-sm text-gray-500">Belum ada lokasi.</li>',
          moreLocations = rows.length > 4 ? `<p class="mt-2 text-xs text-gray-400">+${rows.length - 4} lokasi lain</p>` : '';

    modalBody.innerHTML = `
      <div class="space-y-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-xs text-gray-400">Kode Barang</p>
            <p class="font-semibold text-slate-900">${esc(i.item_code)}</p>
          </div>
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-xs text-gray-400">Kategori</p>
            <p class="font-semibold text-slate-900">${esc(i.category)}</p>
          </div>
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-xs text-gray-400">Satuan</p>
            <p class="font-semibold text-slate-900">${esc(i.unit)}</p>
          </div>
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <p class="text-xs text-gray-400">Minimum Stock</p>
            <p class="font-semibold text-slate-900">${i.minimum_stock ?? '-'}</p>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <p class="text-xs text-gray-400">Nama Barang</p>
            <p class="font-semibold text-slate-900">${esc(i.name)}</p>
          </div>
          <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <p class="text-xs text-gray-400">Status / Kondisi</p>
            <div class="mt-2 flex flex-wrap gap-2">
              <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">${esc(i.status)}</span>
              <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">${esc(i.condition)}</span>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <p class="text-xs text-gray-400">Merk / Model</p>
            <p class="font-semibold text-slate-900">${esc(i.brand)} / ${esc(i.model)}</p>
          </div>
          <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <p class="text-xs text-gray-400">Nomor Seri</p>
            <p class="font-semibold text-slate-900">${esc(i.serial_number)}</p>
          </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <h4 class="text-sm font-semibold text-slate-800 mb-2">Spesifikasi</h4>
            <p class="whitespace-pre-line text-sm text-slate-700">${esc(i.specification)}</p>
          </div>
          <div class="rounded-2xl border border-slate-200 bg-white p-4">
            <h4 class="text-sm font-semibold text-slate-800 mb-2">Deskripsi</h4>
            <p class="whitespace-pre-line text-sm text-slate-700">${esc(i.description)}</p>
          </div>
        </div>

        ${i.purchase_receipt_url ? `<a class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white" target="_blank" href="${i.purchase_receipt_url}"><i class="fas fa-file-invoice"></i>Lihat Nota</a>` : ''}

        <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
          <div class="flex items-center justify-between gap-3">
            <h4 class="text-sm font-semibold text-slate-800">Distribusi Lokasi</h4>
            ${moreLocations}
          </div>
          <ul class="mt-3 space-y-2">${locationList}</ul>
        </div>
      </div>`;
  } catch {
    modalBody.innerHTML = '<p class="py-10 text-center text-red-600">Detail tidak dapat dimuat.</p>';
  }
}
async function openRemoteForm(url,title){openBarangModal(title);loading();try{const html=await (await fetch(url)).text(),doc=new DOMParser().parseFromString(html,'text/html'),form=doc.querySelector('#edit-barang-form') || doc.querySelector('form[action]');if(!form)throw new Error();modalBody.innerHTML='<div class="modal-form"></div>';modalBody.firstElementChild.append(form);bindRemoteForm(modalBody.querySelector('form'));}catch{modalBody.innerHTML='<p class="py-10 text-center text-red-600">Form tidak dapat dimuat.</p>';}}
function bindContinuousFormControls(scope){
  const inventory = scope.querySelector('#inventory_type');
  inventory?.removeAttribute('onchange');
}
function isBarangStoreForm(form){
  return new URL(form.action, window.location.origin).pathname === new URL('{{ route('barang.store') }}', window.location.origin).pathname;
}
async function refreshBarangListFromResponse(response){
  const html = await response.text();
  const doc = new DOMParser().parseFromString(html, 'text/html');
  const nextContent = doc.querySelector('#barangListContent');
  const currentContent = document.querySelector('#barangListContent');
  if (!nextContent || !currentContent) return;
  currentContent.innerHTML = nextContent.innerHTML;
  if (response.redirected && response.url) {
    history.replaceState({}, '', response.url);
  }
}
function bindRemoteForm(form){bindContinuousFormControls(form);form.addEventListener('submit',async e=>{e.preventDefault();const button=form.querySelector('[type=submit]');button?.setAttribute('disabled','disabled');try{const shouldRefreshList=isBarangStoreForm(form);const r=await fetch(form.action,{method:form.method||'POST',headers:{Accept:'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},body:new FormData(form)});if(!r.ok){const d=await r.json().catch(()=>null);throw new Error(d?.message||'Data tidak dapat disimpan.');}if(shouldRefreshList){await refreshBarangListFromResponse(r);}closeBarangModal();}catch(err){alert(err.message);}finally{button?.removeAttribute('disabled');}});}
// Overriding helper that accepts modal variant
async function openRemoteFormVariant(url,title,variant='default'){ openBarangModal(title,variant); loading(); try { const html = await (await fetch(url)).text(), doc = new DOMParser().parseFromString(html,'text/html'), form = doc.querySelector('#edit-barang-form') || doc.querySelector('form[action]'); if(!form) throw new Error(); modalBody.innerHTML = '<div class="modal-form"></div>'; modalBody.firstElementChild.append(form); bindRemoteForm(modalBody.querySelector('form')); } catch { modalBody.innerHTML = '<p class="py-10 text-center text-red-600">Form tidak dapat dimuat.</p>'; } }
async function openAction(id,type){
    const titles = {add:'Tambah Stock', take:'Gunakan Barang', transfer:'Transfer Lokasi', borrow:'Pinjam Barang'};
    const variants = {add:'add-stock', take:'add-stock', transfer:'transfer', borrow:'borrow'};
    openBarangModal(titles[type]||'Aksi Barang', variants[type]||'default');
  loading();
  try {
    const d = await itemData(id),
          i = d.item,
          stock = d.stock,
          available = locationOptions(d.locations,true),
          all = locationOptions(d.available_locations);
    let fields = '';
        if(type==='add'){
            fields = `
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <div class="flex items-center justify-between gap-3">
                            <label class="block text-sm font-medium text-slate-700">Lokasi</label>
                            <button type="button" onclick="toggleQuickLocationPanel()" class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-800"><i class="fas fa-plus text-xs"></i> Tambah Lokasi</button>
                        </div>
                        <select id="addStockLocationSelect" required name="location_id" class="field"><option value="">Pilih Lokasi</option>${all}</select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Jumlah</label>
                        <input required type="number" min="0.01" step="0.01" name="quantity" class="field">
                    </div>
                </div>
                <div id="quickLocationPanel" class="hidden rounded-xl border border-blue-100 bg-blue-50 p-4">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <h5 class="text-sm font-semibold text-slate-800">Tambah Lokasi Baru</h5>
                        <button type="button" onclick="toggleQuickLocationPanel()" class="text-sm text-slate-500 hover:text-slate-700"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Nama Lokasi</label>
                            <input name="quick_location_name" class="field bg-white" placeholder="Contoh: Gudang A">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Gedung</label>
                            <input name="quick_location_building" class="field bg-white">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Lantai</label>
                            <input name="quick_location_floor" class="field bg-white">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-slate-700">Ruang</label>
                            <input name="quick_location_room" class="field bg-white">
                        </div>
                    </div>
                    <p id="quickLocationError" class="mt-3 hidden text-sm text-red-600"></p>
                    <div class="mt-4 flex justify-end gap-3">
                        <button type="button" onclick="toggleQuickLocationPanel()" class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-700">Batal</button>
                        <button type="button" onclick="submitQuickLocation(this)" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Simpan Lokasi</button>
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Merk</label>
                        <input name="brand" class="field" placeholder="Merk">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Model (opsional)</label>
                        <input name="model" class="field" placeholder="Model / Tipe">
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Spesifikasi</label>
                        <textarea name="specification" rows="3" class="field" placeholder="Spesifikasi (opsional)"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nomor Seri (opsional)</label>
                        <input name="serial_number" class="field" placeholder="Nomor Seri">
                    </div>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Kondisi</label>
                        <select name="condition" class="field">
                            <option value="BARU">Baru</option>
                            <option value="BEKAS">Bekas</option>
                            <option value="LAYAK">Layak Pakai</option>
                            <option value="RUSAK">Rusak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Catatan</label>
                        <textarea name="note" rows="3" class="field" placeholder="Catatan tambahan (opsional)"></textarea>
                    </div>
                </div>`;
        }
    if(type==='take'){
      fields = `<div class="grid gap-4 sm:grid-cols-2"><div><label class="block text-sm font-medium text-slate-700">Lokasi asal</label><select name="location_id" class="field"><option value="">Semua lokasi</option>${available}</select></div><div><label class="block text-sm font-medium text-slate-700">Jumlah</label><input required type="number" min="1" max="${stock}" name="quantity" class="field"></div></div>`;
    }
    if(type==='transfer'){
      fields = `<div class="grid gap-4"><div class="grid gap-4 sm:grid-cols-2"><div><label class="block text-sm font-medium text-slate-700">Lokasi asal</label><select required name="from_location_id" class="field"><option value="">Pilih Lokasi</option>${available}</select></div><div><label class="block text-sm font-medium text-slate-700">Lokasi tujuan</label><select required name="to_location_id" class="field"><option value="">Pilih Lokasi</option>${all}</select></div></div><div><label class="block text-sm font-medium text-slate-700">Jumlah</label><input required type="number" min="1" max="${stock}" name="quantity" class="field"></div></div>`;
    }
    if(type==='borrow'){
      fields = `<div class="grid gap-4"><div class="rounded-2xl border border-slate-200 bg-white p-4 text-sm"><p class="font-semibold text-slate-900">${esc(i.item_code)} — ${esc(i.name)}</p><p class="mt-1 text-slate-500">Stok tersedia: ${stock} ${esc(i.unit)}</p></div><div class="grid gap-4"><div><label class="block text-sm font-medium text-slate-700">Nama Peminjam</label><input required name="borrower_name" class="field"></div><div><label class="block text-sm font-medium text-slate-700">Nomor Telepon</label><input required name="borrower_phone" type="tel" class="field"></div><div><label class="block text-sm font-medium text-slate-700">Unit / Prodi</label><input name="borrower_unit" class="field"></div><div><label class="block text-sm font-medium text-slate-700">Keperluan</label><textarea required name="purpose" rows="3" class="field"></textarea></div><div><label class="block text-sm font-medium text-slate-700">Tanggal Peminjaman</label><input required type="date" min="{{ now()->format('Y-m-d') }}" name="borrow_date" class="field"></div><div><label class="block text-sm font-medium text-slate-700">Tanggal Pengembalian</label><input required type="date" min="{{ now()->format('Y-m-d') }}" name="return_date" class="field"></div><div><label class="block text-sm font-medium text-slate-700">Jumlah</label><input required type="number" min="1" max="${stock}" name="quantity" class="field"></div></div></div>`;
    }
    modalBody.innerHTML = `<form action="${endpoint[type]}" method="POST" class="space-y-4"><input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="consumable_id" value="${id}"><div class="space-y-3"><div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700"><p class="font-semibold text-slate-900">${esc(i.item_code)} — ${esc(i.name)}</p><p class="mt-1">Stok tersedia: <span class="font-semibold text-slate-900">${stock} ${esc(i.unit)}</span></p></div>${fields}</div><button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white">Simpan</button></form>`;
    bindRemoteForm(modalBody.querySelector('form'));
  } catch {
    modalBody.innerHTML = '<p class="py-10 text-center text-red-600">Data barang tidak dapat dimuat.</p>';
  }
}
async function confirmDelete(id,name,button){if(!confirm(`Hapus barang "${name}"?`))return;button.disabled=true;const f=new FormData();f.append('_token','{{ csrf_token() }}');f.append('_method','DELETE');const r=await fetch(`/barang/${id}`,{method:'POST',headers:{Accept:'application/json'},body:f});if(r.ok)button.closest('tr').remove();else alert('Barang gagal dihapus.');button.disabled=false;}
let activeItemMenu = null;
let activeItemMenuButton = null;
function closeItemMenus(){document.querySelectorAll('[id^="item-menu-"]').forEach(menu=>{menu.classList.add('hidden');menu.classList.remove('fixed');menu.classList.add('absolute');menu.style.top='';menu.style.left='';});activeItemMenu=null;activeItemMenuButton=null;}
function positionItemMenu(menu,button){const rect=button.getBoundingClientRect();menu.classList.remove('hidden');menu.classList.remove('absolute');menu.classList.add('fixed');const menuHeight=menu.offsetHeight||320,menuWidth=menu.offsetWidth||192,spaceBelow=window.innerHeight-rect.bottom,top=spaceBelow>=menuHeight+12?rect.bottom+8:Math.max(12,rect.top-menuHeight-8),left=Math.min(Math.max(12,rect.right-menuWidth),window.innerWidth-menuWidth-12);menu.style.top=`${top}px`;menu.style.left=`${left}px`;}
function toggleItemMenu(id,button){const menu=document.getElementById(`item-menu-${id}`),wasHidden=menu.classList.contains('hidden');closeItemMenus();if(!wasHidden)return;activeItemMenu=menu;activeItemMenuButton=button;positionItemMenu(menu,button);}
document.addEventListener('click',event=>{if(!event.target.closest('.item-actions'))closeItemMenus();});
window.addEventListener('scroll',()=>{if(activeItemMenu&&activeItemMenuButton)positionItemMenu(activeItemMenu,activeItemMenuButton);},true);
window.addEventListener('resize',closeItemMenus);
/*
window.openDetail=async function(id){openBarangModal('Detail Barang');loading();try{const d=await itemData(id),i=d.item,rows=d.locations,unit=esc(i.unit);modalBody.innerHTML=`<div class="space-y-5"><section class="rounded-2xl border border-slate-100 bg-gradient-to-br from-slate-50 to-white p-5"><div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start"><div><p class="text-xs font-semibold uppercase tracking-wider text-blue-600">${esc(i.item_code)}</p><h4 class="mt-1 text-2xl font-bold text-slate-900">${esc(i.name)}</h4><p class="mt-1 text-sm text-slate-500">${esc(i.category)}${i.brand?' · '+esc(i.brand):''}${i.model?' · '+esc(i.model):''}</p></div><div class="flex gap-2"><span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">${esc(i.status)}</span><span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">${esc(i.condition)}</span></div></div><div class="mt-5 grid grid-cols-2 gap-3 lg:grid-cols-4"><div class="detail-card"><p>Stok tersedia</p><b>${d.stock} ${unit}</b></div><div class="detail-card"><p>Total stok</p><b>${d.total_stock} ${unit}</b></div><div class="detail-card"><p>Minimum stok</p><b>${i.minimum_stock??'-'} ${i.minimum_stock!==null?unit:''}</b></div><div class="detail-card"><p>Nomor seri</p><b>${esc(i.serial_number)}</b></div></div></section><section class="grid gap-4 md:grid-cols-2"><div class="rounded-xl border border-slate-100 p-4"><h5 class="font-semibold text-slate-800">Informasi Barang</h5><dl class="mt-3 space-y-2 text-sm"><div class="detail-line"><dt>Merk</dt><dd>${esc(i.brand)}</dd></div><div class="detail-line"><dt>Model / Tipe</dt><dd>${esc(i.model)}</dd></div><div class="detail-line"><dt>Satuan</dt><dd>${unit}</dd></div></dl></div><div class="rounded-xl border border-slate-100 p-4"><h5 class="font-semibold text-slate-800">Dokumen Nota</h5>${i.purchase_receipt_url?`<a target="_blank" href="${i.purchase_receipt_url}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-2 text-sm font-medium text-white"><i class="fas fa-file-invoice"></i> Lihat / Unduh Nota</a>`:'<p class="mt-4 text-sm text-slate-400">Nota pembelian belum tersedia.</p>'}</div></section><section class="rounded-xl border border-slate-100 p-4"><h5 class="font-semibold text-slate-800">Spesifikasi</h5><p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">${esc(i.specification)}</p><h5 class="mt-4 font-semibold text-slate-800">Deskripsi</h5><p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">${esc(i.description)}</p></section><section class="rounded-xl border border-slate-100 p-4"><div class="mb-3 flex items-center justify-between"><h5 class="font-semibold text-slate-800">Distribusi Lokasi</h5><span class="text-xs text-slate-400">${rows.length} lokasi</span></div><div class="overflow-x-auto"><table class="w-full text-sm"><thead class="bg-slate-50 text-left text-xs uppercase tracking-wide text-slate-400"><tr><th class="rounded-l-lg px-3 py-2">Lokasi</th><th class="px-3 py-2">Jumlah</th><th class="rounded-r-lg px-3 py-2">Status</th></tr></thead><tbody>${rows.map((l,n)=>`<tr class="${n>4?'hidden extra-location':''}"><td class="border-b px-3 py-3 font-medium text-slate-700">${esc(l.name)}</td><td class="border-b px-3 py-3">${l.quantity} ${unit}</td><td class="border-b px-3 py-3"><span class="rounded-full bg-emerald-100 px-2 py-1 text-xs text-emerald-700">Tersedia</span></td></tr>`).join('')}${d.borrowed_stock?`<tr><td class="border-b px-3 py-3 font-medium text-slate-700">Dipinjam</td><td class="border-b px-3 py-3">${d.borrowed_stock} ${unit}</td><td class="border-b px-3 py-3"><span class="rounded-full bg-sky-100 px-2 py-1 text-xs text-sky-700">Dipinjam</span></td></tr>`:''}</tbody></table></div>${rows.length>5?'<button id="allLocations" class="mt-3 text-sm font-medium text-blue-600">Lihat Seluruh Lokasi</button>':''}</section><section class="flex flex-wrap gap-2 border-t pt-5"><button onclick="openRemoteForm('/barang/${i.id}/edit','Edit Barang')" class="action-btn bg-amber-500"><i class="fas fa-pen"></i>Edit</button><button onclick="openAction(${i.id},'add')" class="action-btn bg-emerald-600"><i class="fas fa-plus"></i>Tambah Stok</button><button onclick="openAction(${i.id},'transfer')" class="action-btn bg-indigo-600"><i class="fas fa-exchange-alt"></i>Transfer</button><button onclick="openAction(${i.id},'borrow')" class="action-btn bg-sky-600"><i class="fas fa-hand-holding"></i>Pinjam</button><button onclick="openAction(${i.id},'take')" class="action-btn bg-orange-500"><i class="fas fa-minus"></i>Gunakan</button></section></div>`;document.getElementById('allLocations')?.addEventListener('click',()=>document.querySelectorAll('.extra-location').forEach(x=>x.classList.remove('hidden'));}catch{modalBody.innerHTML='<p class="py-10 text-center text-red-600">Data barang tidak dapat dimuat.</p>';}};
*/
</script>

@endsection
