<!DOCTYPE html>
<html>
<head>
    <title>Detail Barang</title>
    @vite('resources/css/app.css')
</head>
<body>
@if($stock <= 0)
    <p style="color:red">Stok habis!</p>
@endif

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

<h2>{{ $item->name }}</h2>

<h3>
    Stok Saat Ini: 
    <strong>{{ $stock }} {{ $item->unitMeasure->name }}</strong>
</h3>

<h3>Tambah Stok</h3>
<form method="POST" action="/add-stock">
    @csrf
    <input type="hidden" name="consumable_id" value="{{ $item->id }}">
    <input type="number" name="quantity" placeholder="Jumlah"><br>
    <input type="text" name="note" placeholder="Catatan"><br>
    <button type="submit">Tambah</button>
</form>

<h3>Pakai Barang</h3>
<form method="POST" action="/take-stock">
    @csrf
    <input type="hidden" name="consumable_id" value="{{ $item->id }}">
    <input type="number" name="quantity" max="{{ $stock }}" placeholder="Jumlah">
    <input type="text" name="note" placeholder="Catatan"><br>
    <button type="submit" {{ $stock <= 0 ? 'disabled' : '' }}>
    Gunakan
</button>

<form method="GET">
    <select name="type">
        <option value="">Semua</option>
        <option value="IN">Masuk</option>
        <option value="OUT">Keluar</option>
    </select>
    <button type="submit">Filter</button>
</form>

</form>

<h3>Histori Transaksi</h3>

<table border="1" cellpadding="10">
    <tr>
        <th>Tipe</th>
        <th>Jumlah</th>
        <th>Catatan</th>
        <th>Tanggal</th>
    </tr>

    @foreach($transactions as $trx)
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