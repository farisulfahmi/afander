<?php

namespace App\Http\Controllers;

use App\Models\PenerimaanStok;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanStokController extends Controller
{
    public function index(Request $request)
    {
        $produkId = $request->input('id_produk');
        $produkList = Produk::when($produkId, function ($q) use ($produkId) {
            $q->where('id_produk', $produkId);
        })->orderBy('id_produk', 'asc')->get();

        $allProduk = Produk::orderBy('id_produk', 'asc')->get();

        return view('verifikasi.index', compact('produkList', 'allProduk', 'produkId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_supplier'  => 'required|string|max:100',
            'tanggal_terima' => 'required|date',
            'items'          => 'required|array',
            'items.*.id_produk' => 'required|exists:produk,id_produk',
            'items.*.qty_nota'  => 'required|integer|min:0',
            'items.*.qty_bagus' => 'required|integer|min:0',
            'items.*.qty_rusak' => 'required|integer|min:0',
        ]);

        $adaInput = false;

        DB::transaction(function () use ($request, &$adaInput) {
            foreach ($request->items as $item) {
                $qtyNota  = (int) $item['qty_nota'];
                $qtyBagus = (int) $item['qty_bagus'];
                $qtyRusak = (int) $item['qty_rusak'];

                if ($qtyNota === 0 && $qtyBagus === 0 && $qtyRusak === 0) {
                    continue;
                }

                $adaInput = true;
                $statusVerifikasi = (($qtyBagus + $qtyRusak) === $qtyNota) ? 'Sesuai' : 'Selisih Penerimaan';

                PenerimaanStok::create([
                    'id_produk'         => $item['id_produk'],
                    'tanggal_terima'    => $request->tanggal_terima,
                    'nama_supplier'     => $request->nama_supplier,
                    'qty_nota'          => $qtyNota,
                    'qty_bagus'         => $qtyBagus,
                    'qty_rusak'         => $qtyRusak,
                    'status_verifikasi' => $statusVerifikasi,
                ]);

                if ($qtyBagus > 0) {
                    Produk::where('id_produk', $item['id_produk'])->increment('stok_tersedia', $qtyBagus);
                }
            }
        });

        if (!$adaInput) {
            return redirect()->back()->with('error', 'Masukkan minimal satu jumlah penerimaan barang.');
        }

        return redirect()->route('verifikasi.index')->with('success', 'Verifikasi barang masuk berhasil disimpan & stok layak jual diperbarui.');
    }
}