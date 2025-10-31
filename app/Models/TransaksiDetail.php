<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $table = 'transaksi_details';

    protected $fillable = [
        'transaksi_id',
        'barang_id',
        'jumlah',
        'harga',
        'subtotal',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relasi ke transaksi header
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
}