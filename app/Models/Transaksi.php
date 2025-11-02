<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'kode_transaksi',
        'tanggal',
        'customer_id',
        'user_id',
        'total',
        'bayar',
        'kembalian',
        'ongkir',
        'kurir',
        'tipe_pembayaran',
        'status',
        'catatan'
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'total' => 'decimal:2',
        'bayar' => 'decimal:2',
        'kembalian' => 'decimal:2',
        'ongkir' => 'decimal:2'
    ];

    /**
     * Relasi ke user (kasir)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke customer
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Relasi ke detail transaksi
     */
    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    /**
     * Scope untuk transaksi hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal', today());
    }

    /**
     * Scope untuk transaksi bulan ini
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year);
    }

    /**
     * Accessor untuk format tanggal
     */
    public function getTanggalFormatAttribute()
    {
        return $this->tanggal->format('d/m/Y H:i');
    }

    /**
     * Accessor untuk format total
     */
    public function getTotalFormatAttribute()
    {
        return 'Rp. ' . number_format($this->total, 0, ',', '.');
    }

    /**
     * Accessor untuk total item
     */
    public function getTotalItemAttribute()
    {
        return $this->details->sum('jumlah');
    }
}