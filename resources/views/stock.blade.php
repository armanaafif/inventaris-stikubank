@extends('layouts.app')

@section('content')
<div class="p-6">

    <h1 class="text-2xl font-bold mb-6">Manajemen Stok</h1>

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">Nama</th>
                    <th class="p-3 text-left">Stok</th>
                    <th class="p-3 text-left">Satuan</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $item)
                <tr class="border-b">
                    <td class="p-3">{{ $item->name }}</td>
                    <td class="p-3">{{ $item->stock }}</td>
                    <td class="p-3">{{ $item->unitMeasure->name ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center p-6 text-gray-500">
                        Tidak ada data
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>
@endsection