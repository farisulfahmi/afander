@extends('layouts.app')

@push('styles')
<style>
    @media print {
        body * { visibility: hidden; }
        #area-struk, #area-struk * { visibility: visible; }
        #area-struk { position: absolute; left: 0; top: 0; width: 100%; }
    }
</style>
@endpush

@section('content')
<div class="space-y-4">
    @if(session('error'))
        <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-2.5 rounded-xl text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
        <form action="{{ route('kasir.index') }}" method="GET" class="lg:col-span-8">
            <div class="relative">
                <span class="absolute inset-y-0 left-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="Cari produk... (Nama atau Kode)"
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
            </div>
        </form>

        <div class="lg:col-span-4 grid grid-cols-3 gap-2">
            @foreach($kategoriList as $kat)
                <a href="{{ route('kasir.index', ['kategori' => ($kategoriId == $kat->id_kategori ? '' : $kat->id_kategori)]) }}"
                   class="py-2.5 px-3 rounded-xl text-sm font-semibold text-center border transition flex items-center justify-center space-x-1.5 {{ ($kategoriId ?? '') == $kat->id_kategori ? 'bg-[#14171F] text-white border-[#14171F]' : 'bg-white text-gray-800 border-gray-300 hover:bg-gray-50' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    <span>{{ $kat->nama_kategori }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        <div class="lg:col-span-8 flex flex-col justify-between space-y-3">
            <div class="bg-white rounded-xl border border-gray-300 overflow-hidden flex-grow min-h-[430px] flex flex-col">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-[#E5E7EB] text-gray-800 font-semibold">
                        <tr>
                            <th class="py-3 px-3 text-center border-r border-gray-300 w-12">No</th>
                            <th class="py-3 px-4 text-left border-r border-gray-300">Nama Barang</th>
                            <th class="py-3 px-3 text-center border-r border-gray-300 w-28">Ukuran</th>
                            <th class="py-3 px-3 text-center border-r border-gray-300 w-32">Qty</th>
                            <th class="py-3 px-4 text-center border-r border-gray-300">Harga Satuan</th>
                            <th class="py-3 px-4 text-center border-r border-gray-300">Subtotal</th>
                            <th class="py-3 px-3 text-center w-12"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php $no = 1; @endphp
                        @forelse($cart as $id => $item)
                            <tr>
                                <td class="py-3 px-3 text-center border-r border-gray-200">{{ $no++ }}</td>
                                <td class="py-3 px-4 font-bold text-gray-900 border-r border-gray-200">{{ $item['nama_produk'] }}</td>
                                <td class="py-3 px-3 text-center border-r border-gray-200">
                                    <span class="inline-flex items-center space-x-1 border border-gray-300 rounded-md px-3 py-1 text-xs font-medium bg-white">
                                        <span>{{ $item['varian_ukuran'] }}</span>
                                        <svg class="w-3 h-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-center border-r border-gray-200">
                                    <div class="inline-flex items-center space-x-1.5">
                                        <form action="{{ route('kasir.update', $id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="qty_beli" value="{{ $item['qty_beli'] - 1 }}">
                                            <button type="submit" class="w-6 h-6 border border-gray-300 rounded text-xs font-bold hover:bg-gray-100">-</button>
                                        </form>

                                        <form action="{{ route('kasir.update', $id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="qty_beli" value="{{ $item['qty_beli'] }}" min="1" max="{{ $item['stok_maks'] }}" onchange="this.form.submit()"
                                                class="w-10 text-center text-xs font-bold focus:outline-none">
                                        </form>

                                        <form action="{{ route('kasir.update', $id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="qty_beli" value="{{ $item['qty_beli'] + 1 }}">
                                            <button type="submit" class="w-6 h-6 border border-gray-300 rounded text-xs font-bold hover:bg-gray-100">+</button>
                                        </form>
                                    </div>
                                </td>
                                <td class="py-3 px-4 text-center border-r border-gray-200">Rp{{ number_format($item['harga_jual'], 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-center font-bold border-r border-gray-200">Rp{{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                                <td class="py-3 px-3 text-center">
                                    <form action="{{ route('kasir.remove', $id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-600">
                                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center text-gray-400">
                                    {{ !empty($keyword) ? 'Produk tidak ditemukan' : 'Keranjang masih kosong. Pilih produk pada daftar di bawah.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <form action="{{ route('kasir.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-white hover:bg-gray-50 border border-gray-300 rounded-xl py-2.5 text-sm font-semibold text-gray-800 flex items-center justify-center space-x-1.5">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span>Hapus Item</span>
                    </button>
                </form>
                <form action="{{ route('kasir.clear') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-white hover:bg-gray-50 border border-gray-300 rounded-xl py-2.5 text-sm font-semibold text-gray-800 flex items-center justify-center space-x-1.5">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        <span>Kosongkan Keranjang</span>
                    </button>
                </form>
                <button type="button" onclick="document.getElementById('katalog-cepat').scrollIntoView({behavior:'smooth'})"
                    class="w-full bg-white hover:bg-gray-50 border border-gray-300 rounded-xl py-2.5 text-sm font-semibold text-gray-800 flex items-center justify-center space-x-1.5">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Tambah Item</span>
                </button>
            </div>
        </div>

        <div class="lg:col-span-4 bg-white rounded-xl border border-gray-300 overflow-hidden flex flex-col justify-between">
            <div>
                <div class="bg-[#14171F] text-white px-4 py-3 font-bold text-sm flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Ringkasan Pembayaran</span>
                </div>

                <form action="{{ route('kasir.checkout') }}" method="POST" id="form-bayar" class="p-5 space-y-5">
                    @csrf
                    <input type="hidden" name="metode_bayar" id="metode_bayar" value="Tunai">

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-800">Total Belanja :</span>
                        <span class="text-2xl font-extrabold text-gray-900">Rp{{ number_format($totalHarga, 0, ',', '.') }}</span>
                    </div>

                    <div id="box-tunai" class="flex justify-between items-center gap-3">
                        <label class="text-sm font-bold text-gray-800">Pembayaran :</label>
                        <div class="relative w-44">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 text-xs">Rp</span>
                            <input type="number" name="nominal_bayar" id="nominal_bayar" min="0" oninput="hitungKembalian()"
                                class="w-full pl-8 pr-3 py-1.5 border border-gray-300 rounded-lg text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-gray-900">
                        </div>
                    </div>

                    <div id="box-nontunai" class="hidden bg-gray-50 border border-gray-300 rounded-lg p-3">
                        <label class="flex items-center space-x-2 text-xs font-medium text-gray-800 cursor-pointer">
                            <input type="checkbox" name="konfirmasi_nontunai" value="1" class="rounded">
                            <span>Konfirmasi pembayaran non-tunai telah diterima</span>
                        </label>
                    </div>

                    <hr class="border-gray-200">

                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-800">Kembalian :</span>
                        <span id="label-kembalian" class="text-xl font-extrabold text-gray-900">Rp0</span>
                    </div>

                    <div class="pt-6">
                        <div class="border border-gray-300 rounded-xl overflow-hidden">
                            <div class="bg-[#14171F] text-white px-3.5 py-2 text-xs font-bold flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                <span>Metode Pembayaran</span>
                            </div>
                            <div class="p-3 grid grid-cols-3 gap-2">
                                <button type="button" onclick="pilihMetode('Tunai', this)"
                                    class="btn-metode border-2 border-[#14171F] bg-gray-100 rounded-xl py-3 flex flex-col items-center justify-center space-y-1 text-xs font-bold text-gray-900">
                                    <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span>Tunai</span>
                                </button>
                                <button type="button" onclick="pilihMetode('Transfer', this)"
                                    class="btn-metode border border-gray-300 bg-white rounded-xl py-3 flex flex-col items-center justify-center space-y-1 text-xs font-semibold text-gray-700">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                    </svg>
                                    <span>Transfer</span>
                                </button>
                                <button type="button" onclick="pilihMetode('QR Code', this)"
                                    class="btn-metode border border-gray-300 bg-white rounded-xl py-3 flex flex-col items-center justify-center space-y-1 text-xs font-semibold text-gray-700">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                    </svg>
                                    <span>QR Code</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" {{ $totalHarga <= 0 ? 'disabled' : '' }}
                            class="w-full py-3.5 rounded-xl font-bold text-sm flex items-center justify-center space-x-2 shadow transition {{ $totalHarga <= 0 ? 'bg-gray-400 text-gray-200 cursor-not-allowed' : 'bg-[#14171F] hover:bg-black text-white' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Proses Pembayaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="katalog-cepat" class="bg-white rounded-xl border border-gray-300 p-4 space-y-3">
        <div class="font-bold text-sm text-gray-900">Daftar Produk Aktif (Klik untuk Tambah ke Keranjang)</div>
        @if($produkList->isEmpty())
            <div class="text-center py-6 text-sm text-gray-500">Produk tidak ditemukan</div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($produkList as $prod)
                    <div class="border border-gray-200 rounded-xl p-3 flex justify-between items-center hover:border-gray-900 transition">
                        <div>
                            <div class="text-[11px] font-mono text-gray-500">{{ $prod->kode_produk }} • {{ $prod->varian_ukuran }}</div>
                            <div class="font-bold text-sm text-gray-900">{{ $prod->nama_produk }}</div>
                            <div class="text-xs text-gray-600">Rp{{ number_format($prod->harga_jual, 0, ',', '.') }} | Stok: {{ $prod->stok_tersedia }}</div>
                        </div>
                        <form action="{{ route('kasir.add', $prod->id_produk) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-[#14171F] hover:bg-black text-white w-8 h-8 rounded-lg font-bold text-sm flex items-center justify-center">+</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<footer class="mt-6 bg-white border border-gray-300 rounded-xl px-6 py-3 flex justify-between items-center text-xs">
    <div class="flex items-center space-x-4 font-bold text-gray-800">
        <span class="flex items-center space-x-1.5">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <span>Total Item : {{ $totalItem }}</span>
        </span>
        <span class="text-gray-300">|</span>
        <span class="flex items-center space-x-1.5">
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
            </svg>
            <span>Diskon : Rp0</span>
        </span>
    </div>
    <button type="button" onclick="bukaStruk()" {{ !$lastTransaksi ? 'disabled' : '' }}
        class="border border-gray-300 rounded-lg px-4 py-1.5 font-semibold flex items-center space-x-1.5 {{ $lastTransaksi ? 'bg-white hover:bg-gray-100 text-gray-800' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
        </svg>
        <span>Cetak Struk</span>
    </button>
</footer>

@if(session('transaksi_sukses'))
    <div id="popup-sukses" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center shadow-2xl space-y-4">
            <div class="w-14 h-14 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900">Transaksi Berhasil!</h3>
            <p class="text-sm text-gray-600">{{ session('transaksi_sukses') }}</p>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="tutupPopupDanStruk()" class="w-1/2 border border-gray-300 py-2.5 rounded-xl text-xs font-bold hover:bg-gray-50 flex items-center justify-center space-x-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    <span>Lihat Struk</span>
                </button>
                <button type="button" onclick="document.getElementById('popup-sukses').remove()" class="w-1/2 bg-[#14171F] text-white py-2.5 rounded-xl text-xs font-bold hover:bg-black">
                    OK
                </button>
            </div>
        </div>
    </div>
@endif

@if($lastTransaksi)
    @php
        // Konversi data ke array/object secara aman untuk mencegah error tipe data
        $tx = is_array($lastTransaksi) ? (object)$lastTransaksi : $lastTransaksi;
        $txDetails = $tx->details ?? [];
    @endphp
    <div id="modal-struk" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
        <div class="bg-white rounded-xl max-w-sm w-full p-6 shadow-2xl space-y-4">
            <div id="area-struk" class="text-xs font-mono space-y-2 text-gray-800">
                <div class="text-center border-b border-dashed border-gray-400 pb-2">
                    <div class="font-bold text-sm">TOKO PAKAIAN AFANDER</div>
                    <div>Pusat Mbay</div>
                    <div>No: {{ $tx->kode_struk ?? '-' }}</div>
                    <div>{{ $tx->waktu_transaksi ?? '-' }}</div>
                    <div>Kasir: {{ $tx->nama_kasir ?? '-' }}</div>
                </div>
                <div class="border-b border-dashed border-gray-400 py-2 space-y-1">
                    @foreach($txDetails as $det)
                        @php
                            $d = is_array($det) ? (object)$det : $det;
                            $p = isset($d->produk) ? (is_array($d->produk) ? (object)$d->produk : $d->produk) : null;
                        @endphp
                        <div>
                            <div class="font-bold">{{ $p->nama_produk ?? 'Produk' }}</div>
                            <div class="flex justify-between">
                                <span>{{ $d->qty_beli ?? 1 }} x Rp{{ number_format($d->harga_satuan ?? 0, 0, ',', '.') }}</span>
                                <span>Rp{{ number_format($d->subtotal ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="space-y-1 pt-1">
                    <div class="flex justify-between font-bold">
                        <span>TOTAL</span>
                        <span>Rp{{ number_format($tx->total_harga ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>BAYAR ({{ $tx->metode_bayar ?? 'Tunai' }})</span>
                        <span>Rp{{ number_format($tx->nominal_bayar ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between font-bold">
                        <span>KEMBALIAN</span>
                        <span>Rp{{ number_format($tx->kembalian ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
                <div class="text-center border-t border-dashed border-gray-400 pt-2">
                    Terima Kasih Telah Berbelanja
                </div>
            </div>
            <div class="flex gap-2 pt-2">
                <button type="button" onclick="window.print()" class="w-1/2 bg-[#14171F] text-white py-2 rounded-lg text-xs font-bold">Cetak</button>
                <button type="button" onclick="document.getElementById('modal-struk').classList.add('hidden')" class="w-1/2 border border-gray-300 py-2 rounded-lg text-xs font-bold">Tutup</button>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
    const totalBelanja = {{ $totalHarga }};

    function hitungKembalian() {
        const bayar = parseInt(document.getElementById('nominal_bayar').value) || 0;
        const label = document.getElementById('label-kembalian');

        if (bayar >= totalBelanja) {
            const kembali = bayar - totalBelanja;
            label.innerText = 'Rp' + kembali.toLocaleString('id-ID');
            label.className = 'text-xl font-extrabold text-green-600';
        } else {
            const kurang = totalBelanja - bayar;
            label.innerText = '-Rp' + kurang.toLocaleString('id-ID');
            label.className = 'text-xl font-extrabold text-red-600';
        }
    }

    function pilihMetode(metode, el) {
        document.getElementById('metode_bayar').value = metode;

        document.querySelectorAll('.btn-metode').forEach(btn => {
            btn.className = 'btn-metode border border-gray-300 bg-white rounded-xl py-3 flex flex-col items-center justify-center space-y-1 text-xs font-semibold text-gray-700';
            const svgIcon = btn.querySelector('svg');
            if(svgIcon) svgIcon.classList.replace('text-gray-800', 'text-gray-600');
        });
        el.className = 'btn-metode border-2 border-[#14171F] bg-gray-100 rounded-xl py-3 flex flex-col items-center justify-center space-y-1 text-xs font-bold text-gray-900';
        const activeSvg = el.querySelector('svg');
        if(activeSvg) activeSvg.classList.replace('text-gray-600', 'text-gray-800');

        const boxTunai = document.getElementById('box-tunai');
        const boxNonTunai = document.getElementById('box-nontunai');
        const labelKembalian = document.getElementById('label-kembalian');

        if (metode === 'Tunai') {
            boxTunai.classList.remove('hidden');
            boxNonTunai.classList.add('hidden');
            hitungKembalian();
        } else {
            boxTunai.classList.add('hidden');
            boxNonTunai.classList.remove('hidden');
            labelKembalian.innerText = 'Rp0';
            labelKembalian.className = 'text-xl font-extrabold text-gray-900';
        }
    }

    function bukaStruk() {
        const modal = document.getElementById('modal-struk');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function tutupPopupDanStruk() {
        const popup = document.getElementById('popup-sukses');
        if (popup) popup.remove();
        bukaStruk();
    }
</script>
@endpush
@endsection