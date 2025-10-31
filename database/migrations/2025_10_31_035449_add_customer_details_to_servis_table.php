<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('servis', function (Blueprint $table) {
            // Tambah kolom identitas customer
            $table->string('no_telepon', 20)->nullable()->after('nama_pelanggan');
            $table->text('alamat')->nullable()->after('no_telepon');
            $table->text('keterangan')->nullable()->after('alamat');
            $table->string('tipe_barang', 100)->nullable()->after('keterangan');
            $table->string('seri_barang', 100)->nullable()->after('tipe_barang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('servis', function (Blueprint $table) {
            $table->dropColumn([
                'no_telepon',
                'alamat',
                'keterangan',
                'tipe_barang',
                'seri_barang'
            ]);
        });
    }
};