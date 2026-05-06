<!DOCTYPE html>
<html>
<head>
    <title>Inventaris</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100">

    <!-- NAVBAR -->
    <nav class="bg-white shadow p-4 flex justify-between">
        <div class="flex gap-4">
            <a href="/dashboard" class="font-bold">Dashboard</a>
            <a href="/barang">Barang</a>
            <a href="/admin/requests">Request</a>
        </div>

        <form method="POST" action="/logout">
            @csrf
            <button class="bg-red-500 text-white px-3 py-1 rounded">
                Logout
            </button>
        </form>
    </nav>

    <!-- CONTENT -->
    <div class="p-6">
        @yield('content')
    </div>

</body>
</html>