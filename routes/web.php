<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::middleware(['guest'])->group(function () {
// });
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['AuthCheck'])->group(function () {
    Route::get('/owner', fn() => view('owner'))->name('owner')->middleware('UserAccess:owner');

    Route::get('/admin', fn() => view('admin'))->name('admin')->middleware('UserAccess:admin');

    Route::get('/kasir', fn() => view('kasir'))->name('kasir')->middleware('UserAccess:kasir');
});
