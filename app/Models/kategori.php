<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';

    protected $fillable = [
        'nama',
        'tipe',
        'kode',
        'deskripsi',
    ];

    /**
     * Auto-generate kode kategori saat create
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kategori) {
            if (empty($kategori->kode)) {
                $kategori->kode = self::generateKode($kategori->tipe);
            }
        });
    }

    /**
     * Generate kode kategori otomatis
     */
    public static function generateKode($tipe)
    {
        $prefix = ($tipe === 'barang') ? 'KTG-BRG-' : 'KTG-SRV-';

        $lastKategori = self::where('tipe', $tipe)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastKategori && preg_match('/\d{3}$/', $lastKategori->kode, $matches)) {
            $lastNumber = intval($matches[0]);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Scope untuk kategori barang
     */
    public function scopeBarang($query)
    {
        return $query->where('tipe', 'barang');
    }

    /**
     * Scope untuk kategori servis
     */
    public function scopeServis($query)
    {
        return $query->where('tipe', 'servis');
    }

    /**
     * Relasi ke barang
     */
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }
}