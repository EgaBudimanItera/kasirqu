<?php
$judul = 'Panduan KasirQu';
$halaman = 'panduan';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">Panduan &middot; KasirQu</p>
<h1>Panduan Belajar KasirQu</h1>
<p class="lead">
  Pilih materi yang ingin dibaca. Semua panduan bisa dipakai tanpa slide
  presentasi dan tetap terbuka walaupun database sedang mati.
</p>

<div class="grid">
  <div class="card">
    <span class="pill">P12</span>
    <h2>Partial Header/Footer</h2>
    <p>
      Kenali layout bersama: isi <code>partials/header.php</code> dan
      <code>partials/footer.php</code>, cara memanggilnya, peta folder, dan
      aturan path CSS.
    </p>
    <a class="btn" href="../template/panduan_partial.php">Baca Panduan Partial</a>
  </div>

  <div class="card">
    <span class="pill">P14</span>
    <h2>Koneksi Database</h2>
    <p>
      Pahami 4 parameter koneksi, isi <code>config/database.php</code>, cara
      <code>require</code> dengan <code>__DIR__</code>, dan error umum.
    </p>
    <a class="btn" href="panduan_koneksi.php">Baca Panduan Koneksi</a>
  </div>

  <div class="card">
    <span class="pill">P14</span>
    <h2>CRUD Produk</h2>
    <p>
      Panduan tambah, edit, dan hapus produk: validasi, prepared statement,
      tipe <code>bind_param</code>, redirect, dan kesalahan umum.
    </p>
    <a class="btn" href="panduan_crud.php">Baca Panduan CRUD</a>
  </div>

  <div class="card">
    <span class="pill">P11</span>
    <h2>Slide Update &amp; Delete Produk</h2>
    <p>
      Slide Pertemuan 11 dalam browser: beda folder <code>crud/</code> dan
      <code>crud_latihan/</code>, anatomi <code>edit.php</code> &amp;
      <code>hapus.php</code>, WHERE, validasi, dan pengaman hapus. Catatan
      pelatih ada di tombol <strong>Catatan</strong>.
    </p>
    <a class="btn" href="presentasi_p11.php">Buka Slide P11</a>
  </div>

  <div class="card">
    <span class="pill">P13</span>
    <h2>Form &amp; Upload Foto</h2>
    <p>
      Panduan upload foto/image dari form: <code>enctype</code>,
      <code>$_FILES</code>, validasi gambar, ukuran file, dan keamanan.
    </p>
    <a class="btn" href="../template/form_upload.php">Baca Panduan Upload</a>
  </div>

  <div class="card">
    <span class="pill">P11</span>
    <h2>Slide Halaman Pembelian</h2>
    <p>
      Slide Pertemuan 11 bagian pembelian: algoritma empat halaman
      (indeks, tambah, detail, batalkan) lengkap dengan contoh kode. Catatan
      pelatih ada di tombol <strong>Catatan</strong>.
    </p>
    <a class="btn" href="presentasi_p11_pembelian.php">Buka Slide Pembelian</a>
  </div>

  <div class="card">
    <span class="pill">P16</span>
    <h2>Slide Alur Transaksi</h2>
    <p>
      Slide Pertemuan 16 dalam browser: urutan membuat file, rantai
      <code>require</code>, isi form per bagian, selectbox master, hitungan
      JavaScript, validasi POST, dan tabel tujuan. Catatan pelatih ada di
      tombol <strong>Catatan</strong>.
    </p>
    <a class="btn" href="presentasi.php">Buka Slide P16</a>
  </div>

  <div class="card">
    <span class="pill">P16</span>
    <h2>Transaksi Pembelian</h2>
    <p>
      Alur kepala nota, detail, dan kartu stok: nomor nota otomatis,
      transaksi database, dan stok masuk.
    </p>
    <a class="btn" href="panduan_transaksi.php">Baca Panduan Transaksi</a>
  </div>

  <div class="card">
    <span class="pill">P17</span>
    <h2>Session &amp; Login</h2>
    <p>
      Kenapa aplikasi perlu mengingat pengguna, cara kerja
      <code>$_SESSION</code>, penjaga halaman, role admin/kasir, dan logout
      yang benar.
    </p>
    <a class="btn" href="panduan_session.php">Baca Panduan Session</a>
  </div>

  <div class="card">
    <span class="pill">P17</span>
    <h2>Laporan</h2>
    <p>
      Lima bagian laporan, query agregat dengan <code>GROUP BY</code> dan
      <code>BETWEEN</code>, plus petunjuk tiga tugas: pembelian, stok, laba.
    </p>
    <a class="btn" href="panduan_laporan.php">Baca Panduan Laporan</a>
  </div>

  <div class="card">
    <span class="pill">P17</span>
    <h2>Contoh Laporan Penjualan</h2>
    <p>
      Halaman jadi yang bisa langsung dibuka: filter tanggal, kartu ringkasan,
      tabel nota, dan tombol cetak. Hanya untuk admin.
    </p>
    <a class="btn" href="laporan_penjualan.php">Buka Laporan</a>
  </div>
</div>

<div class="actions-top">
  <a class="btn btn-secondary" href="index.php">Ke Daftar Produk</a>
  <a class="btn btn-secondary" href="../template/index.php">Ke Template KasirQu</a>
</div>

<div class="task">
  <h3>// URUTAN BELAJAR</h3>
  <ol>
    <li>Baca panduan partial agar paham layout bersama.</li>
    <li>Baca panduan upload foto agar paham form file dan <code>$_FILES</code>.</li>
    <li>Baca panduan koneksi agar paham cara PHP masuk ke MySQL.</li>
    <li>Baca panduan CRUD agar paham alur tambah, edit, dan hapus data.</li>
    <li>Baca panduan transaksi agar paham alur pembelian.</li>
    <li>Baca panduan session agar paham login dan penjaga halaman.</li>
    <li>Baca panduan laporan agar paham query agregat.</li>
    <li>Praktikkan langsung di halaman Produk, Tambah, Transaksi, dan Laporan.</li>
  </ol>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
