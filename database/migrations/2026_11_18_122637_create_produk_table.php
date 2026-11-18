<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->increments('id_produk');
            $table->unsignedInteger('id_kategori');
            $table->string('kode_produk', 20)->unique();
            $table->string('nama_produk', 100);
            $table->string('varian_ukuran', 20);
            $table->integer('harga_modal');
            $table->integer('harga_jual');
            $table->integer('stok_tersedia');
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};