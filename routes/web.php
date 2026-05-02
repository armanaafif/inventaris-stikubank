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
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    $items = \App\Models\Consumable::all();

    $totalBarang = $items->count();

    $totalStock = \App\Models\ConsumableTransaction::selectRaw("
        COALESCE(SUM(CASE WHEN type = 'IN' THEN quantity ELSE 0 END),0) -
        COALESCE(SUM(CASE WHEN type = 'OUT' THEN quantity ELSE 0 END),0)
        as total
    ")->value('total');

    $pendingRequest = \App\Models\StockRequest::where('status', 'pending')->count();

    return view('dashboard', compact(
        'totalBarang',
        'totalStock',
        'pendingRequest'
    ));

})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

/*
|--------------------------------------------------------------------------
| BARANG
|--------------------------------------------------------------------------
*/

Route::get('/barang', [ConsumableController::class, 'index']);
Route::get('/barang/{id}', [ConsumableController::class, 'show']);

Route::post('/add-stock', [ConsumableController::class, 'addStock']);
Route::post('/take-stock', [ConsumableController::class, 'takeStock']);

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/requests', [StockRequestController::class, 'index']);
    Route::post('/admin/requests/{id}/approve', [StockRequestController::class, 'approve']);
    Route::post('/admin/requests/{id}/reject', [StockRequestController::class, 'reject']);
});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';