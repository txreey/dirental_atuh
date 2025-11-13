<?php
// app/Models/Denda.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denda extends Model
{
    use HasFactory;

    protected $table = 'denda'; // Tentukan nama table

    protected $fillable = [
        'rental_id',
        'user_id',
        'hari_keterlambatan',
        'jumlah',
        'status',
        'keterangan',
        'tanggal_dibuat',
        'tanggal_dibayar'
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