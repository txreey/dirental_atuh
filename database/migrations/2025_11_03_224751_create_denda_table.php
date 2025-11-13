<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('denda', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained('rental')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('hari_keterlambatan');
            $table->decimal('jumlah', 12, 2);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_dibuat');
            $table->date('tanggal_dibayar')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('denda');
    }
};