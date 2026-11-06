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
    <span class="pill">P13</span>
    <h2>Form &amp; Upload Foto</h2>
    <p>
      Panduan upload foto/image dari form: <code>enctype</code>,
      <code>$_FILES</code>, validasi gambar, ukuran file, dan keamanan.
    </p>
    <a class="btn" href="../template/form_upload.php">Baca Panduan Upload</a>
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
    <li>Praktikkan langsung di halaman Produk dan Tambah.</li>
  </ol>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
