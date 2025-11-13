<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harga extends Model
{
    use HasFactory;

    protected $table = 'harga';

    protected $fillable = [
        'kendaraan_id',
        'harga_per_jam',
        'harga_per_hari',
        'harga_per_minggu',
        'harga_per_bulan',
        'denda_per_jam',
        'deposit',
        'biaya_tambahan',
        'keterangan',
        'is_active'
    ];

    protected $casts = [
        'harga_per_jam' => 'decimal:2',
        'harga_per_hari' => 'decimal:2',
        'harga_per_minggu' => 'decimal:2',
        'harga_per_bulan' => 'decimal:2',
        'denda_per_jam' => 'decimal:2',
        'deposit' => 'decimal:2',
        'biaya_tambahan' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Relationship dengan kendaraan
     */
    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    /**
     * Hitung harga berdasarkan durasi
     */
    public function hitungHarga($durasi, $tipe = 'hari')
    {
        $hargaPerHari = $this->harga_per_hari;

        switch ($tipe) {
            case 'jam':
                return $durasi * $this->harga_per_jam;
            case 'hari':
                return $durasi * $hargaPerHari;
            case 'minggu':
                $minggu = ceil($durasi / 7);
                return $minggu * $this->harga_per_minggu;
            case 'bulan':
                $bulan = ceil($durasi / 30);
                return $bulan * $this->harga_per_bulan;
            default:
                return $durasi * $hargaPerHari;
        }
    }

    /**
     * Hitung total dengan diskon untuk durasi panjang
     */
    public function hitungHargaDenganDiskon($durasiHari)
    {
        $hargaNormal = $durasiHari * $this->harga_per_hari;

        // Diskon untuk rental mingguan
        if ($durasiHari >= 7 && $this->harga_per_minggu) {
            $minggu = floor($durasiHari / 7);
            $sisaHari = $durasiHari % 7;
            $hargaMingguan = $minggu * $this->harga_per_minggu;
            $hargaSisaHari = $sisaHari * $this->harga_per_hari;
            return $hargaMingguan + $hargaSisaHari;
        }

        // Diskon untuk rental bulanan
        if ($durasiHari >= 30 && $this->harga_per_bulan) {
            $bulan = floor($durasiHari / 30);
            $sisaHari = $durasiHari % 30;
            $hargaBulanan = $bulan * $this->harga_per_bulan;
            $hargaSisaHari = $sisaHari * $this->harga_per_hari;
            return $hargaBulanan + $hargaSisaHari;
        }

        return $hargaNormal;
    }

    /**
     * Hitung total denda berdasarkan jam keterlambatan
     */
    public function hitungDenda($jamKeterlambatan)
    {
        return $jamKeterlambatan * $this->denda_per_jam;
    }

    /**
     * Scope untuk harga aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Format harga untuk display
     */
    public function formatHarga($harga)
    {
        return 'Rp ' . number_format($harga, 0, ',', '.');
    }
}