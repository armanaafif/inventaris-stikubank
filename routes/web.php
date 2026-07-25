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
use App\Http\Controllers\LocationController;

use App\Http\Controllers\Admin\StockRequestController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\BorrowingController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| PROFILE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| BARANG
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/barang', [ConsumableController::class, 'index'])->name('barang.index');

    Route::get('/barang/create', [ConsumableController::class, 'create'])->name('barang.create');
    Route::get('/barang/store', fn () => redirect()->route('barang.create'));
    Route::post('/barang', [ConsumableController::class, 'store'])->name('barang.store');
    Route::post('/barang/store', [ConsumableController::class, 'store']);

    Route::get('/barang/{id}/edit', [ConsumableController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{id}', [ConsumableController::class, 'update'])->name('barang.update');
    Route::get('/barang/{id}', [ConsumableController::class, 'show'])->name('barang.show');

    Route::delete('/barang/{id}', [ConsumableController::class, 'destroy'])->name('barang.destroy');

    Route::get('/stock', [ConsumableController::class, 'stock'])->name('stock.index');

    Route::get('/history', [ConsumableController::class, 'history'])->name('history.index');

    Route::post('/add-stock', [ConsumableController::class, 'addStock'])->name('stock.add');
    Route::post('/take-stock', [ConsumableController::class, 'takeStock'])->name('stock.take');
    Route::post('/stock/transfer', [ConsumableController::class, 'transferStock'])->name('stock.transfer');

    Route::post('/borrow-item', [ConsumableController::class, 'borrowItem'])->name('borrow.item');

    Route::post('/locations/quick-store', [LocationController::class, 'store'])->name('locations.quick-store');
    Route::post('/categories/quick-store', [ConsumableController::class, 'storeCategory'])->name('categories.quick-store');
    Route::get('/categories/{category}/next-item-number', [ConsumableController::class, 'nextItemNumber'])->name('categories.next-item-number');

});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Approval Request Barang
    |--------------------------------------------------------------------------
    */

    Route::get('/requests', [StockRequestController::class, 'index'])->name('requests');
    Route::post('/requests/{id}/approve', [StockRequestController::class, 'approve'])->name('requests.approve');
    Route::post('/requests/{id}/reject', [StockRequestController::class, 'reject'])->name('requests.reject');

    /*
    |--------------------------------------------------------------------------
    | Management User
    |--------------------------------------------------------------------------
    */

    Route::get('/users', [UserManagementController::class, 'index'])->name('users');
    
    Route::post('/users/{id}/approve', [UserManagementController::class, 'approve'])->name('users.approve');
    
    Route::post('/users/{id}/reject', [UserManagementController::class, 'reject'])->name('users.reject');
    
    Route::post('/users/{id}/role', [UserManagementController::class, 'updateRole'])->name('users.role');
    
    Route::post('/users/create-staff', [UserManagementController::class, 'createStaff'])->name('users.create-staff');

    // Delete user account
    Route::delete('/users/{id}/delete', [UserManagementController::class, 'destroy'])->name('users.delete');

    /*
    |--------------------------------------------------------------------------
    | Manajemen Peminjaman Barang (Admin)
    |--------------------------------------------------------------------------
    */

    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings');
    
    Route::post('/borrowings/{id}/approve', [BorrowingController::class, 'approve'])->name('borrowings.approve');
    
    Route::post('/borrowings/{id}/reject', [BorrowingController::class, 'reject'])->name('borrowings.reject');
    
    Route::post('/borrowings/{id}/return', [BorrowingController::class, 'returnItem'])->name('borrowings.return');

});

require __DIR__.'/auth.php';
