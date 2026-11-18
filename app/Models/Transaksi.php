<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'kode_struk',
        'waktu_transaksi',
        'nama_kasir',
        'metode_bayar',
        'total_harga',
        'nominal_bayar',
        'kembalian',
    ];

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }
}