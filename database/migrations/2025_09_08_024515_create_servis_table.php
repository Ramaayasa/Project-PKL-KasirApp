<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('servis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_servis', 50)->unique();
            $table->string('nama_pelanggan', 100);
            $table->string('kontak', 50)->nullable();
            $table->text('deskripsi');
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');
            $table->decimal('biaya', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servis');
    }
};
