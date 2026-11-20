<?php
// ============================================================
// kasirqu/crud/panduan_session.php
// Panduan belajar: session, login, role, logout.
// Halaman ini tidak memakai database, jadi tetap bisa dibuka
// walaupun MySQL sedang mati.
// ============================================================
$judul   = 'Panduan Session';
$halaman = 'panduan_session';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">Panduan &middot; Pertemuan 17</p>
<h1>Session: Siapa yang Sedang Bekerja?</h1>
<p class="lead">
  Sampai Pertemuan 16, aplikasi kita tidak tahu siapa yang membuka halaman.
  Hari ini kita beri aplikasi sebuah &ldquo;ingatan&rdquo;: session.
</p>

<div class="card">
  <h2>1. Masalah yang Mau Diselesaikan</h2>
  <p>
    Bayangkan toko punya dua pekerja: <strong>pemilik</strong> dan
    <strong>kasir</strong>. Keduanya membuka aplikasi yang sama.
  </p>
  <ul class="steps">
    <li>Pemilik boleh melihat laporan omzet.</li>
    <li>Kasir hanya boleh membuat transaksi.</li>
    <li>Kalau kasir bisa membuka laporan, gaji semua orang bisa dilihat.</li>
  </ul>
  <p style="margin-bottom: 0;">
    Supaya aplikasi bisa membedakan keduanya, aplikasi harus mengingat
    <em>siapa</em> yang login. Alatnya bernama <strong>session</strong>.
  </p>
</div>

<div class="card">
  <h2>2. Session Itu Seperti Gelang Masuk</h2>
  <p>
    Saat kamu masuk ke kolam renang, kamu diberi gelang. Selama gelang itu
    masih di tanganmu, kamu boleh keluar-masuk area. Penjaga tidak perlu
    bertanya nama kamu setiap kali.
  </p>
  <table>
    <thead>
      <tr>
        <th>Di kolam renang</th>
        <th>Di aplikasi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Loket pendaftaran</td>
        <td><code>login.php</code></td>
      </tr>
      <tr>
        <td>Gelang masuk</td>
        <td><code>$_SESSION</code></td>
      </tr>
      <tr>
        <td>Penjaga pintu</td>
        <td><code>config/auth.php</code></td>
      </tr>
      <tr>
        <td>Keluar dan kembalikan gelang</td>
        <td><code>logout.php</code></td>
      </tr>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>3. Alur Login (Empat Langkah)</h2>
  <ol class="steps">
    <li>
      <strong>Form login</strong> mengirim username + password lewat
      <code>POST</code> ke <code>proses_login.php</code>.
    </li>
    <li>
      <strong>Cari pengguna</strong> dengan prepared statement:
      <code>SELECT ... FROM pengguna WHERE username = ?</code>.
    </li>
    <li>
      <strong>Cek password</strong> memakai <code>password_verify()</code>.
      Password asli tidak pernah disimpan, hanya hash-nya.
    </li>
    <li>
      <strong>Simpan identitas</strong> ke session, lalu
      <code>session_regenerate_id(true)</code>, lalu pindah ke
      <code>index.php</code>.
    </li>
  </ol>

  <pre><code>$_SESSION['kasirqu'] = [
    'id_pengguna' =&gt; 1,
    'nama'        =&gt; 'Pemilik Toko',
    'role'        =&gt; 'admin',
];</code></pre>

  <p class="muted" style="margin-bottom: 0;">
    Kenapa dibungkus kunci <code>kasirqu</code>? Karena KasirQu dan KasirSMK
    berjalan di domain yang sama. Kalau dua aplikasi menulis
    <code>$_SESSION['nama']</code>, isinya bisa saling menimpa.
  </p>
</div>

<div class="card">
  <h2>4. Membuat Hash Sendiri</h2>
  <p>
    Kolom <code>password</code> tidak berisi <code>admin123</code>, melainkan
    teks panjang diawali <code>$2y$</code>. Teks itu dibuat seperti ini:
  </p>
  <pre><code>// lewat terminal
php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"

// lewat PHP
$hash = password_hash('admin123', PASSWORD_DEFAULT);</code></pre>
  <p>
    Cara paling mudah: buka <a href="buat_hash.php">buat_hash.php</a>, ketik
    password, klik <strong>Buat Hash</strong>, lalu salin hasilnya ke kolom
    <code>password</code> di <code>setup_pengguna.sql</code> atau lewat
    <code>UPDATE pengguna SET password = '...' WHERE username = 'admin';</code>.
  </p>
  <p style="margin-bottom: 0;">
    Kalau perintah yang sama dijalankan dua kali, hasilnya <strong>beda</strong>.
    Itu normal &mdash; ada <em>salt</em> acak di dalamnya. Yang penting tetap
    bisa diverifikasi dengan <code>password_verify()</code>.
  </p>
</div>

<div class="card">
  <h2>5. Penjaga Halaman</h2>
  <p>
    Satu file kecil dipanggil di baris paling atas setiap halaman bisnis.
  </p>
  <pre><code>require_once __DIR__ . '/config/auth.php';
wajib_role('admin');   // hanya untuk halaman laporan</code></pre>
  <p>
    Isi <code>config/auth.php</code> melakukan dua hal:
  </p>
  <ul class="steps" style="margin-bottom: 0;">
    <li>Kalau belum login &rarr; lempar ke <code>login.php</code>.</li>
    <li>Kalau role tidak cocok &rarr; kembalikan ke <code>index.php</code>
        dengan pesan.</li>
  </ul>
</div>

<div class="card">
  <h2>6. Menyembunyikan Menu Bukan Mengunci Halaman</h2>
  <div class="alert error">
    Menu yang hilang hanya menyembunyikan pintu, bukan mengunci ruangan.
    Orang masih bisa mengetik <code>laporan_penjualan.php</code> di address bar.
  </div>
  <p style="margin-bottom: 0;">
    Karena itu kita butuh dua lapis:
  </p>
  <ol class="steps" style="margin-bottom: 0;">
    <li><strong>Lapis tampilan</strong>: menu Laporan hanya dirender kalau role admin.</li>
    <li><strong>Lapis server</strong>: <code>wajib_role('admin')</code> di file laporannya.</li>
  </ol>
</div>

<div class="card">
  <h2>7. Logout yang Benar</h2>
  <p>
    Logout mengubah keadaan, jadi harus <code>POST</code>, bukan link
    <code>&lt;a href&gt;</code>. Isinya:
  </p>
  <pre><code>unset($_SESSION['kasirqu']);
session_regenerate_id(true);
header('Location: login.php?status=logout');</code></pre>
  <p style="margin-bottom: 0;">
    Kita <strong>tidak</strong> memakai <code>session_destroy()</code>, karena
    itu menghapus semua session di domain yang sama &mdash; termasuk login
    aplikasi satunya.
  </p>
</div>

<div class="card">
  <h2>8. Uji Sendiri (5 Menit)</h2>
  <ol class="steps" style="margin-bottom: 0;">
    <li>Buka <a href="login.php">login.php</a>, masuk sebagai <code>admin</code> / <code>admin123</code>.</li>
    <li>Menu Laporan muncul. Buka <a href="laporan_penjualan.php">laporan penjualan</a>.</li>
    <li>Logout. Menu Laporan hilang.</li>
    <li>Login sebagai <code>kasir1</code> / <code>kasir123</code>.</li>
    <li>Ketik <code>laporan_penjualan.php</code> di address bar &rarr; ditolak.</li>
  </ol>
</div>

<div class="card">
  <h2>9. Kesalahan yang Sering Terjadi</h2>
  <table>
    <thead>
      <tr>
        <th>Gejala</th>
        <th>Penyebab</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>&ldquo;headers already sent&rdquo;</td>
        <td><code>session_start()</code> dipanggil setelah ada spasi atau HTML.</td>
      </tr>
      <tr>
        <td>Login selalu gagal</td>
        <td>Password di database masih teks asli. Buat hash lewat <a href="buat_hash.php">buat_hash.php</a>, lalu tempel ke SQL.</td>
      </tr>
      <tr>
        <td>Session hilang saat pindah halaman</td>
        <td>Lupa <code>session_start()</code> di halaman tujuan.</td>
      </tr>
      <tr>
        <td>Kasir tetap bisa buka laporan</td>
        <td>Hanya menyembunyikan menu, belum memasang <code>wajib_role()</code>.</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="actions-top">
  <a class="btn" href="login.php">Coba Login</a>
  <a class="btn btn-secondary" href="buat_hash.php">Buat Hash Password</a>
  <a class="btn btn-secondary" href="panduan_laporan.php">Lanjut: Panduan Laporan</a>
  <a class="btn btn-secondary" href="panduan.php">Kembali ke Panduan</a>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
