@extends('layouts.dashboard-sidebar')

@section('title', 'Profile - Inventaris Stikubank')
@section('page-title', 'Profile Saya')
@section('page-subtitle', 'Kelola informasi akun Anda')

@section('content')
<div class="max-w-5xl mx-auto">
    
    <!-- Profile Header dengan Cover -->
    <div class="relative mb-8">
        <!-- Cover Background -->
        <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-t-2xl"></div>
        
        <!-- Profile Avatar Card -->
        <div class="relative px-6">
            <div class="flex flex-col md:flex-row items-center md:items-end -mt-12 mb-6">
                <!-- Avatar Large -->
                <div class="relative">
                    <div class="w-28 h-28 rounded-full bg-white p-1 shadow-lg">
                        <div class="w-full h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                            <span class="text-4xl font-bold text-white">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </span>
                        </div>
                    </div>
                    <div class="absolute bottom-0 right-0 w-6 h-6 bg-green-500 rounded-full border-2 border-white"></div>
                </div>
                
                <!-- User Info -->
                <div class="md:ml-5 mt-4 md:mt-0 text-center md:text-left flex-1">
                    <h2 class="text-2xl font-bold text-gray-800">{{ auth()->user()->name ?? 'User' }}</h2>
                    <div class="flex flex-wrap items-center gap-2 mt-1 justify-center md:justify-start">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700">
                            <i class="fas fa-user-shield text-xs"></i> {{ ucfirst(auth()->user()->role ?? 'User') }}
                        </span>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-full bg-green-100 text-green-700">
                            <i class="fas fa-check-circle text-xs"></i> {{ auth()->user()->status === 'approved' ? 'Aktif' : 'Pending' }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs text-gray-400">
                            <i class="fas fa-calendar-alt"></i> Bergabung {{ auth()->user()->created_at ? auth()->user()->created_at->format('d M Y') : '-' }}
                        </span>
                    </div>
                </div>
                
                <!-- Badge / Stats -->
                <div class="flex gap-4 mt-4 md:mt-0">
                    <div class="text-center bg-gray-50 rounded-xl px-4 py-2">
                        <p class="text-xs text-gray-400">Total Barang</p>
                        <p class="text-lg font-bold text-gray-800">{{ App\Models\Consumable::count() }}</p>
                    </div>
                    <div class="text-center bg-gray-50 rounded-xl px-4 py-2">
                        <p class="text-xs text-gray-400">Transaksi</p>
                        <p class="text-lg font-bold text-gray-800">{{ App\Models\ConsumableTransaction::count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Update Profile - 2 Kolom -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white px-6 py-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-user-edit text-blue-600 text-sm"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-800">Edit Profil</h3>
                    <p class="text-xs text-gray-400">Perbarui informasi akun Anda</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Left Column -->
                <div class="space-y-5">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-user mr-1 text-gray-400"></i> Nama Lengkap
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', auth()->user()->name) }}" 
                               required
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-envelope mr-1 text-gray-400"></i> Alamat Email
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', auth()->user()->email) }}" 
                               required
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-5">
                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-lock mr-1 text-gray-400"></i> Password Baru
                        </label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               placeholder="Kosongkan jika tidak ingin mengubah"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('password') border-red-500 @enderror">
                        <p class="text-xs text-gray-400 mt-1">
                            <i class="fas fa-info-circle"></i> Minimal 8 karakter
                        </p>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                            <i class="fas fa-lock mr-1 text-gray-400"></i> Konfirmasi Password Baru
                        </label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               placeholder="Ulangi password baru"
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-100"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="px-4 bg-white text-xs text-gray-400">
                        <i class="fas fa-shield-alt mr-1"></i> Data Anda aman
                    </span>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex flex-col sm:flex-row gap-3 justify-end">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 transition-all duration-200 text-sm font-medium">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white transition-all duration-200 text-sm font-medium shadow-sm">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Informasi Akun Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        
        <!-- Security Info -->
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-5 border border-green-100">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-shield-alt text-green-600 text-lg"></i>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-800">Keamanan Akun</h4>
                    <p class="text-xs text-gray-500 mt-1">Akun Anda dilindungi dengan enkripsi password. Jangan bagikan password kepada siapapun.</p>
                </div>
            </div>
        </div>

        <!-- Account Info -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600 text-lg"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-800">Informasi Akun</h4>
                    <div class="grid grid-cols-2 gap-2 mt-2 text-xs">
                        <div>
                            <span class="text-gray-500">ID User:</span>
                            <span class="text-gray-700 font-medium ml-1">{{ auth()->user()->id }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Role:</span>
                            <span class="text-gray-700 font-medium ml-1 capitalize">{{ auth()->user()->role ?? 'User' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Status:</span>
                            <span class="text-green-600 font-medium ml-1">{{ auth()->user()->status === 'approved' ? 'Aktif' : 'Pending' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Bergabung:</span>
                            <span class="text-gray-700 font-medium ml-1">{{ auth()->user()->created_at ? auth()->user()->created_at->format('d/m/Y') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        confirmButtonColor: '#10b981',
        timer: 3000,
        timerProgressBar: true,
        background: '#fff',
        iconColor: '#10b981'
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#ef4444',
        background: '#fff',
        iconColor: '#ef4444'
    });
</script>
@endif
@endpush