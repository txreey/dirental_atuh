<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('harga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kendaraan_id')->constrained('kendaraan')->onDelete('cascade');
            $table->decimal('harga_per_jam', 12, 2)->nullable()->comment('Harga rental per jam');
            $table->decimal('harga_per_hari', 12, 2)->comment('Harga rental per hari');
            $table->decimal('harga_per_minggu', 12, 2)->nullable()->comment('Harga rental per minggu (7 hari)');
            $table->decimal('harga_per_bulan', 12, 2)->nullable()->comment('Harga rental per bulan (30 hari)');
            $table->decimal('denda_per_jam', 10, 2)->default(0)->comment('Denda keterlambatan per jam');
            $table->decimal('deposit', 12, 2)->default(0)->comment('Uang deposit yang diperlukan');
            $table->decimal('biaya_tambahan', 10, 2)->default(0)->comment('Biaya tambahan seperti asuransi, dll');
            $table->text('keterangan')->nullable()->comment('Keterangan tambahan tentang harga');
            $table->boolean('is_active')->default(true)->comment('Status aktif/tidak aktif harga');
            $table->timestamps();

            // Unique constraint untuk mencegah duplikasi harga untuk kendaraan yang sama
            $table->unique(['kendaraan_id', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('harga');
    }
};