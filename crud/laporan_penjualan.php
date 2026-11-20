<?php
// ============================================================
// kasirqu/crud/laporan_penjualan.php
// CONTOH LAPORAN (bahan bedah di Pertemuan 17).
//
// Lima bagian yang selalu ada di laporan:
//   1. FILTER        tanggal awal & akhir (default: bulan ini)
//   2. QUERY AGREGAT JOIN + WHERE BETWEEN + GROUP BY + SUM
//   3. RINGKASAN     jumlah nota, total item, omzet, rata-rata
//   4. TABEL         satu baris per nota
//   5. CETAK         tautan ke halaman cetak resmi (tab baru)
//
// Halaman ini dikunci untuk admin. Menu saja tidak cukup: orang bisa
// mengetik URL langsung, jadi server tetap harus memeriksa role.
// ============================================================

require_once __DIR__ . '/config/auth.php';
wajib_role('admin');

require __DIR__ . '/../config/database.php';
require __DIR__ . '/laporan_penjualan_data.php';
require __DIR__ . '/transaksi_helper.php';

// ------------------------------------------------------------
// 1-3. FILTER, QUERY, RINGKASAN
// Logikanya ada di laporan_penjualan_data.php supaya halaman layar
// dan halaman cetak memakai query yang SAMA persis.
// ------------------------------------------------------------
[$dari, $sampai, $errorFilter] = periode_laporan_penjualan();
[$daftar, $errorTabel]         = data_laporan_penjualan($koneksi, $dari, $sampai);
$ringkas                       = ringkasan_laporan_penjualan($daftar);

$jumlahNota = (int) $ringkas['jumlahNota'];
$totalItem  = (int) $ringkas['totalItem'];
$totalNilai = (float) $ringkas['totalNilai'];
$rataNota   = (float) $ringkas['rataNota'];

// Filter ikut terbawa ke tab cetak lewat GET.
$urlCetak = 'laporan_penjualan_cetak.php?dari=' . urlencode($dari)
          . '&sampai=' . urlencode($sampai);

$judul   = 'Laporan Penjualan';
$halaman = 'laporan_penjualan';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">Contoh Laporan &middot; Pertemuan 17</p>
<h1>Laporan Penjualan per Periode</h1>
<p class="lead">
  Menjawab pertanyaan pemilik toko: <em>&ldquo;Berapa omzet kita bulan ini?&rdquo;</em>
</p>

<?php if ($errorFilter !== ''): ?>
  <div class="alert error"><?= htmlspecialchars($errorFilter) ?></div>
<?php endif; ?>

<?php if ($errorTabel !== ''): ?>
  <div class="alert error">
    <strong>Laporan belum bisa dibaca.</strong><br>
    Pesan MySQL: <?= htmlspecialchars($errorTabel) ?>
  </div>
<?php endif; ?>

<div class="card">
  <form class="form-grid" method="get" action="laporan_penjualan.php">
    <div class="field">
      <label for="dari">Dari tanggal</label>
      <input type="date" id="dari" name="dari" value="<?= htmlspecialchars($dari) ?>">
    </div>
    <div class="field">
      <label for="sampai">Sampai tanggal</label>
      <input type="date" id="sampai" name="sampai" value="<?= htmlspecialchars($sampai) ?>">
    </div>
    <div class="field">
      <label>&nbsp;</label>
      <button class="btn" type="submit">Tampilkan</button>
    </div>
    <div class="field">
      <label>&nbsp;</label>
      <a class="btn btn-secondary" href="laporan_penjualan.php">Bulan Ini</a>
    </div>
  </form>

  <p class="muted" style="margin-bottom: 0;">
    Periode terbaca:
    <strong><?= htmlspecialchars(date('d/m/Y', strtotime($dari))) ?></strong>
    &ndash;
    <strong><?= htmlspecialchars(date('d/m/Y', strtotime($sampai))) ?></strong>
    &middot;
    <a class="btn btn-sm" href="<?= htmlspecialchars($urlCetak) ?>"
       target="_blank" rel="noopener">Cetak</a>
  </p>
</div>

<div class="grid">
  <div class="stat">
    <span class="stat-label">Jumlah Nota</span>
    <span class="stat-value"><?= $jumlahNota ?></span>
    <span class="stat-note">nota penjualan pada periode ini</span>
  </div>
  <div class="stat">
    <span class="stat-label">Total Item</span>
    <span class="stat-value"><?= $totalItem ?></span>
    <span class="stat-note">jumlah barang, bukan jumlah baris</span>
  </div>
  <div class="stat">
    <span class="stat-label">Omzet</span>
    <span class="stat-value"><?= rupiah($totalNilai) ?></span>
    <span class="stat-note">&Sigma; jumlah &times; harga satuan</span>
  </div>
  <div class="stat">
    <span class="stat-label">Rata-rata / Nota</span>
    <span class="stat-value"><?= rupiah($rataNota) ?></span>
    <span class="stat-note">omzet dibagi jumlah nota</span>
  </div>
</div>

<div class="card">
  <h2>Daftar Nota Penjualan</h2>

  <table>
    <thead>
      <tr>
        <th>No. Penjualan</th>
        <th>Tanggal</th>
        <th>Kasir</th>
        <th>Metode</th>
        <th class="num">Item</th>
        <th class="num">Total</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($jumlahNota === 0): ?>
        <tr>
          <td class="empty" colspan="6">
            Tidak ada penjualan pada periode ini.
            Coba ubah tanggal, atau buat satu nota penjualan dulu.
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($daftar as $nota): ?>
          <tr>
            <td><code><?= htmlspecialchars($nota['no_penjualan']) ?></code></td>
            <td><?= htmlspecialchars(date('d/m/Y', strtotime($nota['tanggal']))) ?></td>
            <td><?= htmlspecialchars($nota['nama_kasir']) ?></td>
            <td><?= htmlspecialchars($nota['metode_bayar'] ?? '-') ?></td>
            <td class="num"><?= (int) $nota['total_item'] ?></td>
            <td class="num"><?= rupiah($nota['total_nilai']) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
    <tfoot>
      <tr>
        <th colspan="4">Total</th>
        <th class="num"><?= $totalItem ?></th>
        <th class="num"><?= rupiah($totalNilai) ?></th>
      </tr>
    </tfoot>
  </table>
</div>

<div class="card">
  <h2>Kenapa Angka Ini Bisa Dipercaya?</h2>
  <ul class="steps" style="margin-bottom: 0;">
    <li>Total dihitung ulang dari <code>detail_penjualan</code> setiap halaman dibuka.</li>
    <li>Harga yang dipakai adalah <em>snapshot</em> saat transaksi terjadi.</li>
    <li>Jadi mengubah harga produk hari ini tidak mengubah laporan bulan lalu.</li>
  </ul>
</div>

<div class="actions-top">
  <a class="btn" href="<?= htmlspecialchars($urlCetak) ?>"
     target="_blank" rel="noopener">Buka Halaman Cetak</a>
  <a class="btn btn-secondary" href="panduan_laporan.php">Baca Panduan Laporan</a>
  <a class="btn btn-secondary" href="panduan.php">Kembali ke Panduan</a>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
