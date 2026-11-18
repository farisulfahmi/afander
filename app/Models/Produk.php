<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Kategori;
class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';

    protected $fillable = [
        'id_kategori',
        'kode_produk',
        'nama_produk',
        'varian_ukuran',
        'harga_modal',
        'harga_jual',
        'stok_tersedia',
        'status_aktif',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_produk', 'id_produk');
    }

    public function penerimaanStok()
    {
        return $this->hasMany(PenerimaanStok::class, 'id_produk', 'id_produk');
    }
}