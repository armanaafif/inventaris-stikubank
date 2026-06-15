@extends('layouts.dashboard-sidebar')

@section('title', 'Manajemen User - Inventaris Stikubank')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Manajemen User</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola akun staff dan approval user baru</p>
        </div>
        <button onclick="openCreateModal()" class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg transition flex items-center gap-2">
            <i class="fas fa-user-plus"></i> Tambah Staff
        </button>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Total User</p>
            <p class="text-2xl font-bold text-gray-800">{{ $totalUser ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Pending</p>
            <p class="text-2xl font-bold text-yellow-600">{{ $pendingUser ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Active</p>
            <p class="text-2xl font-bold text-green-600">{{ $approvedUser ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Admin</p>
            <p class="text-2xl font-bold text-blue-600">{{ $adminCount ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-400">Staff</p>
            <p class="text-2xl font-bold text-purple-600">{{ $staffCount ?? 0 }}</p>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <form method="GET" action="{{ route('admin.users') }}" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Cari nama atau email..." 
                class="flex-1 min-w-[200px] rounded-lg border border-gray-300 px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500">
            
            <select name="status" class="rounded-lg border border-gray-300 px-4 py-2 text-sm w-32">
                <option value="all">Semua Status</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            </select>

            <select name="role" class="rounded-lg border border-gray-300 px-4 py-2 text-sm w-32">
                <option value="all">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
            </select>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.users') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-redo mr-1"></i> Reset
            </a>
        </form>
    </div>

    <!-- Tabel User -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr class="text-left text-xs font-medium text-gray-500">
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Bergabung</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                    <tr class="text-sm hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $user->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-700">
                                    <i class="fas fa-crown mr-1 text-xs"></i> Admin
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                                    <i class="fas fa-user mr-1 text-xs"></i> Staff
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($user->status === 'approved')
                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-green-100 text-green-700">
                                    <i class="fas fa-check-circle mr-1 text-xs"></i> Approved
                                </span>
                            @else
                                <span class="inline-flex px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700">
                                    <i class="fas fa-clock mr-1 text-xs"></i> Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($user->status === 'pending')
                                <div class="flex items-center justify-center gap-2">
                                    <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-xs px-3 py-1.5 rounded-lg transition" 
                                            onclick="return confirm('Approve user {{ $user->name }}?')">
                                            <i class="fas fa-check mr-1"></i> Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.reject', $user->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1.5 rounded-lg transition"
                                            onclick="return confirm('Tolak user {{ $user->name }}? User akan dihapus.')">
                                            <i class="fas fa-times mr-1"></i> Reject
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="flex items-center justify-center gap-2">
                                    <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" class="inline" 
                                          onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs px-3 py-1.5 rounded-lg transition">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-users text-3xl mb-2 block"></i>
                            Tidak ada data user
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- Informasi -->
    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-r-xl p-4">
        <div class="flex items-center gap-3">
            <i class="fas fa-info-circle text-blue-500"></i>
            <div class="text-sm text-blue-800">
                User dengan status <strong>Pending</strong> perlu diapprove oleh Admin. User yang <strong>Reject</strong> akan dihapus dari database.
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH STAFF -->
<div id="createStaffModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeModalOnClick(event)">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center p-5 border-b">
            <h2 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-user-plus mr-2 text-green-600"></i> Tambah Staff Baru
            </h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.users.create-staff') }}" class="p-5">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                <p class="text-xs text-gray-400 mt-1">Minimal 6 karakter</p>
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role <span class="text-red-500">*</span></label>
                <select name="role" required class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-3 border-t">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2 rounded-lg transition">
                    <i class="fas fa-save mr-1"></i> Simpan
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 rounded-lg transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCreateModal() {
        document.getElementById('createStaffModal').classList.remove('hidden');
        document.getElementById('createStaffModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('createStaffModal').classList.add('hidden');
        document.getElementById('createStaffModal').classList.remove('flex');
    }

    function closeModalOnClick(event) {
        if (event.target === event.currentTarget) {
            closeModal();
        }
    }

    @if($errors->any() && session('showCreateModal'))
        openCreateModal();
    @endif
</script>
@endsection