<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaksi_id'); 
            $table->unsignedBigInteger('barang_id');     
            $table->integer('jumlah');
            $table->decimal('harga', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            // Foreign key dengan onDelete cascade (lebih flexible)
            $table->foreign('transaksi_id')
                ->references('id')
                ->on('transaksi')
                ->onDelete('cascade');

            $table->foreign('barang_id')
                ->references('id')
                ->on('barangs')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_details');
    }
};