<?php

use App\Http\Controllers\AlamatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KebunController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransaksiItemController;
use App\Http\Controllers\AlatController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::middleware(['admin'])->group(function () {
        Route::post('/sensor', [AlatController::class, 'store']); #Menambah alat
        Route::post('/produk', [ProdukController::class, 'store']); #Menambah produk
        Route::post('/produk/{id}', [ProdukController::class, 'edit']); #Edit produk
        Route::get('/produk/delete/{id}', [ProdukController::class, 'destroy']); #Menghapus produk
    });
    Route::middleware(['petani'])->group(function () {
        Route::post('/kebun', [KebunController::class, 'store']); #Menambah kebun
        Route::get('/kebun/list', [KebunController::class, 'profileKebun']); #Melihat kebun diri sendiri
        Route::get('/kebun/histori/{id}', [KebunController::class, 'histori'])->middleware('pemilik-kebun'); #lihat histori penyiraman
        Route::post('/kebun/{id}', [KebunController::class, 'update'])->middleware('pemilik-kebun');
        Route::delete('/kebun/{id}', [KebunController::class, 'destroy'])->middleware('pemilik-kebun');
    });
    Route::get('/verify-email', [AuthController::class, 'sendVerifLink']); #Kirim link verif-email ke email user
    Route::get('/logout',[AuthController::class, 'logout']); #Yaaa logout apalagi
    Route::get('/me', [AuthController::class, 'aboutme']); #Melihat Profil diri sendiri
    Route::post('/keranjang/add', [TransaksiItemController::class, 'add']); #Menambah keranjang
    Route::get('/keranjang', [TransaksiItemController::class, 'show']); #Lihat keranjang
    Route::post('/bayar', [TransaksiController::class, 'store']);
    Route::get('/bayar/berhasil/{id}', [TransaksiController::class,'berhasil']);
    Route::get('/bayar/gagal/{id}', [TransaksiController::class,'gagal']);
    Route::get('/unrated', [RatingController::class, 'unrated']);
    Route::post('/rating/{id}', [RatingController::class, 'rating']);

    Route::get('/alamat', [AlamatController::class, 'show']);
    Route::post('/alamat/add', [AlamatController::class, 'store']);
    Route::post('/alamat/{id}', [AlamatController::class, 'update'])->middleware('pemilik-alamat');
    Route::delete('/alamat/{id}', [AlamatController::class, 'destroy'])->middleware('pemilik-alamat');
    Route::get('/alamat/primary/{id}'. [AlamatController::class, 'utama']);
    Route::post('/me/photo', [AuthController::class, 'updatePhoto']);
});

Route::post('/reset-password-link', [AuthController::class, 'sendResetLink']); #Kirim link reset password ke email user
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('signed')->name('reset.password'); #Post buat reset password
Route::get('/verified', [AuthController::class, 'verifEmail'])->middleware('signed')->name('verif.email'); #Memverifikasi email
Route::get('/produk', [ProdukController::class, 'show']); #Melihat semua produk
Route::get('/produk/{id}', [ProdukController::class, 'detail']);
Route::get('/rate/{id}', [RatingController::class,'rate']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']); #Login aja ga lebih
Route::patch('/kebun/status/{id}', [AlatController::class, 'updateStatus']); #Update Status Kebun, otomatis membuat histori
Route::get('/sensor', [AlatController::class, 'show']);
