<?php
// ============================================================
// kasirqu/crud/transaksi.php
// Hub latihan transaksi pembelian (barang masuk).
//
// Catatan: modul penjualan sengaja belum dipasang di aplikasi agar
// siswa membangun sendiri. Polanya sama, hanya arah stok berbeda.
// ============================================================
require __DIR__ . '/../config/database.php';
require __DIR__ . '/transaksi_helper.php';

$jumlahPembelian = 0;
$nilaiPembelian = 0;
$jumlahDetail = 0;

$hasil = mysqli_query($koneksi, 'SELECT COUNT(*) AS jumlah FROM pembelian');
if ($hasil && $baris = mysqli_fetch_assoc($hasil)) {
    $jumlahPembelian = (int) $baris['jumlah'];
}

$hasil = mysqli_query($koneksi, 'SELECT COALESCE(SUM(jumlah * harga_beli), 0) AS nilai FROM detail_pembelian');
if ($hasil && $baris = mysqli_fetch_assoc($hasil)) {
    $nilaiPembelian = (float) $baris['nilai'];
}

$hasil = mysqli_query($koneksi, 'SELECT COUNT(*) AS jumlah FROM detail_pembelian');
if ($hasil && $baris = mysqli_fetch_assoc($hasil)) {
    $jumlahDetail = (int) $baris['jumlah'];
}

$judul = 'Transaksi Pembelian';
$halaman = 'transaksi';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">Pertemuan 16 &middot; Alur Transaksi</p>
<h1>Pembelian: Barang Masuk</h1>
<p class="lead">
  Satu transaksi selalu terdiri dari tiga lapis: <strong>kepala nota</strong> +
  <strong>isi nota</strong> + <strong>kartu stok</strong>. Pembelian menambah
  stok, jadi saldo di kartu stok ikut naik.
</p>

<div class="actions-top">
  <a class="btn" href="presentasi.php">Buka Slide P16</a>
  <a class="btn btn-secondary" href="panduan_transaksi.php">Baca Panduan Transaksi</a>
</div>

<div class="grid">
  <div class="stat">
    <span class="stat-label">Nota Pembelian</span>
    <strong class="stat-value"><?= $jumlahPembelian ?></strong>
    <span class="stat-note"><?= rupiah($nilaiPembelian) ?> total nilai</span>
  </div>
  <div class="stat">
    <span class="stat-label">Baris Barang</span>
    <strong class="stat-value"><?= $jumlahDetail ?></strong>
    <span class="stat-note">tersimpan di <code>detail_pembelian</code></span>
  </div>
  <div class="stat">
    <span class="stat-label">Arah Stok</span>
    <strong class="stat-value">Masuk</strong>
    <span class="stat-note">dicatat di tabel <code>stok</code></span>
  </div>
</div>

<div class="kartu-alur">
  <a href="pembelian.php">
    <span class="pill ok">Barang Masuk</span>
    <h3>Pembelian</h3>
    <p>
      Pilih supplier dan kasir, isi beberapa barang, lalu simpan. Stok
      bertambah dan riwayat pembelian tersimpan.
    </p>
  </a>
  <a href="pembelian_tambah.php">
    <span class="pill ok">Form</span>
    <h3>Tambah Pembelian</h3>
    <p>
      Contoh form multi-baris. Satu nota bisa berisi banyak produk.
    </p>
  </a>
</div>

<div class="card">
  <h2>Alur Singkat</h2>
  <table>
    <thead>
      <tr>
        <th>Langkah</th>
        <th>Yang Dilakukan</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1</td>
        <td>Pilih supplier + kasir aktif</td>
      </tr>
      <tr>
        <td>2</td>
        <td>Isi produk, jumlah, harga beli</td>
      </tr>
      <tr>
        <td>3</td>
        <td>Validasi semua baris input</td>
      </tr>
      <tr>
        <td>4</td>
        <td><code>INSERT pembelian</code> + <code>detail_pembelian</code></td>
      </tr>
      <tr>
        <td>5</td>
        <td><code>stok</code> masuk, saldo bertambah</td>
      </tr>
      <tr>
        <td>6</td>
        <td>Tampilkan detail pembelian</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="task">
  <h3>// TUGAS</h3>
  <ol>
    <li>Buka <a href="panduan_transaksi.php">Panduan Transaksi</a> dan baca algoritmanya.</li>
    <li>Buat satu pembelian dengan minimal 2 produk, lalu cek tabel <code>stok</code> di phpMyAdmin.</li>
    <li>Coba simpan nota tanpa memilih produk. Pastikan muncul pesan error, bukan data rusak.</li>
  </ol>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
