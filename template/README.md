# Template & Form KasirQu (P12–P13)

Starter kecil di dalam aplikasi **KasirQu**: **2 partial + halaman contoh**.
Semua halaman berada di folder yang sama, jadi `assets/style.css` langsung
bekerja tanpa `base_url()` dan tanpa `<base href>`.

## Buka di browser

```
http://localhost/smk4/kasirqu/template/
```

## Struktur

```
kasirqu/template/
├── partials/
│   ├── header.php      # head, topbar, menu, pembuka <main>
│   └── footer.php      # penutup <main>, footer, penutup HTML
├── assets/
│   └── style.css       # satu CSS untuk semua halaman
├── index.php           # contoh dashboard
├── produk.php          # contoh tabel produk
├── transaksi.php       # contoh array + foreach + total
├── form.php            # ringkasan GET vs POST
├── form_dasar.php      # form + tabel paling sederhana
├── form_get.php        # contoh form GET + hasil tangkapan
├── form_post.php       # contoh form POST + hasil tangkapan
└── README.md
```

## Aturan main

- **Fokus konten**: edit halaman `.php` di folder ini, bukan partial.
- Jangan ubah `partials/header.php` dan `partials/footer.php` kecuali menambah
  satu link menu.
- Jangan pindahkan halaman ke subfolder; path `assets/style.css` akan berubah.
- Tidak ada database. Data contoh ditulis langsung di halaman.
- Tidak ada JavaScript. Semua tampilan memakai HTML + CSS + PHP dasar.

## Cara menambah halaman

1. Salin `produk.php` menjadi `pelanggan.php`.
2. Ubah `$judul = 'Pelanggan';` dan `$halaman = 'pelanggan';`.
3. Ganti isi konten di antara header dan footer.
4. Tambah satu link di `partials/header.php`:
   `<a href="pelanggan.php">Pelanggan</a>`.

## Form GET vs POST

- **GET**: data form ikut masuk ke URL setelah `?`. Cocok untuk pencarian,
  filter, dan link yang bisa dibagikan. Jangan untuk password.
- **POST**: data dikirim di body request; URL tidak berubah. Cocok untuk login,
  transaksi, dan data pribadi. Refresh setelah submit bisa meminta konfirmasi
  kirim ulang.
- Keduanya tetap perlu validasi di server. POST bukan jaminan otomatis aman.

## Checklist lomba

- [ ] Semua menu bisa diklik dan tidak ada halaman kosong.
- [ ] Tabel dan kartu tetap rapi di layar kecil.
- [ ] Tidak ada teks contoh yang tertinggal.
- [ ] Total transaksi berubah saat isi array diubah.
- [ ] Form GET menampilkan data di URL; form POST tidak.
- [ ] Semua anggota tim bisa menjelaskan isi halamannya.

## Kenapa di dalam `kasirqu/`

Supaya tetap menjadi satu living project. Folder ini tidak mengganggu
`kasirqu/index.php` dan `kasirqu/assets/style.css` karena punya partial dan
CSS sendiri.
