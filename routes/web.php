<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\KategoriController;

// Home
Route::get('/', function () {
    return redirect()->route('barang.index');
});

// Master Data
Route::resource('barang', BarangController::class);
Route::resource('kategori', KategoriController::class);

// Kasir
Route::prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/', [KasirController::class, 'index'])->name('index');
    Route::get('/search', [KasirController::class, 'search'])->name('search');
    Route::get('/all-barang', [KasirController::class, 'listBarang'])->name('getAllBarang');
    Route::post('/store', [KasirController::class, 'store'])->name('store');
    Route::get('/riwayat', [KasirController::class, 'riwayat'])->name('riwayat');
    Route::get('/{id}', [KasirController::class, 'detail'])->name('detail');
});


// Servis
Route::resource('servis', ServisController::class);
