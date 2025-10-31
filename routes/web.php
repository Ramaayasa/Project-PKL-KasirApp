<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ServisController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KasirController; 

// Halaman dashboard (default)
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

// Barang (manajemen stok)
Route::resource('barang', BarangController::class);

// Servis (penerimaan servis pelanggan)
Route::get('/servis', [ServisController::class, 'index'])->name('servis.index');
Route::get('/servis/create', [ServisController::class, 'create'])->name('servis.create');
Route::post('/servis', [ServisController::class, 'store'])->name('servis.store');
Route::get('/servis/{id}', [ServisController::class, 'show'])->name('servis.show');
Route::get('/servis/{id}/edit', [ServisController::class, 'edit'])->name('servis.edit');
Route::put('/servis/{id}', [ServisController::class, 'update'])->name('servis.update');
Route::delete('/servis/{id}', [ServisController::class, 'destroy'])->name('servis.destroy');

// Transaksi (kasir)

Route::prefix('kasir')->name('kasir.')->group(function () {
    Route::get('/', [KasirController::class, 'index'])->name('index');
    Route::get('/search', [KasirController::class, 'searchBarang'])->name('search');
    Route::post('/store', [KasirController::class, 'store'])->name('store');
    Route::get('/riwayat', [KasirController::class, 'riwayat'])->name('riwayat');
    Route::get('/{id}', [KasirController::class, 'show'])->name('show');
    Route::get('/{id}/print', [KasirController::class, 'printStruk'])->name('print');
});
