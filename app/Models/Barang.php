<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'barcode',
        'nama',
        'kategori_id',
        'harga',
        'harga_modal',
        'stok',
        'satuan',
        'deskripsi',
        'gambar',
        'status'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'harga_modal' => 'decimal:2',
        'stok' => 'integer',
        'status' => 'boolean',
        'kategori_id' => 'integer' // Cast ke integer, nullable
    ];

    /**
     * Relasi ke kategori - Optional (bisa null)
     */
    public function kategori()
    {
        // Gunakan model Kategori yang sudah ada
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    /**
     * Relasi ke detail transaksi
     */
    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    /**
     * Scope untuk barang aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope untuk barang dengan stok tersedia
     */
    public function scopeTersedia($query)
    {
        return $query->where('stok', '>', 0);
    }

    /**
     * Accessor untuk format harga
     */
    public function getHargaFormatAttribute()
    {
        return 'Rp. ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Accessor untuk status stok
     */
    public function getStatusStokAttribute()
    {
        if ($this->stok <= 0) {
            return 'habis';
        } elseif ($this->stok < 5) {
            return 'kritis';
        } elseif ($this->stok < 10) {
            return 'rendah';
        }
        return 'aman';
    }
}