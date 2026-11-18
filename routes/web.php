<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\PenerimaanStokController;
use App\Http\Controllers\ProdukController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return auth()->user()->role === 'Pemilik'
            ? redirect()->route('produk.index')
            : redirect()->route('kasir.index');
    });

    // Rute khusus PEMILIK (Manajemen Produk & Verifikasi Stok)
    Route::middleware('role:Pemilik')->group(function () {
        Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
        Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
        Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('produk.update');
        Route::patch('/produk/{id}/status', [ProdukController::class, 'toggleStatus'])->name('produk.status');
        Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk.destroy');

        Route::get('/verifikasi-stok', [PenerimaanStokController::class, 'index'])->name('verifikasi.index');
        Route::post('/verifikasi-stok', [PenerimaanStokController::class, 'store'])->name('verifikasi.store');
    });

    // Rute untuk KASIR dan PEMILIK (Transaksi POS)
    Route::middleware('role:Pemilik,Kasir')->group(function () {
        Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
        Route::post('/kasir/tambah/{id}', [KasirController::class, 'addToCart'])->name('kasir.add');
        Route::patch('/kasir/update/{id}', [KasirController::class, 'updateCart'])->name('kasir.update');
        Route::delete('/kasir/hapus/{id}', [KasirController::class, 'removeFromCart'])->name('kasir.remove');
        Route::delete('/kasir/kosongkan', [KasirController::class, 'clearCart'])->name('kasir.clear');
        Route::post('/kasir/bayar', [KasirController::class, 'checkout'])->name('kasir.checkout');
    });
});