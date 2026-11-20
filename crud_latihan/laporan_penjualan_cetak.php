<?php
// ============================================================
// kasirqu/crud_latihan/laporan_penjualan_cetak.php
// VERSI LATIHAN dari halaman cetak laporan penjualan.
//
// Halaman ini sengaja TIDAK memakai penjaga login supaya latihan
// tetap bisa dibuka. Kalau ingin mengunci seperti di ../crud/,
// salin dua baris ini ke paling atas:
//   require_once __DIR__ . '/../config/auth.php';
//   wajib_role('admin');
//
// Tugasmu:
//   TODO 1 - ganti nama/alamat toko di kop surat.
//   TODO 2 - ganti "Pemilik Toko" di blok tanda tangan.
//   TODO 3 - buka assets/cetak.css, ubah ukuran font, lalu cetak ulang.
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/../config/database.php';
require __DIR__ . '/laporan_penjualan_data.php';
require __DIR__ . '/../crud/transaksi_helper.php';

if (!function_exists('e')) {
    function e($nilai): string
    {
        return htmlspecialchars((string) $nilai, ENT_QUOTES, 'UTF-8');
    }
}

[$dari, $sampai, $errorFilter] = periode_laporan_penjualan();
[$daftar, $errorTabel] = data_laporan_penjualan($koneksi, $dari, $sampai);
$ringkas = ringkasan_laporan_penjualan($daftar);

$user         = $_SESSION['kasirqu'] ?? [];
$namaPencetak = $user['nama'] ?? 'Operator Latihan';
$jabatanCetak = ($user['role'] ?? '') === 'admin' ? 'Administrator' : 'Kasir';
$periodeTeks  = date('d/m/Y', strtotime($dari)) . ' s/d ' . date('d/m/Y', strtotime($sampai));
$waktuCetak   = date('d/m/Y H:i');
$nomorDokumen = 'LPJ-LATIHAN/' . date('Ymd', strtotime($dari)) . '-' . date('Ymd', strtotime($sampai));
$urlKembali   = 'laporan_penjualan.php?dari=' . urlencode($dari) . '&sampai=' . urlencode($sampai);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak Laporan Penjualan - Latihan</title>
  <link rel="stylesheet" href="assets/cetak.css">
</head>
<body>

  <div class="toolbar">
    <strong>Cetak Laporan Penjualan (Latihan)</strong>
    <div class="toolbar-aksi">
      <a href="<?= e($urlKembali) ?>">Kembali ke laporan</a>
      <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>
  </div>

  <div class="halaman">
    <div class="kop">
      <div class="kop-logo">MJ</div>
      <div class="kop-isi">
        <!-- TODO 1: ganti nama toko di bawah ini -->
        <h1>Toko Maju Jaya</h1>
        <p>Jl. Melati No. 12 &middot; Telp. (021) 000-1234 &middot; toko@maujaya.test</p>
      </div>
    </div>
    <div class="garis"></div>

    <div class="judul">
      <h2>Laporan Penjualan</h2>
      <p>Periode <?= e($periodeTeks) ?></p>
    </div>

    <table class="meta">
      <tr>
        <td class="label">Nomor dokumen</td>
        <td>: <?= e($nomorDokumen) ?></td>
        <td class="label">Dicetak pada</td>
        <td>: <?= e($waktuCetak) ?></td>
      </tr>
      <tr>
        <td class="label">Periode data</td>
        <td>: <?= e($periodeTeks) ?></td>
        <td class="label">Dicetak oleh</td>
        <td>: <?= e($namaPencetak) ?> (<?= e($jabatanCetak) ?>)</td>
      </tr>
    </table>

    <?php if ($errorFilter !== ''): ?>
      <div class="pesan-error">Catatan sistem: <?= e($errorFilter) ?></div>
    <?php endif; ?>

    <?php if ($errorTabel !== ''): ?>
      <div class="pesan-error">
        <strong>Data laporan belum bisa dibaca.</strong><br>
        Pesan MySQL: <?= e($errorTabel) ?>
      </div>
    <?php endif; ?>

    <table class="ringkasan">
      <thead>
        <tr>
          <th>Jumlah Nota</th>
          <th>Total Item</th>
          <th>Total Omzet</th>
          <th>Rata-rata per Nota</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?= (int) $ringkas['jumlahNota'] ?></td>
          <td><?= (int) $ringkas['totalItem'] ?></td>
          <td><?= e(rupiah($ringkas['totalNilai'])) ?></td>
          <td><?= e(rupiah($ringkas['rataNota'])) ?></td>
        </tr>
      </tbody>
    </table>

    <table class="rincian">
      <thead>
        <tr>
          <th>No.</th>
          <th>No. Penjualan</th>
          <th>Tanggal</th>
          <th>Kasir</th>
          <th>Metode</th>
          <th class="num">Item</th>
          <th class="num">Total</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($ringkas['jumlahNota'] === 0): ?>
          <tr>
            <td class="kosong" colspan="7">Tidak ada transaksi penjualan pada periode ini.</td>
          </tr>
        <?php else: ?>
          <?php $no = 1; ?>
          <?php foreach ($daftar as $nota): ?>
            <tr>
              <td style="text-align: center;"><?= $no++ ?></td>
              <td><?= e($nota['no_penjualan']) ?></td>
              <td><?= e(date('d/m/Y', strtotime($nota['tanggal']))) ?></td>
              <td><?= e($nota['nama_kasir']) ?></td>
              <td><?= e($nota['metode_bayar'] ?? '-') ?></td>
              <td class="num"><?= (int) $nota['total_item'] ?></td>
              <td class="num"><?= e(rupiah($nota['total_nilai'])) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="5" style="text-align: right;">TOTAL</th>
          <th class="num"><?= (int) $ringkas['totalItem'] ?></th>
          <th class="num"><?= e(rupiah($ringkas['totalNilai'])) ?></th>
        </tr>
      </tfoot>
    </table>

    <p class="penutup">
      Demikian laporan penjualan ini dibuat berdasarkan data transaksi pada
      sistem KasirQu untuk periode <?= e($periodeTeks) ?>. Total dihitung ulang
      dari rincian nota memakai harga saat transaksi terjadi. Laporan ini
      dibuat untuk digunakan sebagaimana mestinya.
    </p>

    <table class="ttd">
      <tr>
        <td>
          Dibuat oleh,<br>
          <div class="ruang"></div>
          <span class="nama"><?= e($namaPencetak) ?></span><br>
          <?= e($jabatanCetak) ?>
        </td>
        <td>
          Mengetahui,<br>
          <div class="ruang"></div>
          <!-- TODO 2: ganti "Pemilik Toko" dengan jabatan lain -->
          <span class="nama">Pemilik Toko</span><br>
          Toko Maju Jaya
        </td>
      </tr>
    </table>

    <p class="catatan">
      Dokumen ini dicetak pada <?= e($waktuCetak) ?> &middot;
      <?= e($nomorDokumen) ?> &middot; KasirQu / Latihan
    </p>
  </div>

</body>
</html>
