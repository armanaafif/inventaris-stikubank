<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\Admin\StockRequestController;
use Illuminate\Support\Facades\Route;

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
| DASHBOARD (AUTH)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROFILE (AUTH)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| FITUR BARANG
|--------------------------------------------------------------------------
*/

Route::get('/barang', [ConsumableController::class, 'index']);
Route::get('/barang/{id}', [ConsumableController::class, 'show']);

Route::post('/add-stock', [ConsumableController::class, 'addStock']);
Route::post('/take-stock', [ConsumableController::class, 'takeStock']);

/*
|--------------------------------------------------------------------------
| ADMIN (PROTECTED)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/admin/requests', [StockRequestController::class, 'index']);
    Route::post('/admin/requests/{id}/approve', [StockRequestController::class, 'approve']);
    Route::post('/admin/requests/{id}/reject', [StockRequestController::class, 'reject']);

});

/*
|--------------------------------------------------------------------------
| AUTH (WAJIB DIBAWAH)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';