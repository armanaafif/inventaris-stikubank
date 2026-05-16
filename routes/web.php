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
use App\Http\Controllers\Admin\UserManagementController;

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

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

});

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

Route::middleware('auth')->group(function () {

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

});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Approval Request Barang
    |--------------------------------------------------------------------------
    */

    Route::get('/requests', [StockRequestController::class, 'index']);

    Route::post('/requests/{id}/approve', [

        StockRequestController::class,
        'approve'

    ]);

    Route::post('/requests/{id}/reject', [

        StockRequestController::class,
        'reject'

    ]);

    /*
    |--------------------------------------------------------------------------
    | Management User
    |--------------------------------------------------------------------------
    */

    Route::get('/users', [

        UserManagementController::class,
        'index'

    ]);

    Route::post('/users/{id}/approve', [

        UserManagementController::class,
        'approve'

    ]);

    Route::post('/users/{id}/reject', [

        UserManagementController::class,
        'reject'

    ]);

});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';