<?php
// app/Models/Pembayaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran'; // Tentukan nama table

    protected $fillable = [
        'rental_id',
        'user_id',
        'kode_pembayaran',
        'jumlah',
        'metode_pembayaran',
        'status',
        'bukti_pembayaran',
        'batas_pembayaran',
        'tanggal_bayar',
        'keterangan'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class, 'rental_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}