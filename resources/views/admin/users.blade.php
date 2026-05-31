@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Manajemen User</h1>
            <p class="text-gray-500 mt-2">Kelola akun staff, approval user baru, dan kontrol akses sistem</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <button onclick="openCreateModal()"
                class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-medium px-5 py-3 rounded-xl transition shadow-sm">
                + Tambah Staff
            </button>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium px-5 py-3 rounded-xl transition">
                Dashboard
            </a>
        </div>
    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5">
            <p class="text-sm font-medium text-gray-500">Total User</p>
            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalUser ?? 0 }}</p>
        </div>
        <div class="bg-yellow-50 border border-yellow-100 rounded-2xl shadow-sm p-5">
            <p class="text-sm font-medium text-yellow-600">Pending</p>
            <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $pendingUser ?? 0 }}</p>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-2xl shadow-sm p-5">
            <p class="text-sm font-medium text-green-600">Active</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $approvedUser ?? 0 }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-2xl shadow-sm p-5">
            <p class="text-sm font-medium text-blue-600">Admin</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $adminCount ?? 0 }}</p>
        </div>
        <div class="bg-purple-50 border border-purple-100 rounded-2xl shadow-sm p-5">
            <p class="text-sm font-medium text-purple-600">Staff</p>
            <p class="text-3xl font-bold text-purple-600 mt-2">{{ $staffCount ?? 0 }}</p>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 mb-6">
        <form method="GET" action="{{ route('admin.users') }}" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Cari nama atau email..."
                class="flex-1 min-w-[200px] rounded-lg border border-gray-300 px-4 py-2 text-sm">
            
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

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Filter</button>
            <a href="{{ route('admin.users') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm">Reset</a>
        </form>
    </div>

    <!-- Tabel User -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">User</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">Role</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">Status</th>
                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">Bergabung</th>
                        <th class="text-center px-6 py-4 text-sm font-semibold text-gray-600">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                            <p class="text-sm text-gray-400">{{ $user->email }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($user->role === 'admin')
                                <span class="bg-purple-100 text-purple-700 text-xs font-medium px-3 py-1 rounded-full">Admin</span>
                            @else
                                <span class="bg-blue-100 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">Staff</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($user->status === 'approved')
                                <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">Approved</span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-medium px-3 py-1 rounded-full">Pending</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-600 text-sm">
                            {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($user->status === 'pending')
                                <div class="flex justify-center gap-2">
                                    <form method="POST" action="{{ route('admin.users.approve', $user->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-sm px-3 py-1.5 rounded-lg">
                                            Approve
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.reject', $user->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm px-3 py-1.5 rounded-lg">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-12 text-gray-500">Tidak ada data user</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="border-t px-6 py-4 bg-gray-50">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <div class="mt-6 bg-blue-50 rounded-xl border border-blue-100 p-4">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="text-sm text-blue-800 font-medium">Informasi Manajemen User</p>
                <p class="text-xs text-blue-600 mt-1">
                    • User dengan status <strong>Pending</strong> perlu diapprove oleh Admin.<br>
                    • User yang sudah <strong>Approved</strong> dapat login ke sistem.<br>
                    • Klik <strong>Tambah Staff</strong> untuk membuat akun baru langsung (langsung approved).
                </p>
            </div>
        </div>
    </div>

</div>

<!-- MODAL TAMBAH STAFF -->
<div id="createStaffModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50" onclick="closeModalOnClick(event)">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center p-5 border-b bg-gradient-to-r from-green-50 to-green-100 rounded-t-2xl">
            <div class="flex items-center gap-3">
                <div class="bg-green-600 text-white p-2 rounded-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Tambah Staff Baru</h2>
                    <p class="text-xs text-gray-500 mt-0.5">Langsung approve tanpa perlu pendaftaran</p>
                </div>
            </div>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.users.create-staff') }}" class="p-5">
            @csrf

            <div class="bg-blue-50 rounded-lg p-3 mb-5 text-xs text-blue-700 flex items-start gap-2">
                <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>User yang ditambahkan akan langsung <strong>APPROVED</strong> dan bisa login.</span>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-500">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-500">
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Password <span class="text-red-500">*</span></label>
                <input type="password" name="password" id="password" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-500">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation" required
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role / Level Akses <span class="text-red-500">*</span></label>
                <select name="role" required class="w-full rounded-lg border border-gray-300 px-3 py-2">
                    <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff - Akses terbatas</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin - Akses penuh</option>
                </select>
                @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3 pt-3 border-t">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 rounded-lg transition">
                    Simpan & Tambah Staff
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 rounded-lg transition">
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