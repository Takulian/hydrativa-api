<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KebunController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransaksiItemController;
use App\Http\Controllers\HydrativaController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware(['admin'])->group(function () {
        Route::post('/produk', [ProdukController::class, 'store']); #Menambah produk
        Route::post('/produk/{id}', [ProdukController::class, 'edit']); #Edit produk
        Route::get('/produk/delete/{id}', [ProdukController::class, 'destroy']); #Menghapus produk
    });
    Route::middleware(['petani'])->group(function () {
        Route::post('/kebun', [KebunController::class, 'store']); #Menambah kebun
        Route::get('/kebun/list', [KebunController::class, 'profileKebun']); #Melihat kebun diri sendiri
        Route::get('/kebun/histori/{id}', [KebunController::class, 'histori'])->middleware('pemilik-kebun'); #lihat histori penyiraman
    });
    Route::get('/verify-email', [AuthController::class, 'sendVerifLink']);
    Route::get('/logout',[AuthController::class, 'logout']); #Yaaa logout apalagi
    Route::get('/me', [AuthController::class, 'aboutme']); #Melihat Profil diri sendiri
    Route::post('/keranjang/add', [TransaksiItemController::class, 'add']);
    Route::get('/keranjang', [TransaksiItemController::class, 'show']);
    Route::post('/bayar', [TransaksiController::class, 'store']);
    Route::get('/bayar/berhasil/{id}', [TransaksiController::class,'berhasil']);
    Route::get('/bayar/gagal/{id}', [TransaksiController::class,'gagal']);
    Route::get('/unrated', [RatingController::class, 'unrated']);
    Route::post('/rating/{id}', [RatingController::class, 'rating']);
});

Route::post('/reset-password-link', [AuthController::class, 'sendResetLink']);
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('signed')->name('reset.password');
Route::get('/verified', [AuthController::class, 'verifEmail'])->middleware('signed')->name('verif.email');

Route::get('/produk', [ProdukController::class, 'show']); #Melihat semua produk
Route::get('/produk/{id}', [ProdukController::class, 'detail']);
Route::get('/rate/{id}', [RatingController::class,'rate']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']); #Login aja ga lebih

Route::patch('/kebun/status/{id}', [KebunController::class, 'updateStatus']); #Update Status Kebun, otomatis membuat histori

<<<<<<< HEAD
Route::post('/sensor', [HydrativaController::class, 'store']);
Route::get('/show', [HydrativaController::class, 'show']);
=======


Route::get('/test-reset', [AuthController::class, 'testReset'])->middleware('signed');
>>>>>>> 0a552d28eda19475585733e7056ca5d9eca4fd1e
