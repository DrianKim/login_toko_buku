<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use Illuminate\Routing\Router;

// Route::middleware('guest')->group(function () {
// });
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// owner
Route::middleware(['auth', 'UserAccess:owner'])->group(function () {
    Route::get('/owner', fn() => view('owner.index'))->name('owner');
});

// admin
Route::middleware(['auth', 'UserAccess:admin'])->group(function () {
    Route::get('/admin', fn() => view('admin.index'))->name('admin');

    Route::get('admin/data-buku', [AdminController::class, 'dataBuku'])->name('admin.data-buku');
    Route::get('admin/data-buku/create', [AdminController::class, 'createDataBuku'])->name('admin.data-buku.create');
    Route::post('admin/data-buku', [AdminController::class, 'storeDataBuku'])->name('admin.data-buku.store');

    Route::get('admin/kategori-buku', [AdminController::class, 'kategoriBuku'])->name('admin.kategori-buku');
    Route::get('admin/detail-buku', [AdminController::class, 'detailBuku'])->name('admin.detail-buku');
});

// kasir
Route::middleware(['auth', 'UserAccess:kasir'])->group(function () {
    Route::get('/kasir', fn() => view('kasir.index'))->name('kasir');
});

