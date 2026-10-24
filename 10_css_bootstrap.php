<?php
// ============================================================
// Latihan 10 — CSS dengan Bootstrap
// Tujuan : melihat bagaimana "framework CSS" mempercepat kerja.
//          Bandingkan menata sendiri vs pakai class siap pakai.
// Buka   : http://localhost/smk4/kasirqu/10_css_bootstrap.php
//
// Catatan: halaman ini SENGAJA tidak memakai assets/style.css.
// Semua tampilan di sini murni dari Bootstrap, supaya kelihatan
// bedanya dengan hasil kerja tangan sendiri.
// ============================================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Latihan 10 — CSS dengan Bootstrap</title>

  <!-- Bootstrap versi lokal, supaya tetap jalan tanpa internet. -->
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">

  <!-- CSS kecil buatan sendiri, untuk pembanding. -->
  <style>
    .kotak-manual {
      background: #ffffff;
      border: 1px solid #d0d7e2;
      border-radius: 8px;
      padding: 20px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, .08);
    }
    .kotak-manual h5 { margin: 0 0 8px; font-size: 18px; color: #1F3864; }
    .kotak-manual p { margin: 0 0 14px; color: #4a5361; }

    .btn-manual {
      display: inline-block;
      background: #0d6efd;
      color: #ffffff;
      border: 0;
      border-radius: 6px;
      padding: 6px 12px;
      font-size: 14px;
      text-decoration: none;
      cursor: pointer;
    }
    .btn-manual:hover { background: #0b5ed7; color: #ffffff; }

    pre {
      background: #141E30;
      color: #E8EFF7;
      padding: 14px 16px;
      border-radius: 10px;
      overflow-x: auto;
      font-size: 13.5px;
      line-height: 1.5;
    }
    code { color: #B35300; background: #F1F4F9; padding: 1px 6px; border-radius: 5px; }
    pre code { color: #E8EFF7; background: transparent; padding: 0; }
  </style>
</head>
<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-dark" style="background:#1F3864;">
    <div class="container">
      <a class="navbar-brand fw-bold" href="index.php">
        KasirQu <small class="text-warning">/ Toko Maju Jaya</small>
      </a>
      <a class="btn btn-sm btn-outline-light" href="index.php">&larr; Menu Latihan</a>
    </div>
  </nav>

  <main class="container py-4">

    <span class="badge text-bg-warning mb-2">Latihan 10</span>
    <h1 class="text-primary-emphasis">CSS dengan Bootstrap</h1>
    <p class="lead text-secondary">
      Kalau Latihan 9 kita menulis CSS sendiri, sekarang kita pakai
      <strong>kumpulan CSS siap pakai</strong>. Tinggal hafal nama class-nya.
    </p>

    <!-- ================================================== -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h2 class="h4 text-primary-emphasis">1. Bootstrap Itu Apa?</h2>
        <p>
          Bootstrap adalah <strong>framework CSS</strong>: kumpulan aturan CSS (dan sedikit JavaScript)
          yang sudah ditulis orang lain. Kita tinggal memakai <strong>nama class</strong> bawaannya.
        </p>
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr><th>Kalau bikin sendiri</th><th>Kalau pakai Bootstrap</th></tr>
          </thead>
          <tbody>
            <tr><td>Tulis <code>.kotak-manual { ... }</code></td><td>Tulis class <code>card</code> di HTML</td></tr>
            <tr><td>Atur padding, border, shadow manual</td><td>Sudah otomatis rapi</td></tr>
            <tr><td>Perlu waktu lama</td><td>Selesai dalam hitungan detik</td></tr>
            <tr><td>Bebas sesuai selera</td><td>Ikut gaya khas Bootstrap</td></tr>
          </tbody>
        </table>
        <p class="mb-0">
          <span class="badge text-bg-info">Intinya</span>
          Bootstrap <strong>tidak menghapus CSS</strong>. Bootstrap <strong>adalah</strong> CSS yang sudah dibungkus jadi class.
        </p>
      </div>
    </div>

    <!-- ================================================== -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h2 class="h4 text-primary-emphasis">2. Cara Memasang Bootstrap</h2>

        <p class="fw-semibold mb-1">A) Dari internet (CDN)</p>
        <p class="text-secondary">Paling cepat, tapi butuh koneksi internet.</p>
<pre><code>&lt;link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"&gt;</code></pre>

        <p class="fw-semibold mb-1">B) Dari file lokal (dipakai halaman ini)</p>
        <p class="text-secondary">File diunduh dulu, lalu dipanggil seperti CSS biasa. Aman walau internet mati.</p>
<pre><code>&lt;link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css"&gt;</code></pre>

        <div class="alert alert-warning mb-0">
          <strong>Urutan itu penting.</strong> Kalau kamu mau CSS sendiri menimpa Bootstrap,
          letakkan <code>&lt;link&gt;</code> Bootstrap <strong>di atas</strong> CSS milikmu.
          Yang ditulis terakhir biasanya menang.
        </div>
      </div>
    </div>

    <!-- ================================================== -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h2 class="h4 text-primary-emphasis">3. Bandingkan: Manual vs Bootstrap</h2>
        <p class="text-secondary">Dua kotak di bawah sebenarnya isinya sama. Perhatikan cara membuatnya.</p>

        <div class="row g-4">
          <div class="col-md-6">
            <p class="fw-semibold mb-2">Dibuat manual (CSS sendiri)</p>
            <div class="kotak-manual">
              <h5>Produk P01</h5>
              <p>Mie Instan &mdash; Rp 3.500</p>
              <a href="#" class="btn-manual">Beli</a>
            </div>
<pre class="mt-3 mb-0"><code>&lt;div class="kotak-manual"&gt;
  &lt;h5&gt;Produk P01&lt;/h5&gt;
  &lt;p&gt;Mie Instan - Rp 3.500&lt;/p&gt;
  &lt;a class="btn-manual"&gt;Beli&lt;/a&gt;
&lt;/div&gt;</code></pre>
          </div>

          <div class="col-md-6">
            <p class="fw-semibold mb-2">Dibuat Bootstrap</p>
            <div class="card shadow-sm">
              <div class="card-body">
                <h5 class="card-title">Produk P01</h5>
                <p class="card-text text-secondary mb-3">Mie Instan &mdash; Rp 3.500</p>
                <a href="#" class="btn btn-primary btn-sm">Beli</a>
              </div>
            </div>
<pre class="mt-3 mb-0"><code>&lt;div class="card shadow-sm"&gt;
  &lt;div class="card-body"&gt;
    &lt;h5 class="card-title"&gt;Produk P01&lt;/h5&gt;
    &lt;p class="card-text"&gt;Mie Instan - Rp 3.500&lt;/p&gt;
    &lt;a class="btn btn-primary btn-sm"&gt;Beli&lt;/a&gt;
  &lt;/div&gt;
&lt;/div&gt;</code></pre>
          </div>
        </div>

        <div class="alert alert-success mt-4 mb-0">
          Hasilnya hampir sama, tapi versi Bootstrap <strong>tidak perlu menulis CSS sama sekali</strong>.
        </div>
      </div>
    </div>

    <!-- ================================================== -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h2 class="h4 text-primary-emphasis">4. Grid: Mengatur Posisi Kolom</h2>
        <p>
          Bootstrap membagi lebar layar menjadi <strong>12 kolom</strong>. Kita tinggal bilang
          butuh berapa kolom.
        </p>
<pre><code>&lt;div class="row"&gt;
  &lt;div class="col-md-4"&gt;Produk 1&lt;/div&gt;
  &lt;div class="col-md-4"&gt;Produk 2&lt;/div&gt;
  &lt;div class="col-md-4"&gt;Produk 3&lt;/div&gt;
&lt;/div&gt;</code></pre>
        <p class="text-secondary">
          Artinya: di layar sedang/besar (<code>md</code> ke atas), tiap kotak memakai 4 kolom,
          jadi muat 3 kotak sejajar. Kalau layarnya kecil, otomatis turun jadi satu kolom.
        </p>

        <div class="row g-3">
          <div class="col-md-4">
            <div class="border rounded p-3 bg-white text-center">Produk 1</div>
          </div>
          <div class="col-md-4">
            <div class="border rounded p-3 bg-white text-center">Produk 2</div>
          </div>
          <div class="col-md-4">
            <div class="border rounded p-3 bg-white text-center">Produk 3</div>
          </div>
        </div>
      </div>
    </div>

    <!-- ================================================== -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h2 class="h4 text-primary-emphasis">5. Komponen Siap Pakai</h2>
        <p class="text-secondary">Ini contoh komponen yang paling sering dipakai.</p>

        <p class="fw-semibold mb-2">Tombol</p>
        <div class="mb-4">
          <button class="btn btn-primary">Simpan</button>
          <button class="btn btn-success">Bayar</button>
          <button class="btn btn-warning">Ubah</button>
          <button class="btn btn-danger">Hapus</button>
          <button class="btn btn-outline-secondary">Batal</button>
        </div>
<pre class="mb-4"><code>&lt;button class="btn btn-primary"&gt;Simpan&lt;/button&gt;
&lt;button class="btn btn-success"&gt;Bayar&lt;/button&gt;
&lt;button class="btn btn-danger"&gt;Hapus&lt;/button&gt;</code></pre>

        <p class="fw-semibold mb-2">Pesan (alert) dan label (badge)</p>
        <div class="alert alert-success">Transaksi berhasil disimpan.</div>
        <div class="alert alert-danger">Stok habis, tidak bisa dijual.</div>
        <p>
          <span class="badge text-bg-primary">P01</span>
          <span class="badge text-bg-success">Tersedia</span>
          <span class="badge text-bg-danger">Habis</span>
        </p>
<pre class="mb-4"><code>&lt;div class="alert alert-success"&gt;Transaksi berhasil disimpan.&lt;/div&gt;
&lt;span class="badge text-bg-danger"&gt;Habis&lt;/span&gt;</code></pre>

        <p class="fw-semibold mb-2">Tabel</p>
        <table class="table table-striped table-hover align-middle mb-2">
          <thead class="table-dark">
            <tr><th>Kode</th><th>Produk</th><th>Harga</th></tr>
          </thead>
          <tbody>
            <tr><td>P01</td><td>Mie Instan</td><td>Rp 3.500</td></tr>
            <tr><td>P02</td><td>Teh Botol</td><td>Rp 4.000</td></tr>
            <tr><td>P03</td><td>Roti</td><td>Rp 5.000</td></tr>
          </tbody>
        </table>
<pre class="mb-0"><code>&lt;table class="table table-striped table-hover"&gt; ... &lt;/table&gt;</code></pre>
      </div>
    </div>

    <!-- ================================================== -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h2 class="h4 text-primary-emphasis">6. Hubungannya dengan <code>.</code> dan <code>#</code></h2>
        <p>
          Masih ingat Latihan 9? Bootstrap hampir seluruhnya memakai <strong>class</strong>,
          jadi hampir semua selector-nya bertanda <strong>titik</strong>.
        </p>
        <table class="table table-bordered align-middle">
          <thead class="table-light">
            <tr><th>Class Bootstrap</th><th>Artinya</th></tr>
          </thead>
          <tbody>
            <tr><td><code>.btn</code></td><td>Bentuk dasar tombol</td></tr>
            <tr><td><code>.btn-primary</code></td><td>Tombol biru utama</td></tr>
            <tr><td><code>.card</code></td><td>Kotak berbingkai</td></tr>
            <tr><td><code>.container</code></td><td>Wadah yang lebarnya dibatasi</td></tr>
            <tr><td><code>.row</code></td><td>Baris untuk kolom</td></tr>
            <tr><td><code>.text-danger</code></td><td>Teks warna merah</td></tr>
          </tbody>
        </table>
        <p class="mb-0">
          <span class="badge text-bg-warning">Ingat</span>
          Satu elemen boleh punya <strong>banyak class sekaligus</strong>, dipisah spasi:
          <code>class="btn btn-danger btn-sm"</code>. Sedangkan <code>id</code> tetap hanya satu per halaman.
        </p>
      </div>
    </div>

    <!-- ================================================== -->
    <div class="card shadow-sm mb-4">
      <div class="card-body">
        <h2 class="h4 text-primary-emphasis">7. Sedikit JavaScript: Modal</h2>
        <p>
          Bootstrap juga punya komponen yang butuh JavaScript, contohnya <strong>modal</strong>
          (kotak dialog). Itu sebabnya ada file <code>bootstrap.bundle.min.js</code>.
        </p>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalKasir">
          Buka Modal Kasir
        </button>
<pre class="mt-3 mb-0"><code>&lt;button class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#modalKasir"&gt;
  Buka Modal Kasir
&lt;/button&gt;

&lt;div class="modal fade" id="modalKasir"&gt; ... &lt;/div&gt;

&lt;script src="assets/bootstrap/js/bootstrap.bundle.min.js"&gt;&lt;/script&gt;</code></pre>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalKasir" tabindex="-1" aria-labelledby="modalKasirLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalKasirLabel">Konfirmasi Pembayaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            Total belanja: <strong>Rp 12.500</strong><br>
            Uang diterima: <strong>Rp 20.000</strong><br>
            Kembalian: <strong class="text-success">Rp 7.500</strong>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="button" class="btn btn-success" data-bs-dismiss="modal">Bayar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- ================================================== -->
    <div class="card border-warning mb-4">
      <div class="card-body">
        <h3 class="h5 text-warning-emphasis">// TUGAS</h3>
        <ol class="mb-0">
          <li>Ubah tombol <strong>Bayar</strong> menjadi warna hijau memakai class Bootstrap. Class apa yang dipakai?</li>
          <li>Tambahkan satu produk baru ke tabel, ikuti pola baris yang sudah ada.</li>
          <li>Ganti <code>col-md-4</code> menjadi <code>col-md-6</code> pada grid produk. Apa yang terjadi?</li>
          <li>Tambah satu <code>&lt;div class="alert alert-info"&gt;</code> berisi pengumuman toko.</li>
          <li>Buka modal, lalu ganti angka kembaliannya. Simpan dan coba lagi.</li>
          <li>Tantangan: buat halaman baru <code>11_toko_bootstrap.php</code> berisi daftar produk memakai <code>row</code>, <code>col-md-4</code>, dan <code>card</code>.</li>
        </ol>
      </div>
    </div>

    <div class="card border-secondary mb-0">
      <div class="card-body">
        <h3 class="h5 text-secondary-emphasis">// Kunci Sukses</h3>
        <ul class="mb-0">
          <li>Bootstrap dipakai lewat <strong>nama class</strong>, bukan dengan menyalin CSS-nya.</li>
          <li>Jangan hafal semua class. Cukup buka dokumentasi dan cari yang dibutuhkan.</li>
          <li>Kalau tampilan tidak berubah, cek urutan <code>&lt;link&gt;</code> dan tekan <strong>Ctrl + F5</strong>.</li>
          <li>Tidak semua tempat ada internet. Karena itu file Bootstrap disimpan lokal di <code>assets/bootstrap/</code>.</li>
        </ul>
      </div>
    </div>

  </main>

  <footer class="text-center text-secondary small py-4">
    KasirQu &mdash; Pertemuan 9 &middot; Latihan 10
  </footer>

  <script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
