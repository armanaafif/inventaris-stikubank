@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

        <div>

            <h1 class="text-3xl font-bold text-gray-800">
                Manajemen User
            </h1>

            <p class="text-gray-500 mt-2">
                Kelola akun staff, approval user baru, dan kontrol akses sistem
            </p>

        </div>

        <!-- Navigasi -->
        <div class="flex flex-wrap gap-3">

            <a
                href="/dashboard"
                class="inline-flex items-center justify-center bg-white border border-gray-300 hover:bg-gray-100 text-gray-700 font-medium px-5 py-3 rounded-xl transition"
            >
                Dashboard
            </a>

            <a
                href="/admin/requests"
                class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-3 rounded-xl transition"
            >
                Approval Request
            </a>

        </div>

    </div>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <!-- Total -->
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <p class="text-sm font-medium text-gray-500">
                Total User
            </p>

            <h2 class="text-4xl font-bold text-gray-800 mt-3">
                {{ $totalUser }}
            </h2>

            <p class="text-sm text-gray-400 mt-4">
                Semua akun terdaftar
            </p>

        </div>

        <!-- Pending -->
        <div class="bg-white border border-yellow-100 rounded-2xl shadow-sm p-6">

            <p class="text-sm font-medium text-yellow-600">
                Pending Approval
            </p>

            <h2 class="text-4xl font-bold text-yellow-500 mt-3">
                {{ $pendingUser }}
            </h2>

            <p class="text-sm text-gray-400 mt-4">
                Menunggu persetujuan admin
            </p>

        </div>

        <!-- Approved -->
        <div class="bg-white border border-green-100 rounded-2xl shadow-sm p-6">

            <p class="text-sm font-medium text-green-600">
                User Active
            </p>

            <h2 class="text-4xl font-bold text-green-600 mt-3">
                {{ $approvedUser }}
            </h2>

            <p class="text-sm text-gray-400 mt-4">
                Sudah dapat akses sistem
            </p>

        </div>

    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

        <!-- Header -->
        <div class="px-6 py-5 border-b bg-gray-50">

            <h2 class="text-lg font-semibold text-gray-800">
                Data User
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Monitoring akun dan approval akses
            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <!-- Table Head -->
                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            User
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Role
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Status
                        </th>

                        <th class="text-left px-6 py-4 text-sm font-semibold text-gray-600">
                            Bergabung
                        </th>

                        <th class="text-center px-6 py-4 text-sm font-semibold text-gray-600">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <!-- Table Body -->
                <tbody>

                    @forelse($users as $user)

                        <tr class="border-b last:border-b-0 hover:bg-gray-50 transition">

                            <!-- User -->
                            <td class="px-6 py-5">

                                <div>

                                    <p class="font-semibold text-gray-800">
                                        {{ $user->name }}
                                    </p>

                                    <p class="text-sm text-gray-400 mt-1">
                                        {{ $user->email }}
                                    </p>

                                </div>

                            </td>

                            <!-- Role -->
                            <td class="px-6 py-5">

                                @if($user->role === 'admin')

                                    <span class="bg-purple-100 text-purple-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Admin
                                    </span>

                                @else

                                    <span class="bg-blue-100 text-blue-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Staff
                                    </span>

                                @endif

                            </td>

                            <!-- Status -->
                            <td class="px-6 py-5">

                                @if($user->status === 'approved')

                                    <span class="bg-green-100 text-green-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Approved
                                    </span>

                                @else

                                    <span class="bg-yellow-100 text-yellow-700 text-xs font-medium px-3 py-1 rounded-full">
                                        Pending
                                    </span>

                                @endif

                            </td>

                            <!-- Created -->
                            <td class="px-6 py-5 text-gray-600">

                                {{ $user->created_at->format('d M Y') }}

                            </td>

                            <!-- Action -->
                            <td class="px-6 py-5">

                                @if($user->status === 'pending')

                                    <div class="flex justify-center gap-2">

                                        <!-- Approve -->
                                        <form
                                            method="POST"
                                            action="/admin/users/{{ $user->id }}/approve"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="bg-green-600 hover:bg-green-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition"
                                            >
                                                Approve
                                            </button>

                                        </form>

                                        <!-- Reject -->
                                        <form
                                            method="POST"
                                            action="/admin/users/{{ $user->id }}/reject"
                                        >

                                            @csrf

                                            <button
                                                type="submit"
                                                class="bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition"
                                            >
                                                Reject
                                            </button>

                                        </form>

                                    </div>

                                @else

                                    <div class="text-center">

                                        <span class="text-sm text-gray-400">
                                            Tidak ada aksi
                                        </span>

                                    </div>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-14 text-gray-500"
                            >
                                Tidak ada data user
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection