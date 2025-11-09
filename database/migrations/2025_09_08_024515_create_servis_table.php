<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('servis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_servis', 50)->unique();
            $table->string('nama_pelanggan', 100);
            $table->string('no_telepon', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->text('kelengkapan')->nullable();
            $table->text('password');
            $table->string('warna_barang', 50);
            $table->string('tipe_barang', 100);
            $table->string('seri_barang', 100);
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');
            $table->text('keluhan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servis');
    }
};