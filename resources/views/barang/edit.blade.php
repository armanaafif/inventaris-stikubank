@extends('layouts.dashboard-sidebar')
@section('title', 'Edit Barang - Inventaris Stikubank')
@section('page-title', 'Edit Barang')
@section('page-subtitle', 'Perbarui data barang inventaris')
@section('content')
<div class="max-w-3xl mx-auto bg-white rounded-xl shadow-xl border border-gray-100 p-6">
<form id="edit-barang-form" method="POST" action="{{ route('barang.update', $item->id) }}" enctype="multipart/form-data" class="space-y-4">@csrf @method('PUT')
	<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
		<div class="space-y-3">
			<div class="flex items-center justify-between">
				<label class="text-sm font-medium text-gray-700">Kategori</label>
				<button type="button" onclick="openCategoryModal()" class="text-sm text-blue-600 hover:text-blue-800"><i class="fas fa-plus"></i></button>
			</div>
			<select id="category_id" name="category_id" required class="field" onchange="updateCodePreview()">@foreach($categories as $category)<option value="{{ $category->id }}" data-code="{{ $category->code }}" {{ old('category_id',$item->category_id)==$category->id?'selected':'' }}>{{ $category->name }} ({{ $category->code }})</option>@endforeach</select>

			<label class="text-sm font-medium text-gray-700">Nomor Barang</label>
			<div class="flex gap-2"><input id="item_number" name="item_number" value="{{ old('item_number',$item->item_number ?? preg_replace('/^.*-/', '', $item->item_code)) }}" required inputmode="numeric" pattern="[0-9]+" class="field"><button type="button" onclick="refreshItemNumber()" class="px-3 rounded-xl bg-blue-600 text-white text-sm">Generate</button></div>
			<p class="text-xs text-gray-400 mt-1">Kode Barang: <span id="codePreview">{{ $item->item_code }}</span></p>

			<label class="text-sm font-medium text-gray-700">Nama Barang</label>
			<input name="name" value="{{ old('name',$item->name) }}" required class="field">

			<label class="text-sm font-medium text-gray-700">Inventory Type</label>
			<select name="inventory_type" required class="field">
				<option value="UNIT" {{ old('inventory_type',$item->inventory_type ?? 'UNIT')==='UNIT'?'selected':'' }}>UNIT</option>
				<option value="CONTINUOUS" {{ old('inventory_type',$item->inventory_type)==='CONTINUOUS'?'selected':'' }}>CONTINUOUS</option>
			</select>
		</div>

		<div class="space-y-3">
			<label class="text-sm font-medium text-gray-700">Satuan</label>
			<select name="unit_measure_id" required class="field">@foreach($units as $unit)<option value="{{ $unit->id }}" {{ old('unit_measure_id',$item->unit_measure_id)==$unit->id?'selected':'' }}>{{ $unit->name }}</option>@endforeach</select>

			<label class="text-sm font-medium text-gray-700">Minimum Stock</label>
			<input type="number" name="minimum_stock" value="{{ old('minimum_stock',$item->minimum_stock) }}" min="0" class="field">

			<label class="text-sm font-medium text-gray-700">Status</label>
			<select name="status" required class="field"><option value="AKTIF" {{ old('status',$item->status)==='AKTIF'?'selected':'' }}>Aktif</option><option value="NONAKTIF" {{ old('status',$item->status)==='NONAKTIF'?'selected':'' }}>Nonaktif</option></select>

			<label class="text-sm font-medium text-gray-700">Kondisi</label>
			<select name="condition" required class="field">@foreach(['BARU'=>'Baru','BEKAS'=>'Bekas','LAYAK'=>'Layak Pakai','RUSAK'=>'Rusak'] as $value=>$label)<option value="{{ $value }}" {{ old('condition',$item->condition)===$value?'selected':'' }}>{{ $label }}</option>@endforeach</select>
		</div>
	</div>

	<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
		<div><label class="text-sm font-medium text-gray-700">Merk</label><input name="brand" value="{{ old('brand',$item->brand) }}" class="field"></div>
		<div><label class="text-sm font-medium text-gray-700">Model</label><input name="model" value="{{ old('model',$item->model) }}" class="field"></div>
		<div><label class="text-sm font-medium text-gray-700">Nomor Seri</label><input name="serial_number" value="{{ old('serial_number',$item->serial_number) }}" class="field"></div>
		<div><label class="text-sm font-medium text-gray-700">Spesifikasi</label><textarea name="specification" rows="3" class="field">{{ old('specification',$item->specification) }}</textarea></div>
		<div>
			<label class="text-sm font-medium text-gray-700">Upload Nota</label>
			<input type="file" name="purchase_receipt" accept=".pdf,.jpg,.jpeg,.png" class="field">
			@if($item->purchase_receipt_path)<a target="_blank" class="text-xs text-blue-600" href="{{ asset('storage/'.$item->purchase_receipt_path) }}">Lihat nota saat ini</a>@endif
		</div>
	</div>

	<div>
		<label class="text-sm font-medium text-gray-700">Catatan</label>
		<textarea name="description" rows="3" class="field">{{ old('description',$item->description) }}</textarea>
	</div>

	<div class="flex justify-end gap-3"><a href="{{ route('barang.show',$item->id) }}" class="px-5 py-2.5 rounded-xl border">Batal</a><button class="px-5 py-2.5 rounded-xl bg-amber-600 text-white">Simpan Perubahan</button></div>
</form></div>
<script>function updateCodePreview(){const s=document.getElementById('category_id'),n=document.getElementById('item_number').value;document.getElementById('codePreview').textContent=`${s.options[s.selectedIndex].dataset.code}-${n}`;}document.getElementById('item_number').addEventListener('input',updateCodePreview);</script>
@endsection
