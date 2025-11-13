<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_kendaraan_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->string('nama');
            $table->string('merk');
            $table->string('plat_nomor')->unique(); // PASTIKAN: plat_nomor
            $table->string('warna');
            $table->integer('tahun');
            $table->integer('stok')->default(1);
            $table->string('status')->default('tersedia');
            $table->string('gambar')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraan');
    }
};