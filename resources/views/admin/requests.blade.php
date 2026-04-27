<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admint</title>
</head>
<body>
    <h2>Daftar Request Pending</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Barang</th>
        <th>Qty</th>
        <th>Type</th>
        <th>User</th>
        <th>Aksi</th>
    </tr>

    @foreach($requests as $req)
    <tr>
        <td>{{ $req->id }}</td>
        <td>{{ $req->consumable_id }}</td>
        <td>{{ $req->quantity }}</td>
        <td>{{ $req->type }}</td>
        <td>{{ $req->user_id }}</td>

        <td>
            <form method="POST" action="/admin/requests/{{ $req->id }}/approve" style="display:inline;">
                @csrf
                <button type="submit">Approve</button>
            </form>

            <form method="POST" action="/admin/requests/{{ $req->id }}/reject" style="display:inline;">
                @csrf
                <button type="submit">Reject</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
</body>
</html>