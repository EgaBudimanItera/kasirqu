<?php
$judul = 'Tes Koneksi Database';
$halaman = 'koneksi';
require __DIR__ . '/../config/database.php';
require __DIR__ . '/partials/header.php';

$versiServer = mysqli_get_server_info($koneksi);
$charset = mysqli_character_set_name($koneksi);

$hasilTabel = mysqli_query($koneksi, 'SHOW TABLES');
$daftarTabel = [];
while ($baris = mysqli_fetch_row($hasilTabel)) {
    $daftarTabel[] = $baris[0];
}
$adaProduk = in_array('produk', $daftarTabel, true);
$jumlahProduk = 0;
if ($adaProduk) {
    $hasilHitung = mysqli_query($koneksi, 'SELECT COUNT(*) AS jumlah FROM produk');
    $barisHitung = mysqli_fetch_assoc($hasilHitung);
    $jumlahProduk = (int) $barisHitung['jumlah'];
}
?>

<p class="tag">Pertemuan 14 &middot; Koneksi Database</p>
<h1>Koneksi PHP ke MySQL</h1>
<p class="lead">
  Halaman ini membuktikan bahwa PHP sudah terhubung ke MySQL. Koneksi diatur
  sekali di <code>config/database.php</code>, lalu dipakai oleh semua halaman CRUD.
</p>

<div class="alert ok">
  <strong>Koneksi berhasil.</strong> PHP bisa membaca database
  <code><?= htmlspecialchars(DB_NAME) ?></code>.
</div>

<div class="card">
  <h2>Informasi Koneksi</h2>
  <table>
    <thead>
      <tr>
        <th>Pengaturan</th>
        <th>Nilai</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Host</td>
        <td><code><?= htmlspecialchars(DB_HOST) ?></code></td>
      </tr>
      <tr>
        <td>User</td>
        <td><code><?= htmlspecialchars(DB_USER) ?></code></td>
      </tr>
      <tr>
        <td>Database</td>
        <td><code><?= htmlspecialchars(DB_NAME) ?></code></td>
      </tr>
      <tr>
        <td>Versi server MySQL</td>
        <td><?= htmlspecialchars($versiServer) ?></td>
      </tr>
      <tr>
        <td>Charset</td>
        <td><code><?= htmlspecialchars($charset) ?></code></td>
      </tr>
      <tr>
        <td>Tabel <code>produk</code></td>
        <td>
          <?php if ($adaProduk): ?>
            <span class="pill ok">Ada</span>
            &middot; <?= $jumlahProduk ?> baris data
          <?php else: ?>
            <span class="pill no">Belum ada</span>
          <?php endif; ?>
        </td>
      </tr>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>Daftar Tabel di Database</h2>
  <?php if (count($daftarTabel) === 0): ?>
    <p class="muted">Database ini belum punya tabel. Impor <code>setup_produk.sql</code> terlebih dahulu.</p>
  <?php else: ?>
    <ul class="steps">
      <?php foreach ($daftarTabel as $tabel): ?>
        <li><code><?= htmlspecialchars($tabel) ?></code></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<?php if (!$adaProduk): ?>
  <div class="alert error">
    Tabel <code>produk</code> belum ada. Buka phpMyAdmin, lalu impor
    <code>kasirqu/crud/setup_produk.sql</code> atau
    <code>pertemuan-07/bangun_database_penjualan.sql</code>.
  </div>
<?php endif; ?>

<div class="card">
  <h2>Arti 4 Parameter Koneksi</h2>
  <table>
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Contoh</th>
        <th>Artinya</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>host</code></td>
        <td><code>localhost</code></td>
        <td>Alamat komputer tempat MySQL berjalan.</td>
      </tr>
      <tr>
        <td><code>user</code></td>
        <td><code>root</code></td>
        <td>Nama pengguna MySQL.</td>
      </tr>
      <tr>
        <td><code>password</code></td>
        <td><code>(kosong)</code></td>
        <td>Password user. Di Laragon/XAMPP biasanya kosong.</td>
      </tr>
      <tr>
        <td><code>database</code></td>
        <td><code>db_penjualan</code></td>
        <td>Nama database yang ingin dibuka.</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>Kalau Koneksi Gagal</h2>
  <table>
    <thead>
      <tr>
        <th>Pesan Error</th>
        <th>Penyebab &amp; Solusi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>Access denied for user</code></td>
        <td>User/password salah. Cek <code>DB_USER</code> dan <code>DB_PASS</code>.</td>
      </tr>
      <tr>
        <td><code>Unknown database</code></td>
        <td>Database belum dibuat. Impor file SQL terlebih dahulu.</td>
      </tr>
      <tr>
        <td><code>Connection refused</code></td>
        <td>MySQL belum menyala. Nyalakan lewat Laragon atau XAMPP.</td>
      </tr>
      <tr>
        <td><code>Call to undefined function mysqli_connect</code></td>
        <td>Ekstensi <code>mysqli</code> belum aktif di PHP.</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="actions-top">
  <a class="btn" href="index.php">Lihat Data Produk</a>
  <a class="btn btn-secondary" href="tambah.php">Tambah Produk</a>
  <a class="btn btn-secondary" href="panduan_koneksi.php">Baca Panduan Koneksi</a>
</div>

<div class="task">
  <h3>// CATATAN</h3>
  <p>
    Jangan menulis password asli di file yang diunggah ke internet. Untuk latihan
    lokal, user <code>root</code> tanpa password masih aman. Saat aplikasi online,
    buat user MySQL khusus dan simpan password di konfigurasi server.
  </p>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
