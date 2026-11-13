# KasirQu

Aplikasi latihan yang dipakai sepanjang kursus "Belajar Pemrograman" 16 pertemuan.
KasirQu adalah **nama aplikasi**; **Toko Maju Jaya** adalah toko contohnya.

Folder ini adalah **satu-satunya sumber kode aplikasi** (living project).
Setiap pertemuan berikutnya melanjutkan kode di folder yang sama, jadi tidak ada
salinan yang terpisah-pisah.

## Cara Mengakses

Taruh folder induk `smk4` di dalam folder web server, lalu buka:

```
http://localhost/smk4/kasirqu/
```

- Laragon: `D:\laragon\www\smk4\kasirqu\`
- XAMPP   : `C:\xampp\htdocs\smk4\kasirqu\`

PHP harus dijalankan lewat `http://localhost/...`, bukan dobel-klik file.

## Keamanan Web Root (opsional, bonus P9)

Karena folder `smk4` memuat aplikasi sekaligus bahan/skrip, gunakan file
`.htaccess` di **root proyek (`smk4/`)** untuk mengizinkan hanya `kasirqu/`:

```
RewriteEngine On
RewriteRule ^kasirqu(/|$) - [L]
RewriteRule ^ - [F]
```

- File aktifnya sudah disediakan di **root `smk4/`** (satu level di atas folder
  ini).
- Untuk demo "SEBELUM" di kelas (materi terlihat terbuka), rename/hapus
  sementara `.htaccess` di root, lalu kembalikan.
- Khusus Apache (XAMPP/Laragon). `kasirqu/.htaccess` di folder ini hanyalah
  **contoh berisi komentar** (tidak aktif) — jangan isi aturan `^kasirqu` di
  dalamnya karena akan memblokir kasirqu sendiri.

## Peta File (status Pertemuan 14)

| File | Isi | Pertemuan |
|---|---|---|
| `index.php` | Halaman menu, daftar Latihan 1-12 (contoh array + foreach) | P9-P14 |
| `01_halo.php` | `echo`, campur HTML + PHP | P9 |
| `02_variabel.php` | Variabel (`$nama`, `$umur`, ...) | P9 |
| `03_tipe_data.php` | String, integer, float, boolean, array | P9 |
| `04_operator.php` | Operator hitung & gabung teks | P9 |
| `05_percabangan.php` | `if` / `else` / `switch` | P9 |
| `06_perulangan.php` | `for` / `while` / `foreach` | P9 |
| `07_mesin_kasir.php` | MOMEN WOW: kasir otomatis (gabungan semua materi) | P9 |
| `08_latihan_bebas.php` | Tantangan mandiri siswa | P9 |
| `09_css_dasar.php` | CSS dasar: selector, class, id | P11 |
| `10_css_bootstrap.php` | CSS siap pakai dengan Bootstrap | P11 |
| `assets/style.css` | Tampilan (tema navy/oranye) | P9 |
| `PANDUAN_SISWA.txt` | Panduan singkat untuk siswa | P9 |
| `template/` | Template sederhana: 2 partial + halaman contoh + pengenalan form + upload foto + panduan partial + GET/POST | P12-P13 |
| `config/database.php` | Koneksi MySQLi ke database `db_penjualan` | P14 |
| `crud/` | Latihan koneksi database + CRUD produk + transaksi + hub panduan (mysqli) | P14-P16 |

## Template & Form (P12-P13)

Buka: `http://localhost/smk4/kasirqu/template/`

- `partials/header.php` + `partials/footer.php` = layout bersama.
- `index.php`, `produk.php`, `transaksi.php` = contoh konten.
- `form.php` = ringkasan GET vs POST.
- `form_dasar.php` = contoh form + tabel dalam satu halaman.
- `form_pengenalan.php` = anatomi tag form, `id` vs `name`, dan demo mini.
- `form_komponen.php` = komponen input teks/angka, radio, checkbox, select, textarea.
- `form_output.php` = validasi server, output `$_POST`, dan `htmlspecialchars`.
- `form_upload.php` = upload foto/image: `enctype`, `$_FILES`, validasi, dan preview.
- `form_latihan.php` = brief latihan Form Pemesanan KasirQu.
- `form_get.php` = contoh form GET; data muncul di URL.
- `form_post.php` = contoh form POST; data tidak muncul di URL.
- `panduan_partial.php` = panduan header/footer, cara `require`, dan peta folder.
- Semua halaman berada di folder yang sama sehingga `assets/style.css` aman
  tanpa `base_url()`.
- Siswa fokus mengedit konten halaman, bukan membedah template.

## Koneksi Database & CRUD (P14)

Buka: `http://localhost/smk4/kasirqu/crud/`

- `config/database.php` = satu koneksi MySQLi untuk semua halaman CRUD.
- `koneksi.php` = tes koneksi, daftar tabel, dan solusi error.
- `panduan.php` = hub panduan: partial, koneksi, dan CRUD.
- `panduan_koneksi.php` = materi koneksi: peletakan file, isi `config/database.php`, dan cara `require`.
- `panduan_crud.php` = panduan tambah, edit, dan hapus produk.
- `index.php` = READ: tabel produk + pencarian.
- `tambah.php` = CREATE: form POST + INSERT.
- `edit.php` = UPDATE: form + UPDATE.
- `hapus.php` = DELETE: konfirmasi + DELETE via POST.
- `setup_produk.sql` = pembuat tabel produk (fallback).
- Wajib prepared statement, `htmlspecialchars`, dan POST untuk aksi ubah data.

Database target: **`db_penjualan`**. Jika belum ada, impor
`kasirqu/crud/setup_produk.sql` (membuat database + tabel produk). Materi P7
lama bisa menyebut `penjualan_lengkap`; untuk lab ini, gunakan nama
`db_penjualan` agar sama dengan konfigurasi dan proyek akhir `kasirsmk/`.

## Transaksi Pembelian (P16)

Buka: `http://localhost/smk4/kasirqu/crud/transaksi.php`

- `transaksi.php` = hub transaksi + ringkasan nota.
- `pembelian.php` / `pembelian_tambah.php` / `pembelian_detail.php` = daftar,
  form multi-baris, dan detail nota pembelian (barang masuk).
- `transaksi_helper.php` = `rupiah()`, `saldo_stok_terakhir()`, `buat_nomor()`,
  serta daftar kasir/supplier/produk.
- `assets/transaksi.js` = tambah/hapus baris, isi harga otomatis, dan hitung
  subtotal/total.
- `panduan_transaksi.php` = algoritma, potongan kode, dan rumus stok.

Pola wajib: validasi dulu, lalu `begin_transaction` → INSERT kepala nota →
INSERT detail + `stok` → `commit`. Jika satu barang gagal, semua dibatalkan
(`rollback`).

Catatan: modul **penjualan belum dipasang** di aplikasi. Kodenya disimpan di
`pertemuan-16/kode-penjualan/` (di luar folder `kasirqu/`) dan akan dibangun
sendiri oleh siswa pada sesi berikutnya memakai pola yang sama.

Catatan: `07_mesin_kasir.php` (alur kasir) adalah cikal bakal fitur transaksi
yang sekarang dilengkapi di `crud/`.
