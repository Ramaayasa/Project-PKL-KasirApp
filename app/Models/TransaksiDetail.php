<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $table = 'transaksi_detail';

    protected $fillable = [
        'transaksi_id',
        'barang_id',
        'jumlah',
        'harga',
        'subtotal'
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    /**
     * Relasi ke transaksi
     */
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    /**
     * Relasi ke barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    /**
     * Accessor untuk format harga
     */
    public function getHargaFormatAttribute()
    {
        return 'Rp. ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Accessor untuk format subtotal
     */
    public function getSubtotalFormatAttribute()
    {
        return 'Rp. ' . number_format($this->subtotal, 0, ',', '.');
    }
}