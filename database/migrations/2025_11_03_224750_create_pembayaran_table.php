<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained('rental')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('kode_pembayaran')->unique();
            $table->decimal('jumlah', 12, 2);
            $table->enum('metode_pembayaran', ['transfer_bank', 'virtual_account', 'e_wallet', 'cash']);
            $table->enum('status', ['pending', 'paid', 'failed', 'expired'])->default('pending');
            $table->string('bukti_pembayaran')->nullable();
            $table->datetime('batas_pembayaran');
            $table->datetime('tanggal_bayar')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pembayaran');
    }
};