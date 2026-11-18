<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->increments('id_transaksi');
            $table->string('kode_struk', 30)->unique();
            $table->dateTime('waktu_transaksi');
            $table->string('nama_kasir', 50);
            $table->string('metode_bayar', 20);
            $table->integer('total_harga');
            $table->integer('nominal_bayar');
            $table->integer('kembalian');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};