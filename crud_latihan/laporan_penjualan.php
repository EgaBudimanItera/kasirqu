<?php
// ============================================================
// kasirqu/crud_latihan/laporan_penjualan.php
// VERSI LATIHAN dari halaman laporan penjualan (P17).
//
// Data sudah dibaca lewat helper. Tugasmu ada pada bagian bertanda TODO.
// Halaman ini sengaja terbuka tanpa login, supaya latihan tidak tersendat.
// Bandingkan dengan contoh di ../crud/laporan_penjualan.php.
// ============================================================

require __DIR__ . '/../config/database.php';
require __DIR__ . '/laporan_penjualan_data.php';
require __DIR__ . '/../crud/transaksi_helper.php';

[$dari, $sampai, $errorFilter] = periode_laporan_penjualan();
[$daftar, $errorTabel] = data_laporan_penjualan($koneksi, $dari, $sampai);
$ringkas = ringkasan_laporan_penjualan($daftar);

// TODO 1: ganti warna tag di bawah jadi "Contoh" kalau sudah yakin.
// TODO 2: tambahkan satu kolom "No." pada tabel daftar nota.
// TODO 3: coba klik tombol Cetak, lalu ubah kop di laporan_penjualan_cetak.php.

$jumlahNota = (int) $ringkas['jumlahNota'];
$totalItem  = (int) $ringkas['totalItem'];
$totalNilai = (float) $ringkas['totalNilai'];
$rataNota   = (float) $ringkas['rataNota'];
$urlCetak   = 'laporan_penjualan_cetak.php?dari=' . urlencode($dari)
            . '&sampai=' . urlencode($sampai);

$judul   = 'Latihan Laporan Penjualan';
$halaman = 'laporan_penjualan';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">Mode Latihan &middot; LAPORAN</p>
<h1>Laporan Penjualan per Periode</h1>
<p class="lead">
  Halaman ini memakai helper yang sama dengan halaman cetak. Coba ubah
  <code>laporan_penjualan_data.php</code>, lalu lihat perubahannya di dua tempat.
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
          <td class="empty" colspan="6">Tidak ada penjualan pada periode ini.</td>
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

<div class="actions-top">
  <a class="btn" href="<?= htmlspecialchars($urlCetak) ?>"
     target="_blank" rel="noopener">Buka Halaman Cetak</a>
  <a class="btn btn-secondary" href="panduan_laporan.php">Panduan Latihan</a>
  <a class="btn btn-secondary" href="../crud/laporan_penjualan.php">Lihat Contoh</a>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
