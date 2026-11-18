<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->increments('id_detail');
            $table->unsignedInteger('id_transaksi');
            $table->unsignedInteger('id_produk');
            $table->integer('qty_beli');
            $table->integer('harga_satuan');
            $table->integer('subtotal');
            $table->timestamps();

            $table->foreign('id_transaksi')->references('id_transaksi')->on('transaksi')->onDelete('cascade');
            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};