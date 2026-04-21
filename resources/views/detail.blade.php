<!DOCTYPE html>
<html>
<head>
    <title>Detail Barang</title>
</head>
<body>

<h2>{{ $item->name }}</h2>

<p>Stok: {{ $stock }} {{ $item->unitMeasure->name }}</p>

<h3>Histori Transaksi</h3>

<table border="1" cellpadding="10">
    <tr>
        <th>Tipe</th>
        <th>Jumlah</th>
        <th>Catatan</th>
        <th>Tanggal</th>
    </tr>

    @foreach($item->transactions as $trx)
    <tr>
        <td>{{ $trx->type }}</td>
        <td>{{ $trx->quantity }}</td>
        <td>{{ $trx->note }}</td>
        <td>{{ $trx->created_at }}</td>
    </tr>
    @endforeach

</table>

</body>
</html>