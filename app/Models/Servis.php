<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{
    protected $table = 'servis';

    protected $fillable = [
        'kode_servis',
        'nama_pelanggan',
        'no_telepon',
        'alamat',
        'kelengkapan',
        'password',
        'warna_barang',
        'tipe_barang',
        'seri_barang',
        'kontak',
        'status',
        'keluhan',
    ];

    // Auto-generate kode servis saat create
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($servis) {
            if (empty($servis->kode_servis)) {
                $servis->kode_servis = self::generateKodeServis();
            }
        });
    }

    public static function generateKodeServis()
    {
        $prefix = 'SRV';
        $date = date('Ymd'); // Format: 20251031

        // Cari kode terakhir hari ini
        $lastServis = self::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        if ($lastServis) {
            // Ambil 3 digit terakhir dan tambah 1
            $lastNumber = intval(substr($lastServis->kode_servis, -3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        // Format: SRV20251031001, SRV20251031002, dst
        return $prefix . $date . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }
}