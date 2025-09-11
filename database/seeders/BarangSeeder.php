<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('barang')->insert([
            [
                'kode_barang' => 'BRG001',
                'nama_barang' => 'Keyboard Mechanical',
                'kategori' => 'Elektronik',
                'harga_beli' => 250000,
                'harga_jual' => 350000,
                'stok' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_barang' => 'BRG002',
                'nama_barang' => 'Mouse Wireless',
                'kategori' => 'Elektronik',
                'harga_beli' => 100000,
                'harga_jual' => 150000,
                'stok' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
