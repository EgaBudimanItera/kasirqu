# Koneksi Database & CRUD Produk (KasirQu)

Modul latihan untuk menyambungkan PHP ke MySQL memakai **mysqli**, lalu
melakukan **Create, Read, Update, Delete** pada tabel `produk`.

## Buka di browser

```
http://localhost/smk4/kasirqu/crud/
```

## Prasyarat

1. MySQL menyala (Laragon: Start All, XAMPP: Start MySQL).
2. Database `db_penjualan` sudah ada.
   - Cara lengkap: impor `pertemuan-07/bangun_database_penjualan.sql`.
   - Cara cepat: impor `kasirqu/crud/setup_produk.sql`.
3. Ekstensi `mysqli` aktif di PHP.

## Struktur

```
kasirqu/
├── config/
│   └── database.php       # koneksi mysqli (host, user, password, database)
└── crud/
    ├── index.php          # READ: tabel produk + pencarian
    ├── koneksi.php        # tes koneksi + daftar tabel + solusi error
    ├── panduan.php        # hub panduan
    ├── panduan_koneksi.php # materi koneksi, peletakan file, dan cara require
    ├── panduan_crud.php   # panduan tambah, edit, dan hapus produk
    ├── tambah.php         # CREATE: form + INSERT
    ├── edit.php           # UPDATE: form + UPDATE
    ├── hapus.php          # DELETE: konfirmasi + DELETE
    ├── setup_produk.sql   # pembuat tabel produk (fallback)
    ├── partials/          # header + footer
    ├── assets/style.css   # tampilan
    └── README.md
```

## Alur belajar 30-45 menit

1. Buka `panduan.php` - pilih materi yang ingin dibaca.
2. Baca `panduan_koneksi.php` - pahami peta folder, isi `config/database.php`,
   dan cara memanggil koneksi dengan `require`.
3. Buka `koneksi.php` - pastikan status koneksi berhasil dan tabel `produk` ada.
4. Baca `panduan_crud.php` - pahami alur tambah, edit, dan hapus produk.
5. Buka `index.php` - amati data produk yang dibaca dari database.
6. Buka `tambah.php` - tambahkan satu produk baru, lalu cek di `index.php`.
7. Buka `edit.php` lewat tombol Edit - ubah nama/harga/stok.
8. Buka `hapus.php` lewat tombol Hapus - baca peringatan, lalu konfirmasi.
9. Coba pencarian di `index.php` dengan kata kunci nama atau kode produk.

## Aturan aman

- Semua query yang memakai input pengguna menggunakan **prepared statement**.
- Semua data yang ditampilkan di-escape dengan `htmlspecialchars`.
- Aksi ubah data memakai **POST**, bukan link GET.
- Hapus produk memakai halaman konfirmasi.
- Produk yang sudah dipakai transaksi sebaiknya **tidak dihapus**. Di aplikasi
  nyata, gunakan status nonaktif (soft delete) agar riwayat penjualan tetap utuh.
- File `config/database.php` memakai user `root` tanpa password untuk latihan
  lokal. Jangan pakai konfigurasi ini di server online.

## Kaitan dengan pertemuan sebelumnya

- P11: tabel HTML dan CSS.
- P12: header/footer bersama.
- P13: form GET/POST, `$_GET` dan `$_POST`.
- P14: koneksi database + CRUD (modul ini).
