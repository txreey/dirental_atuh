<?php
// app/Models/Kendaraan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraan';

    protected $fillable = [
        'kategori_id',
        'nama',
        'merk',
        'plat_nomor', // PASTIKAN: plat_nomor bukan nomor_plat
        'warna',
        'tahun',
        'stok',
        'status',
        'gambar'
    ];

    // Relationship ke kategori (jenis)
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relationship ke harga
    public function harga()
    {
        return $this->hasOne(Harga::class, 'kendaraan_id');
    }

    // Relationship ke rental
    public function rental()
    {
        return $this->hasMany(Rental::class, 'kendaraan_id');
    }

    // Accessor untuk status stok
    public function getStatusStokAttribute()
    {
        $tersewa = $this->rental()->whereIn('status', ['dipinjam', 'pending'])->count();
        $tersisa = $this->stok - $tersewa;
        
        return $tersisa > 0 ? "Tersisa {$tersisa}" : 'Habis';
    }

    // Accessor untuk kompatibilitas jika ada yang masih pakai nomor_plat
    public function getNomorPlatAttribute()
    {
        return $this->plat_nomor;
    }
}