<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input('keyword');
        $kategoriId = $request->input('kategori');

        $query = Produk::with('kategori')
            ->where('status_aktif', true)
            ->where('stok_tersedia', '>', 0);

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_produk', 'LIKE', "%{$keyword}%")
                  ->orWhere('kode_produk', 'LIKE', "%{$keyword}%");
            });
        }

        if (!empty($kategoriId)) {
            $query->where('id_kategori', $kategoriId);
        }

        $produkList = $query->get();
        $kategoriList = Kategori::take(3)->get();
        $cart = session()->get('cart', []);

        $totalHarga = 0;
        $totalItem = 0;
        foreach ($cart as $item) {
            $totalHarga += $item['subtotal'];
            $totalItem += $item['qty_beli'];
        }

        $lastTransaksi = session()->get('last_transaksi');

        return view('kasir.index', compact(
            'produkList',
            'kategoriList',
            'cart',
            'totalHarga',
            'totalItem',
            'keyword',
            'kategoriId',
            'lastTransaksi'
        ));
    }

    public function addToCart(Request $request, $id)
    {
        $produk = Produk::where('id_produk', $id)
            ->where('status_aktif', true)
            ->where('stok_tersedia', '>', 0)
            ->first();

        if (!$produk) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan atau nonaktif');
        }

        $cart = session()->get('cart', []);
        $qtySekarang = isset($cart[$id]) ? $cart[$id]['qty_beli'] : 0;
        $qtyBaru = $qtySekarang + 1;

        if ($qtyBaru > $produk->stok_tersedia) {
            return redirect()->back()->with('error', 'Jumlah beli melebihi stok tersedia');
        }

        $cart[$id] = [
            'id_produk'     => $produk->id_produk,
            'kode_produk'   => $produk->kode_produk,
            'nama_produk'   => $produk->nama_produk,
            'varian_ukuran' => $produk->varian_ukuran,
            'harga_jual'    => $produk->harga_jual,
            'stok_maks'     => $produk->stok_tersedia,
            'qty_beli'      => $qtyBaru,
            'subtotal'      => $qtyBaru * $produk->harga_jual,
        ];

        session()->put('cart', $cart);

        return redirect()->route('kasir.index')->with('cart_notice', 'Item masuk ke keranjang.');
    }

    public function updateCart(Request $request, $id)
    {
        $qtyInput = (int) $request->input('qty_beli');
        $produk = Produk::find($id);
        $cart = session()->get('cart', []);

        if (!$produk || !isset($cart[$id])) {
            return redirect()->back()->with('error', 'Produk tidak ditemukan');
        }

        if ($qtyInput <= 0) {
            return redirect()->back()->with('error', 'Jumlah beli harus lebih dari 0');
        }

        if ($qtyInput > $produk->stok_tersedia) {
            return redirect()->back()->with('error', 'Jumlah beli melebihi stok tersedia');
        }

        $cart[$id]['qty_beli'] = $qtyInput;
        $cart[$id]['subtotal'] = $qtyInput * $cart[$id]['harga_jual'];
        session()->put('cart', $cart);

        return redirect()->route('kasir.index');
    }

    public function removeFromCart($id)
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return redirect()->route('kasir.index');
    }

    public function clearCart()
    {
        session()->forget('cart');
        return redirect()->route('kasir.index');
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Keranjang belanja masih kosong');
        }

        $request->validate([
            'metode_bayar'        => 'required|in:Tunai,Transfer,QR Code',
            'nominal_bayar'       => 'nullable|integer|min:0',
            'konfirmasi_nontunai' => 'nullable',
        ]);

        $totalHarga = array_sum(array_column($cart, 'subtotal'));
        $metode = $request->input('metode_bayar');
        $nominalBayar = (int) $request->input('nominal_bayar');
        $kembalian = 0;

        if ($metode === 'Tunai') {
            if ($nominalBayar < $totalHarga) {
                return redirect()->back()->with('error', 'Nominal uang bayar kurang');
            }
            $kembalian = $nominalBayar - $totalHarga;
        } else {
            if (!$request->has('konfirmasi_nontunai')) {
                return redirect()->back()->with('error', 'Konfirmasi pembayaran terlebih dahulu');
            }
            $nominalBayar = $totalHarga;
            $kembalian = 0;
        }

        $transaksi = null;

        DB::transaction(function () use ($cart, $totalHarga, $metode, $nominalBayar, $kembalian, &$transaksi) {
            $kodeStruk = 'STRK-' . date('YmdHis');

            $transaksi = Transaksi::create([
                'kode_struk'      => $kodeStruk,
                'waktu_transaksi' => now(),
                'nama_kasir'      => Auth::user()->name,
                'metode_bayar'    => $metode,
                'total_harga'     => $totalHarga,
                'nominal_bayar'   => $nominalBayar,
                'kembalian'       => $kembalian,
            ]);

            foreach ($cart as $item) {
                DetailTransaksi::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_produk'    => $item['id_produk'],
                    'qty_beli'     => $item['qty_beli'],
                    'harga_satuan' => $item['harga_jual'],
                    'subtotal'     => $item['subtotal'],
                ]);

                Produk::where('id_produk', $item['id_produk'])->decrement('stok_tersedia', $item['qty_beli']);
            }
        });

        session()->forget('cart');

        $transaksiWithDetails = Transaksi::with('details.produk')->find($transaksi->id_transaksi);

        return redirect()->route('kasir.index')
            ->with('transaksi_sukses', 'Transaksi berhasil diselesaikan!')
            ->with('last_transaksi', $transaksiWithDetails);
    }
}