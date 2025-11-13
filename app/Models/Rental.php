<?php
// app/Models/Rental.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $table = 'rental'; // Tentukan nama table

    protected $fillable = [
        'user_id',
        'kendaraan_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_hari',
        'total_harga',
        'status',
        'catatan'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'rental_id');
    }
}