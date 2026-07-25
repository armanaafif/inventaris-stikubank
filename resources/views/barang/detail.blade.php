@extends('layouts.dashboard-sidebar')

@section('title', 'Detail Barang - Inventaris Stikubank')
@section('page-title', 'Detail Barang')
@section('page-subtitle', 'Daftar item fisik dan detail stock batch')

@section('content')
@php
    $isContinuous = ($item->inventory_type ?? 'UNIT') === 'CONTINUOUS';
    $unitName = $item->unitMeasure->name ?? '';
    $masterStockLabel = $isContinuous
        ? (($stockSummary['rolls'] ?? $stockDistribution->count()) . ' Roll')
        : number_format($stock ?? 0) . ' ' . $unitName;
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('barang.index') }}" class="hover:text-blue-600">Daftar Barang</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span>{{ $item->name }}</span>
            </div>
            <h1 class="mt-2 text-2xl font-bold text-gray-800">{{ $item->name }}</h1>
            <p class="mt-1 text-sm text-gray-500">Detail master barang dan seluruh item fisik yang tercatat.</p>
        </div>
        <a href="{{ route('barang.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-xl border border-green-100 bg-green-50 p-4 text-sm font-medium text-green-700">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-xl border border-red-100 bg-red-50 p-4 text-sm font-medium text-red-700">
            <i class="fas fa-circle-exclamation mr-2"></i>{{ session('error') }}
        </div>
    @endif

    <section class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
        <div class="border-b border-gray-100 px-5 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                    <i class="fas fa-box"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-800">Master Barang</h2>
                    <p class="text-sm text-gray-500">Informasi umum yang berlaku untuk seluruh item fisik.</p>
                </div>
            </div>
        </div>

        <div class="p-5">
            <div class="mb-5 flex flex-col gap-4 border-b border-gray-100 pb-5 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Nama Barang</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ $item->name }}</p>
                    <p class="mt-2 max-w-3xl text-sm text-gray-500">{{ $item->description ?: 'Tidak ada catatan.' }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">{{ $item->inventory_type ?? 'UNIT' }}</span>
                    <span class="inline-flex rounded-full {{ $item->status === 'AKTIF' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} px-3 py-1 text-xs font-semibold">{{ $item->status }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs text-gray-400">Kategori</p>
                    <p class="mt-1 font-semibold text-gray-800">{{ $item->category->name ?? '-' }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs text-gray-400">Kode Barang</p>
                    <p class="mt-1 font-semibold text-gray-800">{{ $item->item_code ?? '-' }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs text-gray-400">Storage Unit</p>
                    <p class="mt-1 font-semibold text-gray-800">{{ $unitName ?: '-' }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs text-gray-400">Minimum Stock</p>
                    <p class="mt-1 font-semibold text-gray-800">{{ is_null($item->minimum_stock) ? '-' : number_format($item->minimum_stock) . ' ' . $unitName }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs text-gray-400">Total Item Fisik</p>
                    <p class="mt-1 font-semibold text-gray-800">{{ number_format($stockDistribution->count()) }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs text-gray-400">Stock Tersedia</p>
                    <p class="mt-1 font-semibold text-gray-800">{{ $masterStockLabel }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs text-gray-400">Dipinjam</p>
                    <p class="mt-1 font-semibold text-gray-800">{{ number_format($borrowedStock ?? 0) }} {{ $unitName }}</p>
                </div>
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                    <p class="text-xs text-gray-400">Nota Pembelian</p>
                    @if($item->purchase_receipt_path)
                        <a href="{{ asset('storage/' . $item->purchase_receipt_path) }}" target="_blank" class="mt-1 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-800">
                            <i class="fas fa-file-invoice"></i> Lihat Nota
                        </a>
                    @else
                        <p class="mt-1 font-semibold text-gray-800">-</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="space-y-4">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Item Fisik</h2>
                <p class="text-sm text-gray-500">Setiap stock batch ditampilkan sebagai card terpisah.</p>
            </div>
            <span class="inline-flex w-fit rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">{{ number_format($stockDistribution->count()) }} item</span>
        </div>

        <div class="grid grid-cols-1 gap-5 xl:grid-cols-2">
            @forelse($stockDistribution as $index => $stockLocation)
                @php
                    $stockName = trim(($stockLocation->brand ?: $item->brand ?: $item->name) . ' ' . ($stockLocation->model ?: $item->model ?: ''));
                    $stockName = $stockName ?: $item->name;
                    $stockCondition = $stockLocation->condition ?: $item->condition;
                    $stockStatus = $isContinuous
                        ? (((float) ($stockLocation->remaining_length ?? 0)) > 0 ? 'AKTIF' : 'HABIS')
                        : (((float) ($stockLocation->quantity ?? 0)) > 0 ? 'AKTIF' : 'HABIS');
                    $historyRows = $transactions->where('consumable_stock_id', $stockLocation->id);
                    $quantityValue = $isContinuous ? ($stockLocation->remaining_length ?? 0) : ($stockLocation->quantity ?? 0);
                    $initialValue = $stockLocation->initial_length ?? $quantityValue;
                    $lengthUnit = $stockLocation->length_unit ?: $unitName;
                @endphp

                <article class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                    <div class="flex items-start justify-between gap-4 border-b border-gray-100 px-5 py-4">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">
                                {{ $isContinuous ? ($stockLocation->batch_code ?: 'Roll ' . str_pad($index + 1, 3, '0', STR_PAD_LEFT)) : 'Item ' . str_pad($index + 1, 3, '0', STR_PAD_LEFT) }}
                            </p>
                            <h3 class="mt-1 truncate text-lg font-bold text-gray-900">{{ $stockName }}</h3>
                            <p class="mt-1 text-sm text-gray-500">{{ $stockLocation->location->name ?? '-' }}</p>
                        </div>

                        <div class="relative shrink-0">
                            <button type="button" onclick="toggleItemActionMenu({{ $stockLocation->id }})" class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-gray-500 transition hover:bg-gray-100 hover:text-gray-800" aria-label="Menu aksi">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div id="item-action-{{ $stockLocation->id }}" class="absolute right-0 z-20 mt-2 hidden w-48 overflow-hidden rounded-xl border border-gray-100 bg-white py-1 text-left shadow-xl">
                                <a href="{{ route('barang.edit', $item->id) }}" class="flex items-center gap-2 px-4 py-2 text-sm text-amber-700 hover:bg-gray-50"><i class="fas fa-pen w-4"></i> Edit Item</a>
                                <button type="button" onclick="openStockModal('add', {{ $stockLocation->id }});closeItemActionMenus()" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-emerald-700 hover:bg-gray-50"><i class="fas fa-plus w-4"></i> Tambah Stock</button>
                                <button type="button" onclick="openStockModal('transfer', {{ $stockLocation->id }});closeItemActionMenus()" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-indigo-700 hover:bg-gray-50"><i class="fas fa-exchange-alt w-4"></i> Transfer Lokasi</button>
                                @if($isContinuous)
                                    <button type="button" onclick="openStockModal('take', {{ $stockLocation->id }});closeItemActionMenus()" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-orange-700 hover:bg-gray-50"><i class="fas fa-minus w-4"></i> Gunakan Barang</button>
                                @else
                                    <button type="button" onclick="openStockModal('borrow', {{ $stockLocation->id }});closeItemActionMenus()" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-sky-700 hover:bg-gray-50"><i class="fas fa-hand-holding w-4"></i> Pinjam Barang</button>
                                @endif
                                <button type="button" onclick="openHistoryModal({{ $stockLocation->id }});closeItemActionMenus()" class="flex w-full items-center gap-2 px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50"><i class="fas fa-history w-4"></i> Riwayat</button>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-5 p-5">
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <p class="text-xs text-gray-400">Nama Barang</p>
                                <p class="mt-1 font-semibold text-gray-800">{{ $stockName }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Merk</p>
                                <p class="mt-1 font-semibold text-gray-800">{{ $stockLocation->brand ?: $item->brand ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Model</p>
                                <p class="mt-1 font-semibold text-gray-800">{{ $stockLocation->model ?: $item->model ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Nomor Barang</p>
                                <p class="mt-1 font-semibold text-gray-800">{{ $item->item_number ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Kode Barang</p>
                                <p class="mt-1 font-semibold text-gray-800">{{ $item->item_code ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Nomor Seri</p>
                                <p class="mt-1 font-semibold text-gray-800">{{ $stockLocation->serial_number ?: $item->serial_number ?: '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Lokasi</p>
                                <p class="mt-1 font-semibold text-gray-800">{{ $stockLocation->location->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Storage Unit</p>
                                <p class="mt-1 font-semibold text-gray-800">{{ $unitName ?: '-' }}</p>
                            </div>
                        </div>

                        <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                            <p class="text-xs text-gray-400">Spesifikasi</p>
                            <p class="mt-1 whitespace-pre-line text-sm text-gray-700">{{ $stockLocation->specification ?: $item->specification ?: '-' }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-3 lg:grid-cols-4">
                            <div class="rounded-lg border border-gray-100 p-3">
                                <p class="text-xs text-gray-400">Kondisi</p>
                                <span class="mt-2 inline-flex rounded-full {{ $stockCondition === 'RUSAK' ? 'bg-red-100 text-red-700' : ($stockCondition === 'BEKAS' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700') }} px-2 py-1 text-xs font-semibold">{{ $stockCondition }}</span>
                            </div>
                            <div class="rounded-lg border border-gray-100 p-3">
                                <p class="text-xs text-gray-400">Status</p>
                                <span class="mt-2 inline-flex rounded-full {{ $stockStatus === 'AKTIF' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }} px-2 py-1 text-xs font-semibold">{{ $stockStatus }}</span>
                            </div>
                            <div class="rounded-lg border border-gray-100 p-3">
                                <p class="text-xs text-gray-400">Inventory Type</p>
                                <span class="mt-2 inline-flex rounded-full {{ $isContinuous ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }} px-2 py-1 text-xs font-semibold">{{ $item->inventory_type ?? 'UNIT' }}</span>
                            </div>
                            <div class="rounded-lg border border-gray-100 p-3">
                                <p class="text-xs text-gray-400">{{ $isContinuous ? 'Jumlah Roll' : 'Jumlah' }}</p>
                                <p class="mt-1 text-lg font-bold text-gray-900">
                                    @if($isContinuous)
                                        {{ number_format($stockLocation->roll_count ?: 1) }} Roll
                                    @else
                                        {{ number_format($stockLocation->quantity ?? 0) }} {{ $unitName }}
                                    @endif
                                </p>
                            </div>
                        </div>

                        @if($isContinuous)
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                <div class="rounded-lg border border-gray-100 bg-purple-50 p-4">
                                    <p class="text-xs text-purple-500">Panjang Awal</p>
                                    <p class="mt-1 text-lg font-bold text-purple-900">{{ number_format($initialValue, 2) }} {{ $lengthUnit }}</p>
                                </div>
                                <div class="rounded-lg border border-gray-100 bg-green-50 p-4">
                                    <p class="text-xs text-green-500">Panjang Tersisa</p>
                                    <p class="mt-1 text-lg font-bold text-green-900">{{ number_format($stockLocation->remaining_length ?? 0, 2) }} {{ $lengthUnit }}</p>
                                </div>
                                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                                    <p class="text-xs text-gray-400">Satuan Panjang</p>
                                    <p class="mt-1 text-lg font-bold text-gray-900">{{ $lengthUnit ?: '-' }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </article>

                <div id="history-modal-{{ $stockLocation->id }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
                    <div class="flex max-h-[85vh] w-full max-w-2xl flex-col overflow-hidden rounded-xl bg-white shadow-xl">
                        <div class="flex items-center justify-between border-b px-5 py-4">
                            <div>
                                <h3 class="font-semibold text-gray-800">Riwayat Item Fisik</h3>
                                <p class="text-sm text-gray-500">{{ $stockName }}</p>
                            </div>
                            <button type="button" onclick="closeHistoryModal({{ $stockLocation->id }})" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="overflow-y-auto p-5">
                            <div class="space-y-3">
                                @forelse($historyRows as $trx)
                                    <div class="rounded-lg border border-gray-100 p-4">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <span class="inline-flex rounded-full {{ $trx->type === 'IN' ? 'bg-green-100 text-green-700' : ($trx->type === 'TRANSFER' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700') }} px-2 py-1 text-xs font-semibold">
                                                    {{ $trx->type === 'IN' ? 'Barang Masuk' : ($trx->type === 'TRANSFER' ? 'Transfer' : 'Digunakan') }}
                                                </span>
                                                <p class="mt-2 text-sm text-gray-600">{{ $trx->note ?: '-' }}</p>
                                            </div>
                                            <div class="text-right text-sm">
                                                <p class="font-semibold text-gray-800">{{ number_format($trx->length_amount ?: $trx->quantity, 2) }} {{ $trx->length_unit ?: $unitName }}</p>
                                                <p class="text-xs text-gray-400">{{ $trx->created_at->format('d/m/Y H:i') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="py-10 text-center text-gray-400">
                                        <i class="fas fa-history mb-2 block text-3xl text-gray-300"></i>
                                        Belum ada riwayat untuk item fisik ini
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-xl border border-gray-100 bg-white py-12 text-center text-gray-400 shadow-sm xl:col-span-2">
                    <i class="fas fa-box-open mb-2 block text-4xl text-gray-300"></i>
                    Belum ada item fisik untuk barang ini
                </div>
            @endforelse
        </div>
    </section>
</div>

<div id="stockActionModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="flex max-h-[85vh] w-full max-w-2xl flex-col overflow-hidden rounded-xl bg-white shadow-xl">
        <div class="flex items-center justify-between border-b px-5 py-4">
            <div>
                <h3 id="stockActionTitle" class="font-semibold text-gray-800">Aksi Item</h3>
                <p id="stockActionSubtitle" class="text-sm text-gray-500"></p>
            </div>
            <button type="button" onclick="closeStockModal()" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100"><i class="fas fa-times"></i></button>
        </div>
        <div id="stockActionBody" class="overflow-y-auto p-5"></div>
    </div>
</div>

@php
    $itemStocksPayload = $stockDistribution->mapWithKeys(function ($stockLocation, $index) use ($item, $unitName, $isContinuous) {
    $quantity = $isContinuous ? (float) ($stockLocation->remaining_length ?? 0) : (float) ($stockLocation->quantity ?? 0);
    return [$stockLocation->id => [
        'id' => $stockLocation->id,
        'name' => $stockLocation->brand ?: $item->brand ?: $item->name,
        'display_name' => trim(($stockLocation->brand ?: $item->brand ?: $item->name) . ' ' . ($stockLocation->model ?: $item->model ?: '')),
        'location_id' => $stockLocation->location_id,
        'location_name' => $stockLocation->location->name ?? '-',
        'quantity' => $quantity,
        'unit' => $isContinuous ? ($stockLocation->length_unit ?: $unitName) : $unitName,
        'brand' => $stockLocation->brand ?: $item->brand,
        'model' => $stockLocation->model ?: $item->model,
        'serial_number' => $stockLocation->serial_number ?: $item->serial_number,
        'specification' => $stockLocation->specification ?: $item->specification,
        'condition' => $stockLocation->condition ?: $item->condition,
    ]];
});
    $locationOptionsPayload = $locations->map(fn ($location) => ['id' => $location->id, 'name' => $location->name])->values();
@endphp

<script>
const itemStocks = @json($itemStocksPayload);
const isContinuousItem = @json($isContinuous);
const allLocations = @json($locationOptionsPayload);
const csrfToken = @json(csrf_token());

function closeItemActionMenus(){
    document.querySelectorAll('[id^="item-action-"]').forEach(menu => menu.classList.add('hidden'));
}
function toggleItemActionMenu(id){
    const menu = document.getElementById(`item-action-${id}`);
    const hidden = menu.classList.contains('hidden');
    closeItemActionMenus();
    if (hidden) menu.classList.remove('hidden');
}
document.addEventListener('click', event => {
    if (!event.target.closest('[id^="item-action-"]') && !event.target.closest('button[aria-label="Menu aksi"]')) {
        closeItemActionMenus();
    }
});

function locationOptions(selectedId = null){
    return allLocations.map(location => `<option value="${location.id}" ${String(location.id) === String(selectedId) ? 'selected' : ''}>${escapeHtml(location.name)}</option>`).join('');
}
function escapeHtml(value){
    return String(value ?? '').replace(/[&<>'"]/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','"':'&quot;'}[char]));
}
function openStockModal(type, stockId){
    const stock = itemStocks[stockId];
    if (!stock) return;
    const title = {
        add: 'Tambah Stock',
        transfer: 'Transfer Lokasi',
        take: 'Gunakan Barang',
        borrow: 'Pinjam Barang'
    }[type] || 'Aksi Item';
    document.getElementById('stockActionTitle').textContent = title;
    document.getElementById('stockActionSubtitle').textContent = `${stock.display_name || '{{ $item->name }}'} - ${stock.location_name}`;
    document.getElementById('stockActionBody').innerHTML = stockModalTemplate(type, stock);
    document.getElementById('stockActionModal').classList.replace('hidden', 'flex');
}
function closeStockModal(){
    document.getElementById('stockActionModal').classList.replace('flex', 'hidden');
    document.getElementById('stockActionBody').innerHTML = '';
}
function stockModalTemplate(type, stock){
    if (type === 'transfer') {
        return `<form method="POST" action="{{ route('stock.transfer') }}" class="space-y-4">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="consumable_id" value="{{ $item->id }}">
            <input type="hidden" name="consumable_stock_id" value="${stock.id}">
            <input type="hidden" name="from_location_id" value="${stock.location_id}">
            <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 text-sm text-gray-600">Lokasi asal: <span class="font-semibold text-gray-900">${escapeHtml(stock.location_name)}</span></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700">Lokasi tujuan</label><select name="to_location_id" required class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm">${locationOptions()}</select></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700">Jumlah</label><input type="number" name="quantity" required min="0.01" step="0.01" max="${stock.quantity}" value="${stock.quantity}" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700">Catatan</label><textarea name="note" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm">Transfer ${escapeHtml(stock.display_name || '')}</textarea></div>
            <button class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white">Simpan Transfer</button>
        </form>`;
    }
    if (type === 'take') {
        return `<form method="POST" action="{{ route('stock.take') }}" class="space-y-4">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="consumable_id" value="{{ $item->id }}">
            <input type="hidden" name="consumable_stock_id" value="${stock.id}">
            <input type="hidden" name="location_id" value="${stock.location_id}">
            <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 text-sm text-gray-600">Sisa saat ini: <span class="font-semibold text-gray-900">${stock.quantity} ${escapeHtml(stock.unit)}</span></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700">Jumlah digunakan</label><input type="number" name="quantity" required min="0.01" step="0.01" max="${stock.quantity}" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm"></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700">Catatan</label><textarea name="note" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm"></textarea></div>
            <button class="w-full rounded-lg bg-orange-600 px-4 py-2.5 text-sm font-semibold text-white">Gunakan Barang</button>
        </form>`;
    }
    if (type === 'borrow') {
        return `<form method="POST" action="{{ route('borrow.item') }}" class="space-y-4">
            <input type="hidden" name="_token" value="${csrfToken}">
            <input type="hidden" name="consumable_id" value="{{ $item->id }}">
            <input type="hidden" name="consumable_stock_id" value="${stock.id}">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div><label class="mb-2 block text-sm font-medium text-gray-700">Nama Peminjam</label><input name="borrower_name" required class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm"></div>
                <div><label class="mb-2 block text-sm font-medium text-gray-700">Nomor Telepon</label><input name="borrower_phone" required class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm"></div>
                <div><label class="mb-2 block text-sm font-medium text-gray-700">Unit / Prodi</label><input name="borrower_unit" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm"></div>
                <div><label class="mb-2 block text-sm font-medium text-gray-700">Jumlah</label><input type="number" name="quantity" required min="1" max="${stock.quantity}" value="1" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm"></div>
                <div><label class="mb-2 block text-sm font-medium text-gray-700">Tanggal Pinjam</label><input type="date" name="borrow_date" required min="{{ now()->format('Y-m-d') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm"></div>
                <div><label class="mb-2 block text-sm font-medium text-gray-700">Tanggal Kembali</label><input type="date" name="return_date" required min="{{ now()->format('Y-m-d') }}" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm"></div>
            </div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700">Keperluan</label><textarea name="purpose" required rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm"></textarea></div>
            <div><label class="mb-2 block text-sm font-medium text-gray-700">Catatan</label><textarea name="note" rows="2" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm">Item: ${escapeHtml(stock.display_name || '')} | Serial: ${escapeHtml(stock.serial_number || '-')}</textarea></div>
            <button class="w-full rounded-lg bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white">Ajukan Pinjam</button>
        </form>`;
    }
    return `<form method="POST" action="{{ route('stock.add') }}" class="space-y-4">
        <input type="hidden" name="_token" value="${csrfToken}">
        <input type="hidden" name="consumable_id" value="{{ $item->id }}">
        <input type="hidden" name="location_id" value="${stock.location_id}">
        <input type="hidden" name="brand" value="${escapeHtml(stock.brand || '')}">
        <input type="hidden" name="model" value="${escapeHtml(stock.model || '')}">
        <input type="hidden" name="serial_number" value="${escapeHtml(stock.serial_number || '')}">
        <input type="hidden" name="specification" value="${escapeHtml(stock.specification || '')}">
        <input type="hidden" name="condition" value="${escapeHtml(stock.condition || '')}">
        <div class="rounded-lg border border-gray-100 bg-gray-50 p-4 text-sm text-gray-600">Item fisik: <span class="font-semibold text-gray-900">${escapeHtml(stock.display_name || '')}</span></div>
        <div><label class="mb-2 block text-sm font-medium text-gray-700">Jumlah</label><input type="number" name="quantity" required min="0.01" step="0.01" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm"></div>
        <div><label class="mb-2 block text-sm font-medium text-gray-700">Catatan</label><textarea name="note" rows="3" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm">Tambah stock ${escapeHtml(stock.display_name || '')}</textarea></div>
        <button class="w-full rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white">Tambah Stock</button>
    </form>`;
}
function openHistoryModal(id){
    document.getElementById(`history-modal-${id}`)?.classList.replace('hidden', 'flex');
}
function closeHistoryModal(id){
    document.getElementById(`history-modal-${id}`)?.classList.replace('flex', 'hidden');
}
document.addEventListener('keydown', event => {
    if (event.key === 'Escape') {
        closeStockModal();
        document.querySelectorAll('[id^="history-modal-"]').forEach(modal => modal.classList.replace('flex', 'hidden'));
        closeItemActionMenus();
    }
});
</script>
@endsection
