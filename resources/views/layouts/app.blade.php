<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

    <!-- NAVBAR -->
    <div class="bg-white shadow px-6 py-4 flex justify-between">
        <h1 class="font-bold text-lg">Inventaris</h1>

        <div class="flex gap-4">
            <a href="/barang" class="text-sm hover:text-blue-500">Barang</a>
            <a href="/admin/requests" class="text-sm hover:text-blue-500">Request</a>
        </div>
    </div>

    <!-- CONTENT -->
    <div class="p-6">
        @yield('content')
    </div>

</body>
</html>