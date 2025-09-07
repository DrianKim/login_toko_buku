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
    Route::get('admin/data-buku/edit/{id}', [AdminController::class, 'editDataBuku'])->name('admin.data-buku.edit');
    Route::put('admin/data-buku/{id}', [AdminController::class, 'updateDataBuku'])->name('admin.data-buku.update');
    Route::delete('admin/data-buku/{id}', [AdminController::class, 'deleteDataBuku'])->name('admin.data-buku.destroy');

    Route::get('admin/kategori-buku', [AdminController::class, 'kategoriBuku'])->name('admin.kategori-buku');
    Route::get('admin/kategori-buku/create', [AdminController::class, 'createKategoriBuku'])->name('admin.kategori.create');
    Route::post('admin/kategori-buku', [AdminController::class, 'storeKategoriBuku'])->name('admin.kategori.store');
    Route::get('admin/kategori-buku/edit/{id}', [AdminController::class, 'editKategoriBuku'])->name('admin.kategori.edit');
    Route::put('admin/kategori-buku/{id}', [AdminController::class, 'updateKategoriBuku'])->name('admin.kategori.update');
    Route::delete('admin/kategori-buku/{id}', [AdminController::class, 'deleteKategoriBuku'])->name('admin.kategori.destroy');

    Route::get('admin/detail-buku', [AdminController::class, 'detailBuku'])->name('admin.detail-buku');
});

// kasir
Route::middleware(['auth', 'UserAccess:kasir'])->group(function () {
    Route::get('/kasir', fn() => view('kasir.index'))->name('kasir');
});

