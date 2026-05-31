@extends('layouts.dashboard-sidebar')

@section('title', 'Tambah Barang - Inventaris Stikubank')
@section('page-title', 'Tambah Barang')
@section('page-subtitle', 'Tambahkan data barang inventaris baru')

@section('content')
<div class="max-w-3xl mx-auto">
    
    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        
        <!-- Header -->
        <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white px-6 py-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-plus-circle text-blue-600 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Form Tambah Barang</h3>
                    <p class="text-xs text-gray-400">Isi data barang dengan lengkap</p>
                </div>
            </div>
        </div>

        <!-- Form Body - STRUKTUR FORM TIDAK BERUBAH -->
        <form method="POST" action="{{ route('barang.store') }}" class="p-6">
            @csrf

            <div class="space-y-5">
                
                <!-- Nama Barang -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-box mr-1 text-gray-400"></i> Nama Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required
                           placeholder="Contoh: Kabel LAN, Mouse, Keyboard"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Satuan & Minimum Stok (2 Kolom) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <!-- Satuan -->
                    <div>
                        <label for="unit_measure_id" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-ruler mr-1 text-gray-400"></i> Satuan Barang <span class="text-red-500">*</span>
                        </label>
                        <select id="unit_measure_id" 
                                name="unit_measure_id" 
                                required
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('unit_measure_id') border-red-500 @enderror">
                            <option value="">Pilih Satuan</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_measure_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_measure_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Minimum Stok -->
                    <div>
                        <label for="minimum_stock" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-exclamation-triangle mr-1 text-gray-400"></i> Minimum Stok <span class="text-red-500">*</span>
                        </label>
                        <input type="number" 
                               id="minimum_stock" 
                               name="minimum_stock" 
                               value="{{ old('minimum_stock') }}" 
                               required
                               min="0"
                               placeholder="Contoh: 5"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('minimum_stock') border-red-500 @enderror">
                        <p class="text-xs text-gray-400 mt-1">Batas minimal stok untuk peringatan</p>
                        @error('minimum_stock')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Kondisi & Status (2 Kolom) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <!-- Kondisi -->
                    <div>
                        <label for="condition" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-clipboard-list mr-1 text-gray-400"></i> Kondisi Barang <span class="text-red-500">*</span>
                        </label>
                        <select id="condition" 
                                name="condition" 
                                required
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('condition') border-red-500 @enderror">
                            <option value="">Pilih Kondisi</option>
                            <option value="BARU" {{ old('condition') == 'BARU' ? 'selected' : '' }}>Baru</option>
                            <option value="BEKAS" {{ old('condition') == 'BEKAS' ? 'selected' : '' }}>Bekas</option>
                            <option value="LAYAK" {{ old('condition') == 'LAYAK' ? 'selected' : '' }}>Layak Pakai</option>
                            <option value="RUSAK" {{ old('condition') == 'RUSAK' ? 'selected' : '' }}>Rusak / Tidak Layak</option>
                        </select>
                        @error('condition')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-power-off mr-1 text-gray-400"></i> Status Barang <span class="text-red-500">*</span>
                        </label>
                        <select id="status" 
                                name="status" 
                                required
                                class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('status') border-red-500 @enderror">
                            <option value="">Pilih Status</option>
                            <option value="AKTIF" {{ old('status') == 'AKTIF' ? 'selected' : '' }}>Aktif</option>
                            <option value="NONAKTIF" {{ old('status') == 'NONAKTIF' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Stok Awal -->
                <div>
                    <label for="initial_stock" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-database mr-1 text-gray-400"></i> Stok Awal <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="initial_stock" 
                           name="initial_stock" 
                           value="{{ old('initial_stock', 0) }}" 
                           required
                           min="0"
                           placeholder="Contoh: 10"
                           class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('initial_stock') border-red-500 @enderror">
                    <p class="text-xs text-gray-400 mt-1">Stok awal barang yang akan ditambahkan ke sistem</p>
                    @error('initial_stock')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-100"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="px-4 bg-white text-xs text-gray-400">
                        <i class="fas fa-info-circle mr-1"></i> Pastikan data sudah benar
                    </span>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row gap-3 justify-end">
                <a href="{{ route('barang.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 transition-all duration-200 text-sm font-medium">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white transition-all duration-200 text-sm font-medium shadow-sm">
                    <i class="fas fa-save"></i> Simpan Barang
                </button>
            </div>
        </form>
    </div>

    <!-- Informasi  -->
    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-r-xl p-4 mt-6">
        <div class="flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
            <div class="text-sm text-blue-800">
                <p class="font-medium">Informasi Pengisian Form</p>
                <ul class="text-xs text-blue-700 mt-1 space-y-1">
                    <li>• <strong>Nama Barang</strong> harus unik dan mudah diidentifikasi</li>
                    <li>• <strong>Satuan Barang</strong> pilih satuan yang sesuai (PCS, BOX, UNIT, dll)</li>
                    <li>• <strong>Minimum Stok</strong> digunakan untuk peringatan stok menipis</li>
                    <li>• <strong>Stok Awal</strong> akan otomatis menambah histori transaksi masuk</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection