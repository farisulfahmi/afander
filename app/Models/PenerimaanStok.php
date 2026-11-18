<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Produk;
class PenerimaanStok extends Model
{
    protected $table = 'penerimaan_stok';
    protected $primaryKey = 'id_terima';

    protected $fillable = [
        'id_produk',
        'tanggal_terima',
        'nama_supplier',
        'qty_nota',
        'qty_bagus',
        'qty_rusak',
        'status_verifikasi',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
    }
}