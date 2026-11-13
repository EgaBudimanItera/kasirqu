<?php
// ============================================================
// kasirqu/crud/pembelian_detail.php
// READ: detail satu nota pembelian + dampak ke stok.
// ============================================================
require __DIR__ . '/../config/database.php';
require __DIR__ . '/transaksi_helper.php';

$noPembelian = trim($_GET['no'] ?? '');
$error = [];
$nota = null;
$detail = [];
$mutasiStok = [];

if ($noPembelian === '') {
    $error[] = 'Nomor pembelian tidak dikirim.';
} else {
    $stmt = mysqli_prepare(
        $koneksi,
        'SELECT p.no_pembelian, p.tanggal, p.id_kasir, p.id_supplier,
                k.nama_kasir, s.nama_supplier, s.kota
         FROM pembelian p
         LEFT JOIN kasir k ON k.id_kasir = p.id_kasir
         LEFT JOIN supplier s ON s.id_supplier = p.id_supplier
         WHERE p.no_pembelian = ?'
    );

    if (!$stmt) {
        $error[] = 'Tabel pembelian belum siap: ' . mysqli_error($koneksi);
    } else {
        mysqli_stmt_bind_param($stmt, 's', $noPembelian);
        mysqli_stmt_execute($stmt);
        $hasil = mysqli_stmt_get_result($stmt);
        $nota = mysqli_fetch_assoc($hasil);
        mysqli_stmt_close($stmt);

        if (!$nota) {
            $error[] = 'Pembelian dengan nomor ' . $noPembelian . ' tidak ditemukan.';
        }
    }
}

if ($nota) {
    $stmt = mysqli_prepare(
        $koneksi,
        'SELECT id_detail, kode_produk, nama_produk, jumlah, harga_beli
         FROM detail_pembelian
         WHERE no_pembelian = ?
         ORDER BY id_detail'
    );

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $noPembelian);
        mysqli_stmt_execute($stmt);
        $hasil = mysqli_stmt_get_result($stmt);

        while ($baris = mysqli_fetch_assoc($hasil)) {
            $detail[] = $baris;
        }

        mysqli_stmt_close($stmt);
    }

    $stmt = mysqli_prepare(
        $koneksi,
        'SELECT tanggal, kode_produk, jumlah_masuk, saldo_awal, saldo_akhir
         FROM stok
         WHERE no_pembelian = ?
         ORDER BY id_stok'
    );

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $noPembelian);
        mysqli_stmt_execute($stmt);
        $hasil = mysqli_stmt_get_result($stmt);

        while ($baris = mysqli_fetch_assoc($hasil)) {
            $mutasiStok[] = $baris;
        }

        mysqli_stmt_close($stmt);
    }
}

$total = 0;
$totalQty = 0;
foreach ($detail as $baris) {
    $total += (float) $baris['jumlah'] * (float) $baris['harga_beli'];
    $totalQty += (int) $baris['jumlah'];
}

$judul = 'Detail Pembelian';
$halaman = 'transaksi';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">Detail &middot; Barang Masuk</p>
<h1>Nota Pembelian</h1>

<?php if (count($error) > 0): ?>
  <div class="alert error">
    <ul>
      <?php foreach ($error as $pesan): ?>
        <li><?= htmlspecialchars($pesan) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <a class="btn" href="pembelian.php">Kembali ke Daftar Pembelian</a>
<?php else: ?>
  <?php if (($_GET['status'] ?? '') === 'tambah'): ?>
    <div class="alert ok">Pembelian berhasil disimpan dan stok sudah diperbarui.</div>
  <?php endif; ?>

  <div class="nota-box">
    <div class="nota-head">
      <h2><?= htmlspecialchars($nota['no_pembelian']) ?></h2>
      <p class="muted">
        <?= htmlspecialchars($nota['tanggal']) ?> &middot;
        Supplier: <?= htmlspecialchars($nota['nama_supplier'] ?? '-') ?> &middot;
        Kasir: <?= htmlspecialchars($nota['nama_kasir'] ?? '-') ?>
      </p>
    </div>

    <table>
      <thead>
        <tr>
          <th>Kode</th>
          <th>Nama Produk</th>
          <th class="num">Jumlah</th>
          <th class="num">Harga Beli</th>
          <th class="num">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($detail as $baris): ?>
          <tr>
            <td><code><?= htmlspecialchars($baris['kode_produk']) ?></code></td>
            <td><?= htmlspecialchars($baris['nama_produk']) ?></td>
            <td class="num"><?= (int) $baris['jumlah'] ?></td>
            <td class="num"><?= rupiah($baris['harga_beli']) ?></td>
            <td class="num"><?= rupiah((float) $baris['jumlah'] * (float) $baris['harga_beli']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="nota-total">
      Total <?= (int) $totalQty ?> barang &middot;
      <strong><?= rupiah($total) ?></strong>
    </div>
  </div>

  <div class="card">
    <h2>Dampak ke Stok</h2>
    <p class="muted">
      Baris di bawah ini berasal dari tabel <code>stok</code> dengan
      <code>no_pembelian = <?= htmlspecialchars($noPembelian) ?></code>.
    </p>
    <table>
      <thead>
        <tr>
          <th>Tanggal</th>
          <th>Kode Produk</th>
          <th class="num">Masuk</th>
          <th class="num">Saldo Awal</th>
          <th class="num">Saldo Akhir</th>
        </tr>
      </thead>
      <tbody>
        <?php if (count($mutasiStok) === 0): ?>
          <tr>
            <td class="empty" colspan="5">Belum ada mutasi stok untuk nota ini.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($mutasiStok as $stok): ?>
            <tr>
              <td><?= htmlspecialchars($stok['tanggal']) ?></td>
              <td><code><?= htmlspecialchars($stok['kode_produk']) ?></code></td>
              <td class="num">+<?= (int) $stok['jumlah_masuk'] ?></td>
              <td class="num"><?= (int) $stok['saldo_awal'] ?></td>
              <td class="num"><strong><?= (int) $stok['saldo_akhir'] ?></strong></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <div class="actions-top">
    <a class="btn" href="pembelian_tambah.php">+ Pembelian Lagi</a>
    <a class="btn btn-danger" href="pembelian_batal.php?no=<?= urlencode($noPembelian) ?>">Batalkan Nota</a>
    <a class="btn btn-secondary" href="pembelian.php">Daftar Pembelian</a>
    <a class="btn btn-secondary" href="transaksi.php">Hub Transaksi</a>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
