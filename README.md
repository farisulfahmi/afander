# 🛍️ Website Sistem Kasir (POS), Manajemen Stok, & Katalog Digital

**Studi Kasus: Toko Pakaian Afander**

Repositori ini berisi sistem informasi berbasis web yang dirancang khusus untuk mendigitalisasi operasional ritel pakaian di Toko Pakaian Afander (Pusat Mbay), mencakup pencatatan transaksi kasir (_Point of Sales_), manajemen inventaris, verifikasi barang masuk, hingga katalog produk digital[cite: 3].

---

## 👥 Tim Pengembang (Kelompok 4)

- **Farisul Fahmi** — Project Manager[cite: 3]
- **Bebi Aulia Maharani** — System Analyst & Pemilik Toko[cite: 3]
- **Ibrahimovic & Muhammad Rafsyan** — UI/UX Designer[cite: 3]
- **Inayah & Putri Tri Sadiva** — Frontend Developer[cite: 3]
- **Hafiza Aulia Ibrahim & Aisyah Diva Damayanti** — Backend Developer[cite: 3]
- **Mufidah Abdi Rasti** — Quality Assurance[cite: 3]

---

## 🚀 Fitur Utama & User Stories

### 🛒 Modul Kasir / POS (Kasir)

- **Pencarian Produk (US-01):** Mencari produk pakaian berdasarkan nama atau kode barang secara cepat beserta informasi harga dan sisa stok[cite: 3, 4].
- **Manajemen Keranjang (US-02 & US-03):** Menambah, mengubah jumlah, atau menghapus item belanja dengan validasi stok otomatis dan kalkulasi total harga secara _real-time_[cite: 3, 4].
- **Multi-Metode Pembayaran (US-04):** Mendukung pencatatan pembayaran Tunai (dengan kalkulasi kembalian), Transfer, dan QR Code[cite: 3, 4].
- **Cetak Struk (US-05):** Menghasilkan nomor struk unik, detail transaksi, metode bayar, dan nama kasir setelah transaksi selesai[cite: 3, 4].

### 📦 Modul Inventaris & Toko (Pemilik)

- **Manajemen Produk & Harga (US-06 & US-08):** Menambah produk baru, mencetak label harga, memperbarui harga, serta mengubah status produk menjadi _Nonaktif_ agar tidak muncul di kasir[cite: 3, 4].
- **Verifikasi Barang Masuk (US-07):** Memeriksa pengiriman dari supplier, memisahkan stok barang bagus dan barang rusak ekspedisi, serta mencatat selisih penerimaan[cite: 3, 4].
- **Kontrol Akses & Keamanan (US-09):** Pembatasan hak akses (_Role-Based Access Control_) yang ketat antara **Pemilik** (akses penuh) dan **Kasir** (khusus halaman POS dan proteksi URL)[cite: 3, 4].
- **Katalog Digital & Rekap (US-10):** Menyediakan rekapitulasi penjualan serta katalog produk online yang dapat dibagikan melalui WhatsApp atau media sosial[cite: 3, 4].

---

## 🛠️ Tech Stack

- **Framework Backend:** Laravel 13 (PHP)
- **Frontend:** Blade Templates, Tailwind CSS
- **Database:** MySQL / SQLite
- **Autentikasi & Keamanan:** Custom `CheckRole` Middleware & Auth Facade

---

## ⚙️ Panduan Instalasi & Menjalankan Proyek

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal Anda:

1. **Clone repositori ini:**
    ```bash
    git clone [https://github.com/username/nama-repositori.git](https://github.com/username/nama-repositori.git)
    cd nama-repositori
    ```
