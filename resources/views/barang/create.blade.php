@extends('layouts.dashboard-sidebar')

@section('title', 'Tambah Barang - Inventaris Stikubank')
@section('page-title', 'Tambah Barang')
@section('page-subtitle', 'Tambahkan data barang inventaris baru')

@section('content')
<div class="mx-auto max-w-6xl space-y-6">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <a href="{{ route('barang.index') }}" class="hover:text-blue-600">Daftar Barang</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span>Tambah Barang</span>
            </div>
            <h1 class="mt-2 text-2xl font-bold text-gray-800">Tambah Barang</h1>
            <p class="mt-1 text-sm text-gray-500">Lengkapi informasi barang, spesifikasi, dan stok awal.</p>
        </div>
        <a href="{{ route('barang.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
            <div class="mb-2 flex items-center gap-2 font-semibold">
                <i class="fas fa-circle-exclamation"></i>
                Data belum dapat disimpan
            </div>
            <ul class="list-disc space-y-1 pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="edit-barang-form" method="POST" action="{{ route('barang.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <section class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="mb-5 flex items-start gap-3 border-b border-gray-100 pb-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                    <i class="fas fa-box"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-800">Informasi Barang</h2>
                    <p class="text-sm text-gray-500">Identitas utama barang inventaris.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div>
                    <div class="mb-2 flex items-center justify-between gap-3">
                        <label for="category_id" class="text-sm font-medium text-gray-700">Kategori <span class="text-red-500">*</span></label>
                        <button type="button" onclick="openCategoryModal()" class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-800">
                            <i class="fas fa-plus text-xs"></i> Tambah Kategori
                        </button>
                    </div>
                    <select id="category_id" name="category_id" required class="form-field" onchange="refreshItemNumber()">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" data-code="{{ $category->code }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }} ({{ $category->code }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="item_number" class="mb-2 block text-sm font-medium text-gray-700">Nomor Barang</label>
                    <input id="item_number" type="number" name="item_number" value="{{ old('item_number') }}" min="1" class="form-field" placeholder="Otomatis jika dikosongkan">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Kode Barang</label>
                    <div id="codePreview" class="flex min-h-[42px] items-center rounded-lg border border-gray-200 bg-gray-50 px-4 text-sm font-semibold text-gray-700">-</div>
                </div>

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-gray-700">Nama Barang <span class="text-red-500">*</span></label>
                    <input id="name" name="name" value="{{ old('name') }}" required class="form-field">
                </div>

                <div class="lg:col-span-2">
                    <label for="inventory_type" class="mb-2 block text-sm font-medium text-gray-700">Inventory Type <span class="text-red-500">*</span></label>
                    <select id="inventory_type" name="inventory_type" required class="form-field" onchange="toggleInventoryFields()">
                        <option value="UNIT" {{ old('inventory_type', 'UNIT') === 'UNIT' ? 'selected' : '' }}>UNIT</option>
                        <option value="CONTINUOUS" {{ old('inventory_type') === 'CONTINUOUS' ? 'selected' : '' }}>CONTINUOUS</option>
                    </select>
                </div>
            </div>
        </section>

        <section class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="mb-5 flex items-start gap-3 border-b border-gray-100 pb-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-800">Spesifikasi</h2>
                    <p class="text-sm text-gray-500">Detail fisik dan kondisi barang.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div>
                    <label for="brand" class="mb-2 block text-sm font-medium text-gray-700">Merk</label>
                    <input id="brand" name="brand" value="{{ old('brand') }}" class="form-field">
                </div>
                <div>
                    <label for="model" class="mb-2 block text-sm font-medium text-gray-700">Model</label>
                    <input id="model" name="model" value="{{ old('model') }}" class="form-field">
                </div>
                <div>
                    <label for="serial_number" class="mb-2 block text-sm font-medium text-gray-700">Nomor Seri</label>
                    <input id="serial_number" name="serial_number" value="{{ old('serial_number') }}" class="form-field">
                </div>
                <div>
                    <label for="condition" class="mb-2 block text-sm font-medium text-gray-700">Kondisi <span class="text-red-500">*</span></label>
                    <select id="condition" name="condition" required class="form-field">
                        <option value="BARU" {{ old('condition') === 'BARU' ? 'selected' : '' }}>Baru</option>
                        <option value="BEKAS" {{ old('condition') === 'BEKAS' ? 'selected' : '' }}>Bekas</option>
                        <option value="LAYAK" {{ old('condition') === 'LAYAK' ? 'selected' : '' }}>Layak Pakai</option>
                        <option value="RUSAK" {{ old('condition') === 'RUSAK' ? 'selected' : '' }}>Rusak</option>
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label for="specification" class="mb-2 block text-sm font-medium text-gray-700">Spesifikasi</label>
                    <textarea id="specification" name="specification" rows="4" class="form-field">{{ old('specification') }}</textarea>
                </div>
            </div>
        </section>

        <section class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="mb-5 flex items-start gap-3 border-b border-gray-100 pb-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-green-100 text-green-600">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-800">Stok Awal</h2>
                    <p class="text-sm text-gray-500">Lokasi penyimpanan dan jumlah awal barang.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
                <div>
                    <div class="mb-2 flex items-center justify-between gap-3">
                        <label for="location_id" class="text-sm font-medium text-gray-700">Lokasi <span class="text-red-500">*</span></label>
                        <button type="button" onclick="openLocationModal()" class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-800">
                            <i class="fas fa-plus text-xs"></i> Tambah Lokasi
                        </button>
                    </div>
                    <select id="location_id" name="location_id" required class="form-field">
                        <option value="">Pilih Lokasi</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="unit_measure_id" class="mb-2 block text-sm font-medium text-gray-700">Storage Unit / Satuan <span class="text-red-500">*</span></label>
                    <select id="unit_measure_id" name="unit_measure_id" required class="form-field">
                        <option value="">Pilih Satuan</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_measure_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="quantity" class="mb-2 block text-sm font-medium text-gray-700">Quantity / Roll <span class="text-red-500">*</span></label>
                    <input id="quantity" type="number" name="quantity" value="{{ old('quantity') }}" min="0.01" step="0.01" required class="form-field">
                </div>
                <div class="lg:col-span-3">
                    <label for="purchase_receipt" class="mb-2 block text-sm font-medium text-gray-700">Upload Nota</label>
                    <input id="purchase_receipt" type="file" name="purchase_receipt" accept=".pdf,.jpg,.jpeg,.png" class="form-field bg-white">
                </div>
            </div>
        </section>

        <section class="rounded-xl border border-gray-100 bg-white p-5 shadow-sm">
            <div class="mb-5 flex items-start gap-3 border-b border-gray-100 pb-4">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-600">
                    <i class="fas fa-circle-info"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-800">Informasi Tambahan</h2>
                    <p class="text-sm text-gray-500">Batas minimum stok, status, dan catatan barang.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <div>
                    <label for="minimum_stock" class="mb-2 block text-sm font-medium text-gray-700">Minimum Stock</label>
                    <input id="minimum_stock" type="number" name="minimum_stock" value="{{ old('minimum_stock') }}" min="0" class="form-field">
                </div>
                <div>
                    <label for="status" class="mb-2 block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                    <select id="status" name="status" required class="form-field">
                        <option value="AKTIF" {{ old('status') === 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                        <option value="NONAKTIF" {{ old('status') === 'NONAKTIF' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label for="description" class="mb-2 block text-sm font-medium text-gray-700">Catatan</label>
                    <textarea id="description" name="description" rows="4" class="form-field">{{ old('description') }}</textarea>
                </div>
            </div>
        </section>

        <div class="sticky bottom-0 z-10 flex flex-col-reverse gap-3 border-t border-gray-100 bg-gray-100/95 py-4 backdrop-blur sm:flex-row sm:justify-end">
            <button type="button" onclick="cancelBarangForm()" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
                Batal
            </button>
            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700">
                <i class="fas fa-save"></i> Simpan Barang
            </button>
        </div>
    </form>
</div>

<div id="categoryModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
    <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Tambah Kategori</h3>
            <button type="button" onclick="closeCategoryModal()" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100"><i class="fas fa-times"></i></button>
        </div>
        <div class="space-y-4">
            <input id="category_name" name="name" required placeholder="Nama kategori" class="form-field">
            <p id="categoryError" class="hidden text-sm text-red-500"></p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeCategoryModal()" class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-700">Batal</button>
                <button type="button" onclick="submitCategoryForm()" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div id="locationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4">
    <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Tambah Lokasi</h3>
            <button type="button" onclick="closeLocationModal()" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100"><i class="fas fa-times"></i></button>
        </div>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="location_name" class="mb-2 block text-sm font-medium text-gray-700">Nama Lokasi <span class="text-red-500">*</span></label>
                <input id="location_name" name="name" required class="form-field" placeholder="Contoh: Gudang A">
            </div>
            <div>
                <label for="location_building" class="mb-2 block text-sm font-medium text-gray-700">Gedung</label>
                <input id="location_building" name="building" class="form-field">
            </div>
            <div>
                <label for="location_floor" class="mb-2 block text-sm font-medium text-gray-700">Lantai</label>
                <input id="location_floor" name="floor" class="form-field">
            </div>
            <div class="sm:col-span-2">
                <label for="location_room" class="mb-2 block text-sm font-medium text-gray-700">Ruang</label>
                <input id="location_room" name="room" class="form-field">
            </div>
            <p id="locationError" class="hidden text-sm text-red-500 sm:col-span-2"></p>
        </div>
        <div class="mt-5 flex justify-end gap-3">
            <button type="button" onclick="closeLocationModal()" class="rounded-lg border border-gray-200 px-4 py-2 text-sm text-gray-700">Batal</button>
            <button type="button" onclick="submitLocationForm()" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white">Simpan</button>
        </div>
    </div>
</div>

<style>
    .form-field {
        display: block;
        width: 100%;
        border-radius: 0.5rem;
        border: 1px solid #d1d5db;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        color: #374151;
    }
    .form-field:focus {
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.25);
    }
</style>

<script>
function toggleInventoryFields(){}
toggleInventoryFields();

function cancelBarangForm(){
    if (typeof closeBarangModal === 'function') {
        closeBarangModal();
        return;
    }
    window.location.href = '{{ route('barang.index') }}';
}

function openCategoryModal(){document.getElementById('categoryModal').classList.replace('hidden','flex');}
function closeCategoryModal(){document.getElementById('categoryModal').classList.replace('flex','hidden');document.getElementById('category_name').value='';document.getElementById('categoryError').classList.add('hidden');}
function openLocationModal(){document.getElementById('locationModal').classList.replace('hidden','flex');}
function closeLocationModal(){document.getElementById('locationModal').classList.replace('flex','hidden');['location_name','location_building','location_floor','location_room'].forEach(id=>document.getElementById(id).value='');document.getElementById('locationError').classList.add('hidden');}
function selectedCategoryCode(){const option=document.getElementById('category_id').selectedOptions[0];return option?.dataset?.code || '';}
function updateCodePreview(){const code=selectedCategoryCode(), number=document.getElementById('item_number').value;document.getElementById('codePreview').textContent=code&&number ? `${code}-${String(number).padStart(6,'0')}` : '-';}
async function refreshItemNumber(){const id=document.getElementById('category_id').value;if(!id){updateCodePreview();return;}const r=await fetch(`/categories/${id}/next-item-number`,{headers:{Accept:'application/json'}});if(r.ok){document.getElementById('item_number').value=(await r.json()).item_number;updateCodePreview();}}
document.getElementById('item_number').addEventListener('input',updateCodePreview);
updateCodePreview();

async function submitCategoryForm(){
    const error=document.getElementById('categoryError');
    const data=new FormData();
    data.append('name', document.getElementById('category_name').value);
    const r=await fetch('{{ route('categories.quick-store') }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},body:data});
    if(!r.ok){error.textContent='Kategori gagal disimpan atau sudah ada.';error.classList.remove('hidden');return;}
    const c=await r.json(),select=document.getElementById('category_id');
    const option=new Option(`${c.name} (${c.code})`,c.id,true,true);
    option.dataset.code=c.code;
    select.add(option);
    closeCategoryModal();
    refreshItemNumber();
}

async function submitLocationForm(){
    const error=document.getElementById('locationError');
    const data=new FormData();
    data.append('name', document.getElementById('location_name').value);
    data.append('building', document.getElementById('location_building').value);
    data.append('floor', document.getElementById('location_floor').value);
    data.append('room', document.getElementById('location_room').value);
    const r=await fetch('{{ route('locations.quick-store') }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'},body:data});
    if(!r.ok){error.textContent='Lokasi gagal disimpan atau sudah ada.';error.classList.remove('hidden');return;}
    const location=await r.json(),select=document.getElementById('location_id');
    select.add(new Option(location.name,location.id,true,true));
    closeLocationModal();
}
</script>
@endsection
