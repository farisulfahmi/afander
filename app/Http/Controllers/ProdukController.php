<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{

public function index(Request $request)
{
    // --- PENGAMAN TAMBAHAN ROLE PEMILIK ---
    if (auth()->check() && auth()->user()->role !== 'Pemilik') {
        return redirect()->route('kasir.index')->with('error', 'Akses ditolak! Halaman ini khusus untuk Pemilik Toko.');
    }
    // ----------------------------------------

    // Kode asli kamu dilanjutkan di bawah sini:
    $keyword = $request->get('keyword');
    $kategoriId = $request->get('kategori');
    $status = $request->get('status');

    $kategoriList = Kategori::all();
    $query = Produk::with('kategori');

    if (!empty($keyword)) {
        $query->where(function($q) use ($keyword) {
            $q->where('nama_produk', 'like', "%{$keyword}%")
              ->orWhere('kode_produk', 'like', "%{$keyword}%");
        });
    }

    if (!empty($kategoriId)) {
        $query->where('id_kategori', $kategoriId);
    }

    if ($status !== null && $status !== '') {
        $query->where('status_aktif', $status);
    }

    $produkList = $query->get();

    return view('produk.index', compact('produkList', 'kategoriList', 'keyword', 'kategoriId', 'status'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kategori'   => 'required|exists:kategori,id_kategori',
            'kode_produk'   => 'required|string|max:20|unique:produk,kode_produk',
            'nama_produk'   => 'required|string|max:100',
            'varian_ukuran' => 'required|string|max:20',
            'harga_modal'   => 'required|integer|min:0',
            'harga_jual'    => 'required|integer|min:0',
            'stok_tersedia' => 'required|integer|min:0',
        ], [
            'kode_produk.unique'   => 'Kode produk sudah digunakan',
            'kode_produk.required' => 'Kolom wajib diisi',
            'nama_produk.required' => 'Kolom wajib diisi',
            'id_kategori.required' => 'Kolom wajib diisi',
            'harga_modal.integer'  => 'Harga harus berupa bilangan bulat >= 0',
            'harga_modal.min'      => 'Harga harus berupa bilangan bulat >= 0',
            'harga_jual.integer'   => 'Harga harus berupa bilangan bulat >= 0',
            'harga_jual.min'       => 'Harga harus berupa bilangan bulat >= 0',
            'stok_tersedia.min'    => 'Stok harus berupa bilangan bulat >= 0',
        ]);

        $validated['status_aktif'] = true;

        Produk::create($validated);

        return redirect()->route('produk.index')->with('success', 'Data produk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $validated = $request->validate([
            'id_kategori'   => 'required|exists:kategori,id_kategori',
            'kode_produk'   => 'required|string|max:20|unique:produk,kode_produk,' . $id . ',id_produk',
            'nama_produk'   => 'required|string|max:100',
            'varian_ukuran' => 'required|string|max:20',
            'harga_modal'   => 'required|integer|min:0',
            'harga_jual'    => 'required|integer|min:0',
            'stok_tersedia' => 'required|integer|min:0',
        ], [
            'kode_produk.unique' => 'Kode produk sudah digunakan',
            'harga_modal.min'    => 'Harga harus berupa bilangan bulat >= 0',
            'harga_jual.min'     => 'Harga harus berupa bilangan bulat >= 0',
        ]);

        $produk->update($validated);

        return redirect()->route('produk.index')->with('success', 'Data produk berhasil diperbarui.');
    }

    public function toggleStatus($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->status_aktif = !$produk->status_aktif;
        $produk->save();

        return redirect()->route('produk.index')->with('success', 'Status aktif produk berhasil diubah.');
    }

    public function destroy($id)
    {
        $produk = Produk::withCount('detailTransaksi')->findOrFail($id);

        if ($produk->detail_transaksi_count > 0) {
            $produk->update(['status_aktif' => false]);
            return redirect()->route('produk.index')->with('error', 'Produk sudah memiliki riwayat transaksi sehingga tidak dapat dihapus permanen. Status otomatis diubah menjadi Nonaktif.');
        }

        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus permanen.');
    }
}