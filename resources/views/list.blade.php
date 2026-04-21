<!DOCTYPE html>
<html>
<head>
    <title>Data Barang</title>
</head>
<body>

<h2>Data Barang</h2>

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
    </tr>
    @endforeach

</table>

</body>
</html>