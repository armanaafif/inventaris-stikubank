<!DOCTYPE html>
<html>
<head>
    <title>Admin Requests</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- HEADER -->
    <div class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">Stock Request Management</h1>
        <span class="text-sm text-gray-500">Admin Panel</span>
    </div>

    <!-- CONTENT -->
    <div class="p-6">

        <!-- 🔥 STATS -->
        <div class="grid grid-cols-3 gap-4 mb-6">

            <div class="bg-yellow-100 p-4 rounded">
                <h3 class="text-sm text-gray-600">Pending</h3>
                <p class="text-2xl font-bold">{{ $totalPending }}</p>
            </div>

            <div class="bg-green-100 p-4 rounded">
                <h3 class="text-sm text-gray-600">Approved</h3>
                <p class="text-2xl font-bold">{{ $totalApproved }}</p>
            </div>

            <div class="bg-red-100 p-4 rounded">
                <h3 class="text-sm text-gray-600">Rejected</h3>
                <p class="text-2xl font-bold">{{ $totalRejected }}</p>
            </div>

        </div>

        <!-- CARD -->
        <div class="bg-white rounded-xl shadow p-5">

            <h2 class="text-lg font-semibold mb-4">Pending Requests</h2>

            <!-- 🔍 FILTER -->
            <form method="GET" class="flex gap-3 mb-4">

                <input 
                    type="text" 
                    name="search" 
                    placeholder="Cari barang..."
                    class="border px-3 py-2 rounded w-1/3"
                    value="{{ request('search') }}"
                >

                <select name="type" class="border px-3 py-2 rounded">
                    <option value="">Semua</option>
                    <option value="IN" {{ request('type') == 'IN' ? 'selected' : '' }}>IN</option>
                    <option value="OUT" {{ request('type') == 'OUT' ? 'selected' : '' }}>OUT</option>
                </select>

                <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Filter
                </button>

            </form>

            <!-- TABLE -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-200 text-left">
                            <th class="p-3">Barang</th>
                            <th class="p-3">Qty</th>
                            <th class="p-3">Tipe</th>
                            <th class="p-3">User</th>
                            <th class="p-3">Note</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($requests as $req)
                        <tr class="border-b hover:bg-gray-50">

                            <td class="p-3 font-medium">
                                {{ $req->consumable->name ?? '-' }}
                            </td>

                            <td class="p-3">
                                {{ $req->quantity }}
                                <span class="text-gray-500 text-xs">
                                    {{ $req->consumable->unitMeasure->name ?? '' }}
                                </span>
                            </td>

                            <td class="p-3">
                                @if($req->type == 'IN')
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-semibold">
                                        IN
                                    </span>
                                @elseif($req->type == 'OUT')
                                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-semibold">
                                        OUT
                                    </span>
                                @endif
                            </td>

                            <td class="p-3">
                                {{ $req->user->name ?? '-' }}
                            </td>

                            <td class="p-3 text-gray-600">
                                {{ $req->note ?? '-' }}
                            </td>

                            <td class="p-3 text-center">
                                <div class="flex justify-center gap-2">

                                    <form method="POST" action="/admin/requests/{{ $req->id }}/approve">
                                        @csrf
                                        <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-xs">
                                            Approve
                                        </button>
                                    </form>

                                    <form method="POST" action="/admin/requests/{{ $req->id }}/reject">
                                        @csrf
                                        <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-xs">
                                            Reject
                                        </button>
                                    </form>

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center p-6 text-gray-500">
                                Tidak ada request pending
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

    </div>

</body>
</html>