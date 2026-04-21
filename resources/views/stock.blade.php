<!DOCTYPE html>
<html>
<head>
    <title>Manajemen Stok</title>
</head>
<body>

<h2>Tambah Stok</h2>
<form method="POST" action="/add-stock">
    @csrf
    <input type="number" name="consumable_id" placeholder="ID Barang"><br>
    <input type="number" name="quantity" placeholder="Jumlah"><br>
    <input type="text" name="note" placeholder="Catatan"><br>
    <button type="submit">Tambah</button>
</form>

<h2>Pakai Barang</h2>
<form method="POST" action="/take-stock">
    @csrf
    <input type="number" name="consumable_id" placeholder="ID Barang"><br>
    <input type="number" name="quantity" placeholder="Jumlah"><br>
    <input type="text" name="note" placeholder="Catatan"><br>
    <button type="submit">Gunakan</button>
</form>

</body>
</html>