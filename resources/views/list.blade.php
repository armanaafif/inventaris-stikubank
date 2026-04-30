@extends('layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">Daftar Barang</h1>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-left">Stok</th>
                    <th class="p-3 text-left">Satuan</th>
                    <th class="p-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-3 font-medium">{{ $item->name }}</td>
                    <td class="p-3">{{ $item->stock }}</td>
                    <td class="p-3">{{ $item->unitMeasure->name ?? '-' }}</td>
                    <td class="p-3 text-center">
                        <a href="/barang/{{ $item->id }}"
                           class="bg-blue-500 text-white px-3 py-1 rounded text-xs">
                           Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center p-6 text-gray-500">
                        Tidak ada data
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

    <div class="mt-4">
        {{ $data->links() }}
    </div>

</div>
@endsection