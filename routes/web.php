<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\OwnerController;

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// owner
Route::middleware(['auth', 'UserAccess:owner'])->group(function () {
    Route::get('/owner', [OwnerController::class, 'index'])->name('owner');

    Route::get('/owner/data-buku', [OwnerController::class, 'dataBuku'])->name('owner.data-buku');

    Route::get('/owner/users', [OwnerController::class, 'indexUser'])->name('owner.users.index');
    Route::get('owner/users/create', [OwnerController::class, 'createUser'])->name('owner.users.create');
    Route::post('owner/users', [OwnerController::class, 'storeUser'])->name('owner.users.store');
    Route::get('owner/users/edit/{id}', [OwnerController::class, 'editUser'])->name('owner.users.edit');
    Route::put('owner/users/{id}', [OwnerController::class, 'updateUser'])->name('owner.users.update');
    Route::delete('owner/users/{id}', [OwnerController::class, 'deleteUser'])->name('owner.users.destroy');
});

// admin
Route::middleware(['auth', 'UserAccess:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin');

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
    Route::get('admin/detail-buku/create', [AdminController::class, 'createDetailBuku'])->name('admin.detail-buku.create');
    Route::post('admin/detail-buku', [AdminController::class, 'storeDetailBuku'])->name('admin.detail-buku.store');

    Route::post('/admin/detail/{id}/tambah-stok', [AdminController::class, 'tambahStok'])->name('admin.detail-buku.tambah-stok');
    Route::put('/admin/detail/{id}/update-harga', [AdminController::class, 'updateHarga'])->name('admin.detail-buku.update-harga');


    Route::get('admin/users', [AdminController::class, 'indexUser'])->name('admin.users.index');
    Route::get('admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::get('admin/users/edit/{id}', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.destroy');
});

// kasir
Route::middleware(['auth', 'UserAccess:kasir'])->group(function () {
    Route::get('/kasir', [KasirController::class, 'dashboard'])->name('kasir');

    // Route::get('kasir/data-buku', [KasirController::class, 'indexBuku'])->name('kasir.buku');

    // Route::get('kasir/transaksi', [KasirController::class, 'indexTransaksi'])->name('kasir.transaksi');
    // Route::post('/kasir/transaksi/addTokeranjang/{id}', [KasirController::class, 'addTokeranjang'])->name('kasir.transaksi.add');
    // Route::post('/kasir/transaksi/update-qty/{id}', [KasirController::class, 'updateQty'])->name('kasir.transaksi.update');
    // Route::post('/kasir/transaksi/remove/{id}', [KasirController::class, 'removeFromkeranjang'])->name('transaksi.remove');
    // Route::get('/kasir/transaksi/checkout', [KasirController::class, 'checkout'])->name('kasir.transaksi.checkout');
    // Route::get('/kasir/transaksi/struk/{id}', [KasirController::class, 'struk'])->name('kasir.transaksi.struk');

    Route::get('/kasir/keranjang', [KasirController::class, 'index'])->name('kasir.buku');
    Route::post('/kasir/keranjang/tambah', [KasirController::class, 'tambahKeranjang'])->name('kasir.keranjang.tambah');
    Route::post('/kasir/keranjang/update', [KasirController::class, 'updateKeranjang'])->name('kasir.keranjang.update');
    Route::post('/kasir/keranjang/hapus', [KasirController::class, 'hapusKeranjang'])->name('kasir.keranjang.hapus');
    Route::get('/kasir/keranjang/get', [KasirController::class, 'getkeranjang'])->name('kasir.keranjang.get');
    Route::post('/kasir/checkout', [KasirController::class, 'checkout'])->name('kasir.checkout');
    Route::get('/kasir/struk/{id}', [KasirController::class, 'struk'])->name('kasir.struk');

    Route::get('kasir/riwayat', [KasirController::class, 'riwayatTransaksi'])->name('kasir.riwayat');
});
