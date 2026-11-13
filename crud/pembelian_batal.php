<?php
// ============================================================
// kasirqu/crud/pembelian_batal.php
// DELETE terkendali: batalkan satu nota pembelian.
//
// Nota tidak boleh diedit. Kalau salah input, notanya dibatalkan:
//   1. stok dikurangi lewat baris stok keluar (jejak audit tetap ada),
//   2. baru detail dan kepala nota dihapus.
// Pembatalan ditolak kalau stok sekarang lebih kecil dari isi nota,
// karena berarti barangnya sudah terjual.
// ============================================================
require __DIR__ . '/../config/database.php';
require __DIR__ . '/transaksi_helper.php';

$noPembelian = trim($_GET['no'] ?? $_POST['no_pembelian'] ?? '');
$error = [];
$nota = null;
$detail = [];
$total = 0;
$totalQty = 0;
$kebutuhan = [];
$bolehBatal = false;

if ($noPembelian === '') {
    $error[] = 'Nomor pembelian tidak dikirim. Pilih nota dari halaman daftar.';
} else {
    $stmt = mysqli_prepare(
        $koneksi,
        'SELECT p.no_pembelian, p.tanggal, p.id_kasir, p.id_supplier,
                k.nama_kasir, s.nama_supplier
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
            $error[] = 'Nota ' . $noPembelian . ' tidak ditemukan.';
        }
    }
}

if ($nota) {
    $stmt = mysqli_prepare(
        $koneksi,
        'SELECT kode_produk, nama_produk, jumlah, harga_beli
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
            $total += (float) $baris['jumlah'] * (float) $baris['harga_beli'];
            $totalQty += (int) $baris['jumlah'];

            $kode = $baris['kode_produk'];
            if (!isset($kebutuhan[$kode])) {
                $kebutuhan[$kode] = [
                    'nama_produk' => $baris['nama_produk'],
                    'jumlah' => 0,
                ];
            }
            $kebutuhan[$kode]['jumlah'] += (int) $baris['jumlah'];
        }

        mysqli_stmt_close($stmt);
    }

    // Saldo terkini per produk + keputusan boleh dibatalkan atau tidak.
    $bolehBatal = count($kebutuhan) > 0;

    foreach ($kebutuhan as $kode => $info) {
        $saldo = saldo_stok_terakhir($koneksi, $kode);
        $kebutuhan[$kode]['saldo'] = $saldo;
        $kebutuhan[$kode]['sisa'] = $saldo - $info['jumlah'];

        if ($kebutuhan[$kode]['sisa'] < 0) {
            $bolehBatal = false;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $nota && count($error) === 0) {
    if (!$bolehBatal) {
        $error[] = 'Nota tidak bisa dibatalkan: stok sekarang lebih kecil dari isi nota. '
            . 'Barangnya kemungkinan sudah terjual.';
    } else {
        mysqli_begin_transaction($koneksi);

        try {
            $tanggal = date('Y-m-d');

            // 1. Stok keluar sebagai koreksi, dicatat per produk.
            $stmtStok = mysqli_prepare(
                $koneksi,
                'INSERT INTO stok
                    (kode_produk, tanggal, jumlah_masuk, jumlah_keluar,
                     saldo_awal, saldo_akhir, no_penjualan, no_pembelian, keterangan)
                 VALUES (?, ?, 0, ?, ?, ?, NULL, ?, ?)'
            );

            if (!$stmtStok) {
                throw new Exception('Gagal menyiapkan query stok: ' . mysqli_error($koneksi));
            }

            $keterangan = 'Batal pembelian';

            foreach ($kebutuhan as $kode => $info) {
                $saldoAwal = $info['saldo'];
                $saldoAkhir = $info['sisa'];
                $jumlah = $info['jumlah'];

                mysqli_stmt_bind_param(
                    $stmtStok,
                    'ssiiiss',
                    $kode,
                    $tanggal,
                    $jumlah,
                    $saldoAwal,
                    $saldoAkhir,
                    $noPembelian,
                    $keterangan
                );

                if (!mysqli_stmt_execute($stmtStok)) {
                    throw new Exception('Gagal mencatat stok keluar: ' . mysqli_stmt_error($stmtStok));
                }
            }

            mysqli_stmt_close($stmtStok);

            // 2. Hapus detail, lalu kepala nota.
            $stmtDetail = mysqli_prepare($koneksi, 'DELETE FROM detail_pembelian WHERE no_pembelian = ?');

            if (!$stmtDetail) {
                throw new Exception('Gagal menyiapkan hapus detail: ' . mysqli_error($koneksi));
            }

            mysqli_stmt_bind_param($stmtDetail, 's', $noPembelian);

            if (!mysqli_stmt_execute($stmtDetail)) {
                throw new Exception('Gagal menghapus detail: ' . mysqli_stmt_error($stmtDetail));
            }

            mysqli_stmt_close($stmtDetail);

            $stmtNota = mysqli_prepare($koneksi, 'DELETE FROM pembelian WHERE no_pembelian = ?');

            if (!$stmtNota) {
                throw new Exception('Gagal menyiapkan hapus nota: ' . mysqli_error($koneksi));
            }

            mysqli_stmt_bind_param($stmtNota, 's', $noPembelian);

            if (!mysqli_stmt_execute($stmtNota)) {
                throw new Exception('Gagal menghapus nota: ' . mysqli_stmt_error($stmtNota));
            }

            mysqli_stmt_close($stmtNota);

            mysqli_commit($koneksi);

            header('Location: pembelian.php?status=batal');
            exit;
        } catch (Exception $e) {
            mysqli_rollback($koneksi);
            $error[] = 'Pembatalan dibatalkan, tidak ada data yang berubah: ' . $e->getMessage();
        }
    }
}

$judul = 'Batalkan Pembelian';
$halaman = 'transaksi';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">DELETE &middot; Barang Masuk</p>
<h1>Batalkan Nota Pembelian</h1>
<p class="lead">
  Nota pembelian tidak bisa diedit. Kalau salah input, notanya dibatalkan:
  stok dikoreksi dulu, baru nota dihapus. Keduanya dalam satu transaksi.
</p>

<?php if (count($error) > 0): ?>
  <div class="alert error">
    <strong>Nota tidak bisa dibatalkan:</strong>
    <ul>
      <?php foreach ($error as $pesan): ?>
        <li><?= htmlspecialchars($pesan) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php if ($nota): ?>
  <div class="card">
    <h2>Nota yang Akan Dibatalkan</h2>
    <table>
      <tbody>
        <tr>
          <th>Nomor</th>
          <td><code><?= htmlspecialchars($nota['no_pembelian']) ?></code></td>
        </tr>
        <tr>
          <th>Tanggal</th>
          <td><?= htmlspecialchars($nota['tanggal']) ?></td>
        </tr>
        <tr>
          <th>Supplier</th>
          <td><?= htmlspecialchars($nota['nama_supplier'] ?? '-') ?></td>
        </tr>
        <tr>
          <th>Kasir</th>
          <td><?= htmlspecialchars($nota['nama_kasir'] ?? '-') ?></td>
        </tr>
        <tr>
          <th>Isi Nota</th>
          <td><?= (int) $totalQty ?> barang &middot; <?= rupiah($total) ?></td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="card">
    <h2>Dampak ke Stok</h2>
    <table>
      <thead>
        <tr>
          <th>Kode</th>
          <th>Nama Produk</th>
          <th class="num">Keluar</th>
          <th class="num">Saldo Sekarang</th>
          <th class="num">Saldo Setelah</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($kebutuhan as $kode => $info): ?>
          <tr>
            <td><code><?= htmlspecialchars($kode) ?></code></td>
            <td><?= htmlspecialchars($info['nama_produk']) ?></td>
            <td class="num">-<?= (int) $info['jumlah'] ?></td>
            <td class="num"><?= (int) $info['saldo'] ?></td>
            <td class="num">
              <strong style="<?= $info['sisa'] < 0 ? 'color:#C0392B;' : '' ?>">
                <?= (int) $info['sisa'] ?>
              </strong>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <p class="muted">
      Saldo setelah tidak boleh negatif. Kalau negatif, berarti barang dari nota
      ini sudah terjual dan nota tidak boleh dibatalkan.
    </p>
  </div>

  <?php if ($bolehBatal): ?>
    <div class="alert" style="background:#FFF6EA;border-left:4px solid #E87D0E;">
      <strong>Perhatian:</strong> stok akan dikurangi sebanyak isi nota, lalu nota
      dihapus. Baris koreksi tetap tersimpan di tabel <code>stok</code> dengan
      keterangan "Batal pembelian".
    </div>

    <form method="post" action="pembelian_batal.php">
      <input type="hidden" name="no_pembelian" value="<?= htmlspecialchars($noPembelian) ?>">
      <div class="actions-top">
        <button class="btn btn-danger" type="submit">Ya, Batalkan Nota</button>
        <a class="btn btn-secondary" href="pembelian.php">Batal</a>
      </div>
    </form>
  <?php else: ?>
    <div class="alert error">
      <strong>Pembatalan diblokir.</strong>
      Stok sekarang lebih kecil dari isi nota, jadi barangnya kemungkinan sudah
      terjual. Kalau memang salah input, buat nota koreksi, bukan membatalkan
      nota ini.
    </div>

    <div class="actions-top">
      <a class="btn btn-secondary" href="pembelian.php">Kembali ke Daftar Pembelian</a>
    </div>
  <?php endif; ?>
<?php else: ?>
  <div class="actions-top">
    <a class="btn" href="pembelian.php">Kembali ke Daftar Pembelian</a>
  </div>
<?php endif; ?>

<div class="task">
  <h3>// CATATAN</h3>
  <p>
    Urutannya penting: <strong>catat stok keluar dulu</strong>, baru hapus detail,
    lalu hapus kepala nota. Semua dibungkus
    <code>begin_transaction</code> + <code>commit</code>; kalau satu langkah
    gagal, <code>rollback</code> membatalkan semuanya.
  </p>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
