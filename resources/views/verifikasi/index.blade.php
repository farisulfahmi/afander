@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
    <div class="flex items-center space-x-3">
        <div class="w-9 h-9 rounded-lg bg-[#14171F] text-white flex items-center justify-center font-bold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
            </svg>
        </div>
        <h1 class="text-xl font-bold text-gray-900">Verifikasi Barang Masuk dari Supplier</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-2.5 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-2.5 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('verifikasi.store') }}" method="POST" class="space-y-6">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-gray-800 mb-1.5">Nama Supplier</label>
                <select name="nama_supplier" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white">
                    <option value="">Pilih Supplier...</option>
                    <option value="PT Garment Nusantara">PT Garment Nusantara</option>
                    <option value="CV Tekstil Jaya">CV Tekstil Jaya</option>
                    <option value="Grosir Tanah Abang">Grosir Tanah Abang</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-800 mb-1.5">Tanggal Datang</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </span>
                    <input type="date" name="tanggal_terima" value="{{ date('Y-m-d') }}" required
                        class="w-full pl-9 pr-9 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                    <span class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-800 mb-1.5">Pilih Produk (Opsional)</label>
                <select id="filter-produk" onchange="window.location.href='{{ route('verifikasi.index') }}?id_produk='+this.value"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white">
                    <option value="">Semua Produk</option>
                    @foreach($allProduk as $p)
                        <option value="{{ $p->id_produk }}" {{ ($produkId ?? '') == $p->id_produk ? 'selected' : '' }}>
                            {{ $p->kode_produk }} - {{ $p->nama_produk }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <a href="{{ route('verifikasi.index') }}" class="w-full bg-[#14171F] hover:bg-black text-white py-2 px-4 rounded-lg text-sm font-semibold flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <span>Tampilkan</span>
                </a>
            </div>
        </div>

        <div class="overflow-x-auto border border-gray-200 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-[#E5E7EB] text-gray-800 font-semibold text-center">
                    <tr>
                        <th class="py-3 px-3 border-r border-gray-300 w-12">No</th>
                        <th class="py-3 px-3 border-r border-gray-300">Kode Produk</th>
                        <th class="py-3 px-3 text-left border-r border-gray-300">Nama Produk</th>
                        <th class="py-3 px-3 border-r border-gray-300 w-36">Jumlah pada Nota Supplier</th>
                        <th class="py-3 px-3 border-r border-gray-300 w-36">Jumlah Fisik Kondisi Bagus</th>
                        <th class="py-3 px-3 border-r border-gray-300 w-36">Jumlah Rusak / Cacat Ekspedisi</th>
                        <th class="py-3 px-3 border-r border-gray-300 w-36">Status</th>
                        <th class="py-3 px-3 text-left">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($produkList as $idx => $prod)
                        <tr class="verif-row">
                            <td class="py-3 px-3 text-center border-r border-gray-200">{{ $idx + 1 }}</td>
                            <td class="py-3 px-3 text-center border-r border-gray-200 font-medium">{{ $prod->kode_produk }}</td>
                            <td class="py-3 px-3 border-r border-gray-200 font-medium text-gray-900">
                                {{ $prod->nama_produk }}
                                <input type="hidden" name="items[{{ $idx }}][id_produk]" value="{{ $prod->id_produk }}">
                            </td>
                            <td class="py-3 px-3 text-center border-r border-gray-200">
                                <input type="number" name="items[{{ $idx }}][qty_nota]" value="0" min="0" oninput="hitungVerifikasi()"
                                    class="qty-nota w-20 text-center border border-gray-300 rounded px-2 py-1">
                            </td>
                            <td class="py-3 px-3 text-center border-r border-gray-200">
                                <input type="number" name="items[{{ $idx }}][qty_bagus]" value="0" min="0" oninput="hitungVerifikasi()"
                                    class="qty-bagus w-20 text-center border border-gray-300 rounded px-2 py-1">
                            </td>
                            <td class="py-3 px-3 text-center border-r border-gray-200">
                                <input type="number" name="items[{{ $idx }}][qty_rusak]" value="0" min="0" oninput="hitungVerifikasi()"
                                    class="qty-rusak w-20 text-center border border-gray-300 rounded px-2 py-1">
                            </td>
                            <td class="py-3 px-3 text-center border-r border-gray-200">
                                <span class="status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#14171F] text-white">
                                    ✓ Sesuai
                                </span>
                            </td>
                            <td class="py-3 px-3 text-xs text-gray-600 keterangan-text">-</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end pt-4">
            <div class="md:col-span-2 border border-gray-300 rounded-xl overflow-hidden max-w-lg">
                <div class="bg-gray-100 px-4 py-2.5 border-b border-gray-300 font-bold text-sm flex items-center space-x-2">
                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span>Ringkasan Penerimaan</span>
                </div>
                <div class="p-4 space-y-2 text-sm">
                    <div class="flex">
                        <span class="w-40 text-gray-600">Total Item</span>
                        <span class="font-bold">: <span id="sum-jenis">0</span> Jenis Produk</span>
                    </div>
                    <div class="flex">
                        <span class="w-40 text-gray-600">Total Barang Baik</span>
                        <span class="font-bold">: <span id="sum-bagus">0</span> pcs</span>
                    </div>
                    <div class="flex">
                        <span class="w-40 text-gray-600">Total Rusak</span>
                        <span class="font-bold">: <span id="sum-rusak">0</span> pcs</span>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <button type="submit" class="w-full bg-[#14171F] hover:bg-black text-white py-3 rounded-xl font-semibold text-sm flex items-center justify-center space-x-2 shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    <span>Simpan & Perbarui Stok</span>
                </button>
                <a href="{{ route('produk.index') }}" class="w-full bg-gray-100 hover:bg-gray-200 border border-gray-300 text-gray-800 py-3 rounded-xl font-semibold text-sm flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span>Batal / Kembali</span>
                </a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function hitungVerifikasi() {
        const rows = document.querySelectorAll('.verif-row');
        let totalJenis = 0;
        let totalBagus = 0;
        let totalRusak = 0;

        rows.forEach(row => {
            const nota = parseInt(row.querySelector('.qty-nota').value) || 0;
            const bagus = parseInt(row.querySelector('.qty-bagus').value) || 0;
            const rusak = parseInt(row.querySelector('.qty-rusak').value) || 0;
            const badge = row.querySelector('.status-badge');
            const ket = row.querySelector('.keterangan-text');

            if (nota > 0 || bagus > 0 || rusak > 0) {
                totalJenis++;
            }
            totalBagus += bagus;
            totalRusak += rusak;

            if ((bagus + rusak) === nota) {
                badge.innerHTML = '✓ Sesuai';
                badge.className = 'status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#14171F] text-white';
            } else {
                badge.innerHTML = '! Selisih Penerimaan';
                badge.className = 'status-badge inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-600 text-white';
            }

            ket.innerText = rusak > 0 ? 'Ada ' + rusak + ' pcs cacat' : '-';
        });

        document.getElementById('sum-jenis').innerText = totalJenis;
        document.getElementById('sum-bagus').innerText = totalBagus;
        document.getElementById('sum-rusak').innerText = totalRusak;
    }
</script>
@endpush
@endsection