<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('servis', function (Blueprint $table) {
            // Hapus kolom yang tidak dipakai
            if (Schema::hasColumn('servis', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
            if (Schema::hasColumn('servis', 'biaya')) {
                $table->dropColumn('biaya');
            }

            // Rename keterangan jadi kelengkapan
            if (Schema::hasColumn('servis', 'keterangan')) {
                $table->renameColumn('keterangan', 'kelengkapan');
            } else {
                $table->text('kelengkapan')->nullable()->after('keluhan');
            }

            // Tambah kolom baru
            if (!Schema::hasColumn('servis', 'password')) {
                $table->string('password', 100)->nullable()->after('kelengkapan');
            }
            if (!Schema::hasColumn('servis', 'warna_barang')) {
                $table->string('warna_barang', 50)->nullable()->after('password');
            }
        });
    }

    public function down(): void
    {
        Schema::table('servis', function (Blueprint $table) {
            // Kembalikan seperti semula
            $table->dropColumn(['kelengkapan', 'password', 'warna_barang']);
            $table->text('keterangan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->decimal('biaya', 12, 2)->nullable();
        });
    }
};