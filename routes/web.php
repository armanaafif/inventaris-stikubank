<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\Admin\StockRequestController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    return view('welcome');

});

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

});

/*
|--------------------------------------------------------------------------
| BARANG
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Daftar Barang
|--------------------------------------------------------------------------
*/

Route::get('/barang', [ConsumableController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Tambah Barang
|--------------------------------------------------------------------------
*/

Route::get('/barang/create', [ConsumableController::class, 'create']);

Route::post('/barang/store', [ConsumableController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Detail Barang
|--------------------------------------------------------------------------
*/

Route::get('/barang/{id}', [ConsumableController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Monitoring Stok
|--------------------------------------------------------------------------
*/

Route::get('/stock', [ConsumableController::class, 'stock']);

/*
|--------------------------------------------------------------------------
| Histori Transaksi
|--------------------------------------------------------------------------
*/

Route::get('/history', [ConsumableController::class, 'history']);

/*
|--------------------------------------------------------------------------
| Manipulasi Stok
|--------------------------------------------------------------------------
*/

Route::post('/add-stock', [ConsumableController::class, 'addStock']);

Route::post('/take-stock', [ConsumableController::class, 'takeStock']);

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Approval Request
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/requests', [StockRequestController::class, 'index']);

    Route::post('/admin/requests/{id}/approve', [
        StockRequestController::class,
        'approve'
    ]);

    Route::post('/admin/requests/{id}/reject', [
        StockRequestController::class,
        'reject'
    ]);

});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';