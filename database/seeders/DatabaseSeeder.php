<?php

namespace Database\Seeders;

use App\Models\Kategori;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Bebbi Aulia Maharani',
            'username' => 'pemilik',
            'password' => Hash::make('pemilik123'),
            'role' => 'Pemilik',
        ]);

        User::create([
            'name' => 'Farisul Fahmi',
            'username' => 'kasir',
            'password' => Hash::make('kasir123'),
            'role' => 'Kasir',
        ]);

        $kemeja = Kategori::create(['nama_kategori' => 'Kemeja']);
        $celana = Kategori::create(['nama_kategori' => 'Celana']);
        $gamis  = Kategori::create(['nama_kategori' => 'Gamis']);
        $kaos   = Kategori::create(['nama_kategori' => 'Kaos']);
        $jaket  = Kategori::create(['nama_kategori' => 'Jaket']);
        $rok    = Kategori::create(['nama_kategori' => 'Rok']);

        Produk::create([
            'id_kategori' => $kemeja->id_kategori,
            'kode_produk' => 'PRD001',
            'nama_produk' => 'Kemeja Flanel',
            'varian_ukuran' => 'L',
            'harga_modal' => 60000,
            'harga_jual' => 120000,
            'stok_tersedia' => 25,
            'status_aktif' => true,
        ]);

        Produk::create([
            'id_kategori' => $celana->id_kategori,
            'kode_produk' => 'PRD002',
            'nama_produk' => 'Celana Jeans',
            'varian_ukuran' => 'XL',
            'harga_modal' => 80000,
            'harga_jual' => 150000,
            'stok_tersedia' => 18,
            'status_aktif' => true,
        ]);

        Produk::create([
            'id_kategori' => $gamis->id_kategori,
            'kode_produk' => 'PRD003',
            'nama_produk' => 'Gamis Motif',
            'varian_ukuran' => 'M',
            'harga_modal' => 100000,
            'harga_jual' => 200000,
            'stok_tersedia' => 12,
            'status_aktif' => true,
        ]);

        Produk::create([
            'id_kategori' => $kaos->id_kategori,
            'kode_produk' => 'PRD004',
            'nama_produk' => 'Kaos Polos',
            'varian_ukuran' => 'L',
            'harga_modal' => 40000,
            'harga_jual' => 80000,
            'stok_tersedia' => 30,
            'status_aktif' => true,
        ]);

        Produk::create([
            'id_kategori' => $jaket->id_kategori,
            'kode_produk' => 'PRD005',
            'nama_produk' => 'Jaket Hoodie',
            'varian_ukuran' => 'XL',
            'harga_modal' => 120000,
            'harga_jual' => 250000,
            'stok_tersedia' => 10,
            'status_aktif' => true,
        ]);

        Produk::create([
            'id_kategori' => $rok->id_kategori,
            'kode_produk' => 'PRD006',
            'nama_produk' => 'Rok Panjang',
            'varian_ukuran' => 'All Size',
            'harga_modal' => 70000,
            'harga_jual' => 130000,
            'stok_tersedia' => 15,
            'status_aktif' => false,
        ]);
    }
}