<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kasir extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'kasirs';

    // Kolom yang bisa diisi
    protected $fillable = [
        'barang_id',
        'jumlah',
        'total',
    ];

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function kasir()
    {
        return $this->hasMany(Kasir::class);
    }

}
