<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('servis', function (Blueprint $table) {
            if (!Schema::hasColumn('servis', 'no_hp')) {
                $table->string('no_hp', 20)->nullable()->after('nama_pelanggan');
            }
            if (!Schema::hasColumn('servis', 'alamat')) {
                $table->text('alamat')->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('servis', 'tipe_barang')) {
                $table->string('tipe_barang', 100)->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('servis', 'seri_barang')) {
                $table->string('seri_barang', 150)->nullable()->after('tipe_barang');
            }
            if (!Schema::hasColumn('servis', 'kelengkapan')) {
                $table->text('kelengkapan')->nullable()->after('keluhan');
            }
            if (!Schema::hasColumn('servis', 'password')) {
                $table->string('password', 100)->nullable()->after('kelengkapan');
            }
            if (!Schema::hasColumn('servis', 'warna_barang')) {
                $table->string('warna_barang', 100)->nullable()->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('servis', function (Blueprint $table) {
            $table->dropColumn(['no_hp', 'alamat', 'tipe_barang', 'seri_barang', 'kelengkapan', 'password', 'warna_barang']);
        });
    }
};
