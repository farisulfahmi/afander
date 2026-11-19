# 🛍️ Website Sistem Kasir (POS), Manajemen Stok, & Katalog Digital

**Studi Kasus: Toko Pakaian Afander**

Repositori ini berisi sistem informasi berbasis web yang dirancang khusus untuk mendigitalisasi operasional ritel pakaian di Toko Pakaian Afander (Pusat Mbay), mencakup pencatatan transaksi kasir (_Point of Sales_), manajemen inventaris, verifikasi barang masuk, hingga katalog produk digital

---

## 👥 Tim Pengembang (Kelompok 4)

- **Farisul Fahmi** — Project Manager[cite: 3]
- **Bebi Aulia Maharani** — System Analyst & Pemilik Toko
- **Ibrahimovic & Muhammad Rafsyan** — UI/UX Designer
- **Inayah & Putri Tri Sadiva** — Frontend Developer
- **Hafiza Aulia Ibrahim & Aisyah Diva Damayanti** — Backend Developer
- **Mufidah Abdi Rasti** — Quality Assurance

---

## 🚀 Fitur Utama & User Stories

### 🛒 Modul Kasir / POS (Kasir)

- **Pencarian Produk (US-01):** Mencari produk pakaian berdasarkan nama atau kode barang secara cepat beserta informasi harga dan sisa stok
- **Manajemen Keranjang (US-02 & US-03):** Menambah, mengubah jumlah, atau menghapus item belanja dengan validasi stok otomatis dan kalkulasi total harga secara _real-time_
- **Multi-Metode Pembayaran (US-04):** Mendukung pencatatan pembayaran Tunai (dengan kalkulasi kembalian), Transfer, dan QR Code

### 📦 Modul Inventaris & Toko (Pemilik)

- **Manajemen Produk & Harga (US-06 & US-08):** Menambah produk baru, mencetak label harga, memperbarui harga, serta mengubah status produk menjadi _Nonaktif_ agar tidak muncul di kasir
- **Verifikasi Barang Masuk (US-07):** Memeriksa pengiriman dari supplier, memisahkan stok barang bagus dan barang rusak ekspedisi, serta mencatat selisih penerimaan
- **Kontrol Akses & Keamanan (US-09):** Pembatasan hak akses (_Role-Based Access Control_) yang ketat antara **Pemilik** (akses penuh) dan **Kasir** (khusus halaman POS dan proteksi URL)

---

## 🛠️ Tech Stack

- **Framework Backend:** Laravel 13 (PHP)
- **Frontend:** Blade Templates, Tailwind CSS
- **Database:** MySQL
- **Autentikasi & Keamanan:** Custom `CheckRole` Middleware & Auth Facade

---

## ⚙️ Panduan Instalasi & Menjalankan Proyek

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal Anda:

1. **Clone repositori ini:**
    ```bash
    git clone [https://github.com/username/nama-repositori.git](https://github.com/username/nama-repositori.git)
    cd nama-repositori
    ```
