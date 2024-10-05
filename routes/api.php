<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KebunController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\TransaksiItemController;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/me', [AuthController::class, 'aboutme']); #Melihat Profil diri sendiri
    Route::get('/logout',[AuthController::class, 'logout']); #Yaaa logout apalagi
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
    Route::post('/add/keranjang', [TransaksiItemController::class, 'add']);
    Route::post('/beli', [TransaksiController::class, 'store']);
});

Route::get('/produk', [ProdukController::class, 'show']); #Melihat semua produk
Route::get('/produk/{id}', [ProdukController::class, 'detail']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',[AuthController::class, 'login']); #Login aja ga lebih

Route::patch('/kebun/status/{id}', [KebunController::class, 'updateStatus']); #Update Status Kebun, otomatis membuat histori
