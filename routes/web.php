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
use App\Http\Controllers\Admin\BorrowingController; // <-- TAMBAHKAN INI

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
    | Hapus Barang
    |--------------------------------------------------------------------------
    */

    Route::delete('/barang/{id}', [ConsumableController::class, 'destroy'])->name('barang.destroy');

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

    /*
    |--------------------------------------------------------------------------
    | Peminjaman Barang
    |--------------------------------------------------------------------------
    | Route untuk melakukan peminjaman barang
    */

    Route::post('/borrow-item', [ConsumableController::class, 'borrowItem'])->name('borrow.item');

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

    /*
    |--------------------------------------------------------------------------
    | Manajemen Peminjaman Barang (Admin)
    |--------------------------------------------------------------------------
    */

    // Daftar semua peminjaman
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings');
    
    // Approve peminjaman
    Route::post('/borrowings/{id}/approve', [BorrowingController::class, 'approve'])->name('borrowings.approve');
    
    // Reject peminjaman
    Route::post('/borrowings/{id}/reject', [BorrowingController::class, 'reject'])->name('borrowings.reject');
    
    // Konfirmasi pengembalian barang
    Route::post('/borrowings/{id}/return', [BorrowingController::class, 'returnItem'])->name('borrowings.return');

});

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';