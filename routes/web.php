<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Http\Controllers\ConsumableController;

Route::post('/add-stock', [ConsumableController::class, 'addStock']);
Route::post('/take-stock', [ConsumableController::class, 'takeStock']);
Route::get('/stock/{id}', [ConsumableController::class, 'getStock']);

Route::get('/test-add-stock', function () {
    return '
        <form method="POST" action="/add-stock">
            <input type="hidden" name="_token" value="' . csrf_token() . '">
            <input type="number" name="consumable_id" placeholder="ID Barang"><br>
            <input type="number" name="quantity" placeholder="Jumlah"><br>
            <input type="text" name="note" placeholder="Catatan"><br>
            <button type="submit">Submit</button>
        </form>
    ';
});

Route::get('/stock', function () {
    return view('stock');
    Route::get('/barang', [ConsumableController::class, 'index']);
});