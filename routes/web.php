<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\TransaksiController;

// Halaman dashboard (default)
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Barang (manajemen stok)
Route::resource('barang', BarangController::class);

// Servis (penerimaan servis pelanggan)
Route::resource('servis', ServisController::class);

// Transaksi (kasir)
Route::resource('transaksi', TransaksiController::class);
