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
            $table->string('kontak', 50)->nullable();
            $table->text('deskripsi')->nullable(); // 👈 TAMBAH ->nullable()
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');
            $table->decimal('biaya', 12, 2)->nullable();
            $table->text('keluhan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servis');
    }
};