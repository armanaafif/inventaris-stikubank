<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang</title>
</head>
<body>

    <form method="GET" action="/barang">
    <input type="text" name="search" value="{{ request('search') }}">
    <button type="submit">Cari</button>
</form>

    <table border="1" cellpadding="10">
    <tr>
        <th>Nama</th>
        <th>Stok</th>
        <th>Satuan</th>
    </tr>
    

    @foreach($data as $item)
    <tr>
        <td>
            <a href="/barang/{{ $item->id }}">
                {{ $item->name }}
            </a>
        </td>

        <td style="color: {{ ($item->stock ?? 0) < 10 ? 'red' : 'black' }}">
    {{ $item->stock ?? 0 }}

    @if(($item->stock ?? 0) < 10)
        <span style="color:red">(Low)</span>
    @endif
</td>

        <td>
            {{ $item->unitMeasure->name ?? '-' }}
        </td>
    </tr>
    @endforeach

</table>
{{ $data->links() }}
</body>
</html>