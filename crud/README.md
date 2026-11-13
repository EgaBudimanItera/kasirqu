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
    ├── transaksi.php      # hub pembelian
    ├── pembelian.php      # daftar nota pembelian + cari
    ├── pembelian_tambah.php   # form multi-baris + simpan 3 tabel
    ├── pembelian_detail.php   # nota + dampak ke stok
    ├── pembelian_batal.php    # batalkan nota + stok koreksi
    ├── transaksi_helper.php   # rupiah, saldo stok, nomor nota, master
    ├── panduan_transaksi.php  # panduan algoritma pembelian
    ├── presentasi.php     # slide P16 langsung di browser
    ├── presentasi_p11.php # slide P11 (update & delete produk)
    ├── setup_produk.sql   # pembuat tabel produk (fallback)
    ├── partials/          # header + footer
    ├── assets/style.css   # tampilan
    ├── assets/transaksi.css  # gaya form transaksi
    ├── assets/transaksi.js   # hitung subtotal/total + tambah baris
    ├── assets/presentasi.css # gaya halaman slide
    ├── assets/presentasi.js  # navigasi slide
    ├── assets/slide-p16/  # gambar slide P16 + manifest.json
    ├── assets/slide-p11/  # gambar slide P11 + manifest.json
    └── README.md
```

> Modul penjualan **belum ada** di folder ini. Kodenya disimpan di
> `pertemuan-16/kode-penjualan/` (di luar `kasirqu/`) dan akan dibangun sendiri
> oleh siswa pada sesi berikutnya.

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

## Slide di browser (P16)

Deck Pertemuan 16 bisa dipakai tanpa membuka PowerPoint:

```
http://localhost/smk4/kasirqu/crud/presentasi.php
```

Navigasi: `←/→` atau `Space` ganti slide, `F` fullscreen, `O` daftar slide,
`N` panel catatan pelatih, `Esc` menutup panel. Tombol **Catatan** membuka
speaker notes per slide, jadi panduan mengajar ikut terbaca di layar.

Slide di halaman ini adalah **gambar hasil ekspor** dari
`pertemuan-16/Pertemuan_16_Alur_Transaksi.pptx`. Kalau deck diubah, jalankan
ulang dari folder `smk4`:

```
python scripts/export_pertemuan16_slides.py
```

Perintah itu memperbarui `assets/slide-p16/*.png` dan `manifest.json`
(judul + catatan pelatih). Tanpa menjalankannya, halaman tetap memakai gambar
terakhir yang tersimpan.

Ekspor butuh Windows + Microsoft PowerPoint + `pywin32` (hanya saat membuat
ulang gambar, bukan saat mengajar). Bila `pywin32` belum ada:

```
pip install pywin32
```

Hanya ingin memperbarui judul/catatan tanpa mengekspor gambar:

```
python scripts/export_pertemuan16_slides.py --manifest-only
```

## Slide di browser (P11)

Materi update & delete produk (15 slide, 150 menit):

```
http://localhost/smk4/kasirqu/crud/presentasi_p11.php
```

Navigasi sama dengan slide P16: `←/→`, `N` catatan, `O` daftar slide,
`F` fullscreen. Gambar + manifest dibuat oleh:

```
python scripts/export_pertemuan11_slides.py
```

Sumbernya `pertemuan-11/Pertemuan_11_Update_Delete_Produk.pptx`. Tanpa
menjalankan skrip itu, halaman memakai gambar terakhir yang tersimpan.

Bagian pembelian punya deck sendiri (14 slide, 120 menit):

```
http://localhost/smk4/kasirqu/crud/presentasi_p11_pembelian.php
```

Isinya algoritma empat halaman pembelian (indeks, tambah, detail, batalkan)
lalu contoh kodenya. Ekspor ulang dengan:

```
python scripts/export_pertemuan11_pembelian_slides.py
```

Sumbernya `pertemuan-11/Pertemuan_11_Halaman_Pembelian.pptx`.

## Membatalkan nota pembelian

Nota pembelian **tidak punya halaman edit**. Kalau salah input, buka
`pembelian_batal.php?no=PB-0001` (tombol **Batalkan** ada di daftar dan di
halaman detail). Satu transaksi database:

1. `INSERT stok` keluar sebanyak isi nota (keterangan "Batal pembelian")
2. `DELETE detail_pembelian`
3. `DELETE pembelian`
4. `commit` — kalau ada langkah gagal, `rollback`

Pembatalan **ditolak** kalau `saldo_stok_terakhir` lebih kecil dari isi nota,
karena berarti barangnya sudah terjual.

## Aturan aman

- Semua query yang memakai input pengguna menggunakan **prepared statement**.
- Semua data yang ditampilkan di-escape dengan `htmlspecialchars`.
- Aksi ubah data memakai **POST**, bukan link GET.
- Hapus produk memakai halaman konfirmasi.
- Produk yang sudah dipakai transaksi **tidak bisa dihapus**:
  `hapus.php` menghitung `COUNT(*) FROM detail_pembelian` lebih dulu, lalu
  menolak kalau hasilnya lebih dari 0. Di versi latihan
  (`crud_latihan/hapus.php`) pengaman ini sengaja belum ada sebagai bahan
  praktik.
- Produk yang sudah dipakai transaksi sebaiknya **tidak dihapus**. Di aplikasi
  nyata, gunakan status nonaktif (soft delete) agar riwayat penjualan tetap utuh.
- File `config/database.php` memakai user `root` tanpa password untuk latihan
  lokal. Jangan pakai konfigurasi ini di server online.

## Kaitan dengan pertemuan sebelumnya

- P11: tabel HTML dan CSS.
- P12: header/footer bersama.
- P13: form GET/POST, `$_GET` dan `$_POST`.
- P14: koneksi database + CRUD (modul ini).
