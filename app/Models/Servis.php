<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servis extends Model
{
    use HasFactory;

    protected $table = 'servis';

    protected $fillable = [
        'nama_pelanggan',
        'no_hp',
        'barang_servis',
        'keluhan',
        'status',
    ];
}
