<?php
// ============================================================
// kasirqu/crud_latihan/panduan_laporan.php
// Panduan versi latihan: laporan + halaman cetak resmi.
// Tidak memakai database, jadi tetap bisa dibaca tanpa MySQL.
// ============================================================
$judul   = 'Panduan Laporan (Latihan)';
$halaman = 'panduan_laporan';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">Panduan Latihan &middot; P17</p>
<h1>Laporan dan Halaman Cetak</h1>
<p class="lead">
  Di latihan ini kamu memodifikasi tiga file sekaligus. Perubahannya harus
  terlihat di layar <em>dan</em> di kertas.
</p>

<div class="card">
  <h2>1. Peta file latihan</h2>
  <table>
    <thead>
      <tr>
        <th>File</th>
        <th>Yang bisa kamu ubah</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>laporan_penjualan_data.php</code></td>
        <td>TODO 1: tambah filter metode bayar. TODO 2: balik urutan tanggal.</td>
      </tr>
      <tr>
        <td><code>laporan_penjualan.php</code></td>
        <td>TODO 1: ganti warna tag. TODO 2: tambah kolom "No." di tabel.</td>
      </tr>
      <tr>
        <td><code>laporan_penjualan_cetak.php</code></td>
        <td>TODO 1: ganti kop toko. TODO 2: ganti jabatan penandatangan.</td>
      </tr>
      <tr>
        <td><code>assets/cetak.css</code></td>
        <td>TODO 3: ubah ukuran font atau margin A4.</td>
      </tr>
    </tbody>
  </table>
  <p class="muted" style="margin-bottom: 0;">
    Semua perhitungan ada di helper. Kalau kamu mengubah query di helper,
    angka di layar dan di kertas ikut berubah bersamaan.
  </p>
</div>

<div class="card">
  <h2>2. Alur tombol Cetak</h2>
  <ol class="steps">
    <li>Pilih tanggal di form, lalu klik <strong>Tampilkan</strong>.</li>
    <li>Klik <strong>Cetak</strong>. Tab baru akan terbuka.</li>
    <li>Di tab baru, klik <strong>Cetak / Simpan PDF</strong>.</li>
  </ol>
  <pre><code>$urlCetak = 'laporan_penjualan_cetak.php?dari='
          . urlencode($dari) . '&amp;sampai=' . urlencode($sampai);

&lt;a href="&lt;?= $urlCetak ?&gt;" target="_blank" rel="noopener"&gt;Cetak&lt;/a&gt;</code></pre>
  <p style="margin-bottom: 0;">
    <code>target="_blank"</code> membuka tab baru. <code>rel="noopener"</code>
    menjaga tab baru tidak bisa mengakses tab lama lewat JavaScript.
  </p>
</div>

<div class="card">
  <h2>3. Apa yang membuatnya terasa "resmi"?</h2>
  <ul class="steps">
    <li>Kop surat berisi nama toko, alamat, dan kontak.</li>
    <li>Nomor dokumen dibuat otomatis dari rentang tanggal.</li>
    <li>Metadata mencatat kapan dan oleh siapa laporan dicetak.</li>
    <li>Ringkasan berada di atas tabel, bukan campur di bawah.</li>
    <li>Ada kalimat penutup dan dua kolom tanda tangan.</li>
    <li>Kertas A4 hitam putih, judul tabel diulang tiap halaman.</li>
  </ul>
</div>

<div class="task">
  <h3>Tantangan kecil</h3>
  <ol>
    <li>Tambahkan satu baris di kop: <code>Email: toko@maujaya.test</code>.</li>
    <li>Ganti tanda tangan kanan menjadi <strong>Kepala Toko</strong>.</li>
    <li>Cetak ulang, lalu bandingkan dengan contoh di
      <code>../crud/laporan_penjualan_cetak.php</code>.</li>
  </ol>
</div>

<div class="actions-top">
  <a class="btn" href="laporan_penjualan.php">Buka Laporan Latihan</a>
  <a class="btn btn-secondary" href="laporan_penjualan_cetak.php">Buka Halaman Cetak</a>
  <a class="btn btn-secondary" href="../crud/panduan_laporan.php">Lihat Panduan Contoh</a>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
