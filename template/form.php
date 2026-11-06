<?php
$judul = 'Form, GET & POST';
$halaman = 'form';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">Materi P13</p>
<h1>Form, GET, dan POST</h1>
<p class="lead">Form adalah cara pengguna mengirim data. Metode GET dan POST menentukan bagaimana data itu dikirim dan di mana data itu muncul.</p>

<div class="card">
  <h2>Baru: pengenalan form</h2>
  <p>Belum pernah membuat form? Mulai dari tag dasar, lalu kenali komponen dan cara menampilkan output.</p>
  <ul class="steps">
    <li><a href="form_pengenalan.php">1. Tag dan anatomi form</a></li>
    <li><a href="form_komponen.php">2. Komponen input: teks, pilihan, area, tombol</a></li>
    <li><a href="form_output.php">3. Output form dan validasi</a></li>
    <li><a href="form_upload.php">4. Upload foto/image</a></li>
    <li><a href="form_latihan.php">5. Latihan mandiri</a></li>
  </ul>
  <p><a class="btn" href="form_pengenalan.php">Mulai Pengenalan Form</a></p>
</div>

<div class="card">
  <h2>Mulai dari dasar: form + tabel</h2>
  <p>Sebelum membandingkan GET dan POST, lihat satu halaman yang menggabungkan form sebagai input dan tabel sebagai output.</p>
  <a class="btn" href="form_dasar.php">Buka Form + Tabel Dasar</a>
</div>

<div class="card">
  <h2>Lanjutan: simpan ke database</h2>
  <p>Setelah form POST bisa menangkap data, langkah berikutnya adalah menyimpannya ke MySQL. Buka modul koneksi database dan CRUD produk.</p>
  <a class="btn" href="../crud/tambah.php">Buka Koneksi Database &amp; CRUD</a>
</div>

<div class="grid">
  <div class="card">
    <span class="pill">GET</span>
    <h2>Data ikut di URL</h2>
    <p>Nilai form ditempel ke alamat setelah tanda <code>?</code>. Cocok untuk pencarian dan filter.</p>
    <pre>form_get.php?nama=Rina&amp;produk=Beras&amp;jumlah=2</pre>
    <a class="btn" href="form_get.php">Coba Form GET</a>
  </div>
  <div class="card">
    <span class="pill ok">POST</span>
    <h2>Data di balik layar</h2>
    <p>Nilai form dikirim di body request. Alamat halaman tidak berubah. Cocok untuk data sensitif.</p>
    <pre>form_post.php</pre>
    <a class="btn" href="form_post.php">Coba Form POST</a>
  </div>
</div>

<div class="card">
  <h2>Apa yang terjadi saat tombol diklik?</h2>
  <table>
    <thead>
      <tr>
        <th>Tahap</th>
        <th>GET</th>
        <th>POST</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1. Browser membaca form</td>
        <td>Mengambil <code>name</code> + nilai input</td>
        <td>Mengambil <code>name</code> + nilai input</td>
      </tr>
      <tr>
        <td>2. Data dikirim</td>
        <td>Ditempel ke URL sebagai query string</td>
        <td>Dimasukkan ke body request</td>
      </tr>
      <tr>
        <td>3. PHP membaca</td>
        <td><code>$_GET['nama']</code></td>
        <td><code>$_POST['nama']</code></td>
      </tr>
      <tr>
        <td>4. Alamat browser</td>
        <td>Berubah, data terlihat</td>
        <td>Tetap, data tidak terlihat</td>
      </tr>
      <tr>
        <td>5. Refresh</td>
        <td>Aman, data masih di URL</td>
        <td>Bisa muncul konfirmasi kirim ulang</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>Kapan memakai apa?</h2>
  <table>
    <thead>
      <tr>
        <th>Kebutuhan</th>
        <th>Pilihan</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Pencarian produk, filter kategori</td>
        <td><span class="pill">GET</span></td>
      </tr>
      <tr>
        <td>Login, password, data pribadi</td>
        <td><span class="pill ok">POST</span></td>
      </tr>
      <tr>
        <td>Simpan transaksi atau ubah data</td>
        <td><span class="pill ok">POST</span></td>
      </tr>
      <tr>
        <td>Link yang bisa dibagikan</td>
        <td><span class="pill">GET</span></td>
      </tr>
    </tbody>
  </table>
</div>

<div class="task">
  <h3>// TUGAS KONTEN</h3>
  <ol>
    <li>Buka Form GET, isi data, lalu perhatikan URL-nya.</li>
    <li>Buka Form POST, isi data, lalu perhatikan URL-nya.</li>
    <li>Catat 2 perbedaan yang kamu lihat.</li>
  </ol>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
