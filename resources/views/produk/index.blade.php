@extends('layouts.app')

@section('content')
<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-5">
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 rounded-lg bg-[#14171F] text-white flex items-center justify-center font-bold">⬡</div>
            <h1 class="text-xl font-bold text-gray-900">Manajemen Produk dan Harga</h1>
        </div>
        <button onclick="openModalTambah()" class="bg-[#14171F] hover:bg-black text-white px-4 py-2.5 rounded-lg text-sm font-semibold flex items-center space-x-2 shadow-sm">
            <span>+</span>
            <span>Tambah Produk Baru</span>
        </button>
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
    @if($errors->any())
        <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-2.5 rounded-lg text-sm">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('produk.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
        <div class="relative flex-grow min-w-[240px]">
            <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none text-gray-400">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
    </svg>
</span>
            <input type="text" name="keyword" value="{{ $keyword ?? '' }}" placeholder="Cari produk... (Nama atau Kode)"
                class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-gray-900">
        </div>

        <select name="kategori" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm bg-white">
            <option value="">Semua Kategori</option>
            @foreach($kategoriList as $kat)
                <option value="{{ $kat->id_kategori }}" {{ ($kategoriId ?? '') == $kat->id_kategori ? 'selected' : '' }}>
                    {{ $kat->nama_kategori }}
                </option>
            @endforeach
        </select>

        <select name="status" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3.5 py-2 text-sm bg-white">
            <option value="">Semua Status</option>
            <option value="1" {{ ($status ?? '') === '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ ($status ?? '') === '0' ? 'selected' : '' }}>Nonaktif</option>
        </select>

        <a href="{{ route('produk.index') }}" class="bg-[#14171F] hover:bg-black text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-1.5">
            <span>↻</span><span>Refresh</span>
        </a>
    </form>

    <div class="overflow-x-auto border border-gray-200 rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-[#E5E7EB] text-gray-800 font-semibold">
                <tr>
                    <th class="py-3 px-3 text-center border-r border-gray-300 w-12">No</th>
                    <th class="py-3 px-3 text-center border-r border-gray-300">Kode Produk</th>
                    <th class="py-3 px-3 text-left border-r border-gray-300">Nama Produk</th>
                    <th class="py-3 px-3 text-left border-r border-gray-300">Kategori</th>
                    <th class="py-3 px-3 text-center border-r border-gray-300">Harga Modal</th>
                    <th class="py-3 px-3 text-center border-r border-gray-300">Harga Jual</th>
                    <th class="py-3 px-3 text-center border-r border-gray-300">Stok Tersedia</th>
                    <th class="py-3 px-3 text-center border-r border-gray-300">Status Aktif</th>
                    <th class="py-3 px-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse($produkList as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="py-3 px-3 text-center border-r border-gray-200">{{ $index + 1 }}</td>
                        <td class="py-3 px-3 text-center border-r border-gray-200 font-medium">{{ $item->kode_produk }}</td>
                        <td class="py-3 px-3 border-r border-gray-200">
                            <div class="font-medium text-gray-900">{{ $item->nama_produk }}</div>
                            <div class="text-xs text-gray-500">Ukuran: {{ $item->varian_ukuran }}</div>
                        </td>
                        <td class="py-3 px-3 border-r border-gray-200">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                        <td class="py-3 px-3 text-center border-r border-gray-200">Rp{{ number_format($item->harga_modal, 0, ',', '.') }}</td>
                        <td class="py-3 px-3 text-center border-r border-gray-200">Rp{{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                        <td class="py-3 px-3 text-center border-r border-gray-200 font-medium">{{ $item->stok_tersedia }}</td>
                        <td class="py-3 px-3 text-center border-r border-gray-200">
                            @if($item->status_aktif)
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-gray-200 text-gray-900 border border-gray-400">
                                    ✓ Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold bg-gray-300 text-gray-700 border border-gray-400">
                                    — Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-3">
                            <div class="flex items-center justify-center space-x-1.5">
                                <!-- Tombol Ubah -->
                                <button type="button" onclick='openModalEdit(@json($item))' class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded text-xs font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <svg class="w-3.5 h-3.5 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    Ubah
                                </button>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('produk.destroy', $item->id_produk) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded text-xs font-medium text-gray-700 bg-white hover:bg-red-50 hover:text-red-600">
                                        <svg class="w-3.5 h-3.5 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>

                                <!-- Tombol Status -->
                                <form action="{{ route('produk.status', $item->id_produk) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded text-xs font-medium text-gray-700 bg-white hover:bg-gray-50">
                                        <svg class="w-3.5 h-3.5 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                        </svg>
                                        {{ $item->status_aktif ? 'Nonaktif' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="py-10 text-center text-gray-400">Belum ada data produk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-between items-center text-xs text-gray-600 pt-2">
        <div>Menampilkan 1 - {{ count($produkList) }} dari {{ count($produkList) }} data</div>
        <div class="flex items-center space-x-1">
            <span class="px-2.5 py-1.5 border border-gray-300 rounded bg-white">‹</span>
            <span class="px-3 py-1.5 rounded bg-[#14171F] text-white font-semibold">1</span>
            <span class="px-2.5 py-1.5 border border-gray-300 rounded bg-white">›</span>
        </div>
    </div>
</div>

<div id="modal-tambah" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl space-y-4">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="font-bold text-base text-gray-900">Tambah Produk Baru</h3>
            <button onclick="closeModal('modal-tambah')" class="text-gray-400 hover:text-gray-700 text-lg">✕</button>
        </div>
        <form action="{{ route('produk.store') }}" method="POST" class="space-y-3 text-sm">
            @csrf
            <div>
                <label class="block text-xs font-semibold mb-1">Kode Produk (Maks. 20 Karakter)</label>
                <input type="text" name="kode_produk" maxlength="20" placeholder="PRD007" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Nama Produk</label>
                <input type="text" name="nama_produk" maxlength="100" placeholder="Kemeja Linen" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold mb-1">Kategori</label>
                    <select name="id_kategori" required class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white">
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1">Varian Ukuran</label>
                    <input type="text" name="varian_ukuran" placeholder="L" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold mb-1">Harga Modal (Rp)</label>
                    <input type="number" name="harga_modal" min="0" step="1" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1">Harga Jual (Rp)</label>
                    <input type="number" name="harga_jual" min="0" step="1" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Stok Tersedia</label>
                <input type="number" name="stok_tersedia" min="0" step="1" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="flex justify-end space-x-2 pt-3 border-t">
                <button type="button" onclick="closeModal('modal-tambah')" class="px-4 py-2 border rounded-lg text-xs font-semibold">Batal</button>
                <button type="submit" class="px-4 py-2 bg-[#14171F] text-white rounded-lg text-xs font-semibold">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-edit" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-xl max-w-md w-full p-6 shadow-xl space-y-4">
        <div class="flex justify-between items-center border-b pb-3">
            <h3 class="font-bold text-base text-gray-900">Ubah Data Produk</h3>
            <button onclick="closeModal('modal-edit')" class="text-gray-400 hover:text-gray-700 text-lg">✕</button>
        </div>
        <form id="form-edit" method="POST" class="space-y-3 text-sm">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold mb-1">Kode Produk</label>
                <input type="text" name="kode_produk" id="edit-kode" maxlength="20" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Nama Produk</label>
                <input type="text" name="nama_produk" id="edit-nama" maxlength="100" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold mb-1">Kategori</label>
                    <select name="id_kategori" id="edit-kategori" required class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white">
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1">Varian Ukuran</label>
                    <input type="text" name="varian_ukuran" id="edit-ukuran" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold mb-1">Harga Modal (Rp)</label>
                    <input type="number" name="harga_modal" id="edit-modal" min="0" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-xs font-semibold mb-1">Harga Jual (Rp)</label>
                    <input type="number" name="harga_jual" id="edit-jual" min="0" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Stok Tersedia</label>
                <input type="number" name="stok_tersedia" id="edit-stok" min="0" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="flex justify-end space-x-2 pt-3 border-t">
                <button type="button" onclick="closeModal('modal-edit')" class="px-4 py-2 border rounded-lg text-xs font-semibold">Batal</button>
                <button type="submit" class="px-4 py-2 bg-[#14171F] text-white rounded-lg text-xs font-semibold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openModalTambah() {
        document.getElementById('modal-tambah').classList.remove('hidden');
        document.getElementById('modal-tambah').classList.add('flex');
    }

    function openModalEdit(item) {
        document.getElementById('form-edit').action = '/produk/' + item.id_produk;
        document.getElementById('edit-kode').value = item.kode_produk;
        document.getElementById('edit-nama').value = item.nama_produk;
        document.getElementById('edit-kategori').value = item.id_kategori;
        document.getElementById('edit-ukuran').value = item.varian_ukuran;
        document.getElementById('edit-modal').value = item.harga_modal;
        document.getElementById('edit-jual').value = item.harga_jual;
        document.getElementById('edit-stok').value = item.stok_tersedia;
        document.getElementById('modal-edit').classList.remove('hidden');
        document.getElementById('modal-edit').classList.add('flex');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    }
</script>
@endpush
@endsection