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

    Route::get('/barang', [ConsumableController::class, 'index'])->name('barang.index');

    /*
    |--------------------------------------------------------------------------
    | Tambah Barang
    |--------------------------------------------------------------------------
    */

    Route::get('/barang/create', [ConsumableController::class, 'create'])->name('barang.create');
    Route::post('/barang/store', [ConsumableController::class, 'store'])->name('barang.store');

    /*
    |--------------------------------------------------------------------------
    | Detail Barang
    |--------------------------------------------------------------------------
    */

    Route::get('/barang/{id}', [ConsumableController::class, 'show'])->name('barang.show');

    /*
    |--------------------------------------------------------------------------
    | Monitoring Stok
    |--------------------------------------------------------------------------
    */

    Route::get('/stock', [ConsumableController::class, 'stock'])->name('stock.index');

    /*
    |--------------------------------------------------------------------------
    | Histori Transaksi
    |--------------------------------------------------------------------------
    */

    Route::get('/history', [ConsumableController::class, 'history'])->name('history.index');

    /*
    |--------------------------------------------------------------------------
    | Manipulasi Stok
    |--------------------------------------------------------------------------
    */

    Route::post('/add-stock', [ConsumableController::class, 'addStock'])->name('stock.add');
    Route::post('/take-stock', [ConsumableController::class, 'takeStock'])->name('stock.take');

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

    // List user dengan filter
    Route::get('/users', [UserManagementController::class, 'index'])->name('users');
    
    // Approve user (setujui pendaftaran)
    Route::post('/users/{id}/approve', [UserManagementController::class, 'approve'])->name('users.approve');
    
    // Reject user (tolak pendaftaran)
    Route::post('/users/{id}/reject', [UserManagementController::class, 'reject'])->name('users.reject');
    
    // Update role user (ubah role admin/staff)
    Route::post('/users/{id}/role', [UserManagementController::class, 'updateRole'])->name('users.role');
    
    // Create staff baru langsung dari admin
    Route::post('/users/create-staff', [UserManagementController::class, 'createStaff'])->name('users.create-staff');

});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';