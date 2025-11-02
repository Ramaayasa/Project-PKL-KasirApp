<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // 1. KATEGORI BARANG - Skip jika sudah ada
        if (!Schema::hasTable('kategori_barang')) {
            Schema::create('kategori_barang', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 100);
                $table->text('deskripsi')->nullable();
                $table->boolean('status')->default(1);
                $table->timestamps();
            });

            DB::table('kategori_barang')->insert([
                ['nama' => 'Umum', 'deskripsi' => 'Kategori Umum', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['nama' => 'Elektronik', 'deskripsi' => 'Barang Elektronik', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
                ['nama' => 'Komputer', 'deskripsi' => 'Aksesoris Komputer', 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // 2. CUSTOMERS - Skip jika sudah ada
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 200);
                $table->string('telepon', 20)->nullable();
                $table->text('alamat')->nullable();
                $table->string('email', 100)->nullable();
                $table->boolean('status')->default(1);
                $table->timestamps();
            });

            DB::table('customers')->insert([
                'nama' => 'Customer Umum',
                'telepon' => '-',
                'alamat' => '-',
                'email' => null,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // 3. BARANG - Cek struktur tabel
        // Jika tabel 'barangs' (dengan s) ada, rename ke 'barang'
        if (Schema::hasTable('barangs') && !Schema::hasTable('barang')) {
            // Rename tabel dari 'barangs' ke 'barang'
            Schema::rename('barangs', 'barang');
        }

        // Jika tabel barang belum ada sama sekali, buat baru
        if (!Schema::hasTable('barang')) {
            Schema::create('barang', function (Blueprint $table) {
                $table->id();
                $table->string('kode_barang', 50)->unique();
                $table->string('barcode', 100)->nullable();
                $table->string('nama', 200);
                $table->unsignedBigInteger('kategori_id')->nullable();
                $table->decimal('harga', 15, 2)->default(0);
                $table->decimal('harga_modal', 15, 2)->default(0);
                $table->integer('stok')->default(0);
                $table->string('satuan', 20)->default('pcs');
                $table->text('deskripsi')->nullable();
                $table->string('gambar')->nullable();
                $table->boolean('status')->default(1);
                $table->timestamps();

                $table->index(['nama', 'kode_barang']);
            });
        }

        // Pastikan kolom yang dibutuhkan ada
        if (Schema::hasTable('barang')) {
            Schema::table('barang', function (Blueprint $table) {
                // Tambah kolom jika belum ada
                if (!Schema::hasColumn('barang', 'nama')) {
                    $table->string('nama', 200)->after('barcode');
                }
                if (!Schema::hasColumn('barang', 'harga')) {
                    $table->decimal('harga', 15, 2)->default(0)->after('kategori_id');
                }
                if (!Schema::hasColumn('barang', 'harga_modal')) {
                    $table->decimal('harga_modal', 15, 2)->default(0)->after('harga');
                }
                if (!Schema::hasColumn('barang', 'deskripsi')) {
                    $table->text('deskripsi')->nullable()->after('satuan');
                }
                if (!Schema::hasColumn('barang', 'gambar')) {
                    $table->string('gambar')->nullable()->after('deskripsi');
                }
            });
        }

        // 4. TRANSAKSI - Skip jika sudah ada
        if (!Schema::hasTable('transaksi')) {
            Schema::create('transaksi', function (Blueprint $table) {
                $table->id();
                $table->string('kode_transaksi', 50)->unique();
                $table->timestamp('tanggal');
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->decimal('total', 15, 2)->default(0);
                $table->decimal('bayar', 15, 2)->default(0);
                $table->decimal('kembalian', 15, 2)->default(0);
                $table->decimal('ongkir', 15, 2)->default(0);
                $table->string('kurir', 100)->nullable();
                $table->string('tipe_pembayaran', 50)->nullable();
                $table->string('status', 20)->default('selesai');
                $table->text('catatan')->nullable();
                $table->timestamps();

                $table->index(['kode_transaksi', 'tanggal', 'status']);
            });
        }

        // Pastikan kolom yang dibutuhkan ada di transaksi
        if (Schema::hasTable('transaksi')) {
            Schema::table('transaksi', function (Blueprint $table) {
                if (!Schema::hasColumn('transaksi', 'ongkir')) {
                    $table->decimal('ongkir', 15, 2)->default(0)->after('kembalian');
                }
                if (!Schema::hasColumn('transaksi', 'kurir')) {
                    $table->string('kurir', 100)->nullable()->after('ongkir');
                }
                if (!Schema::hasColumn('transaksi', 'tipe_pembayaran')) {
                    $table->string('tipe_pembayaran', 50)->nullable()->after('kurir');
                }
                if (!Schema::hasColumn('transaksi', 'catatan')) {
                    $table->text('catatan')->nullable()->after('status');
                }
            });
        }

        // 5. TRANSAKSI DETAIL - Cek nama tabel (bisa 'transaksi_detail' atau 'transaksi_details')
        if (Schema::hasTable('transaksi_details') && !Schema::hasTable('transaksi_detail')) {
            // Rename dari 'transaksi_details' ke 'transaksi_detail'
            Schema::rename('transaksi_details', 'transaksi_detail');
        }

        if (!Schema::hasTable('transaksi_detail')) {
            Schema::create('transaksi_detail', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('transaksi_id');
                $table->unsignedBigInteger('barang_id');
                $table->integer('jumlah');
                $table->decimal('harga', 15, 2);
                $table->decimal('subtotal', 15, 2);
                $table->timestamps();

                $table->index(['transaksi_id', 'barang_id']);
            });
        }
    }

    public function down()
    {
        // Tidak drop tabel untuk keamanan
        // Jika ingin rollback manual, uncomment baris di bawah:
        // Schema::dropIfExists('transaksi_detail');
        // Schema::dropIfExists('transaksi');
        // Schema::dropIfExists('barang');
        // Schema::dropIfExists('customers');
        // Schema::dropIfExists('kategori_barang');
    }
};