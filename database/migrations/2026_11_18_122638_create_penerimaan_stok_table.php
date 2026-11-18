<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penerimaan_stok', function (Blueprint $table) {
            $table->increments('id_terima');
            $table->unsignedInteger('id_produk');
            $table->date('tanggal_terima');
            $table->string('nama_supplier', 100);
            $table->integer('qty_nota');
            $table->integer('qty_bagus');
            $table->integer('qty_rusak');
            $table->string('status_verifikasi', 30);
            $table->timestamps();

            $table->foreign('id_produk')->references('id_produk')->on('produk')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penerimaan_stok');
    }
};