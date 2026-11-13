<?php
// ============================================================
// kasirqu/crud/pembelian.php
// READ: daftar nota pembelian (barang masuk).
// ============================================================
require __DIR__ . '/../config/database.php';
require __DIR__ . '/transaksi_helper.php';

$cari = trim($_GET['cari'] ?? '');
$errorTabel = '';

$sqlDasar = "SELECT p.no_pembelian, p.tanggal, p.id_kasir, p.id_supplier,
                    k.nama_kasir, s.nama_supplier,
                    COALESCE(SUM(d.jumlah * d.harga_beli), 0) AS total,
                    COUNT(d.id_detail) AS jumlah_item
             FROM pembelian p
             LEFT JOIN kasir k ON k.id_kasir = p.id_kasir
             LEFT JOIN supplier s ON s.id_supplier = p.id_supplier
             LEFT JOIN detail_pembelian d ON d.no_pembelian = p.no_pembelian";

if ($cari !== '') {
    $sql = $sqlDasar . "
            WHERE p.no_pembelian LIKE ?
               OR k.nama_kasir LIKE ?
               OR s.nama_supplier LIKE ?
            GROUP BY p.no_pembelian, p.tanggal, p.id_kasir, p.id_supplier,
                     k.nama_kasir, s.nama_supplier
            ORDER BY p.tanggal DESC, p.no_pembelian DESC";
    $stmt = mysqli_prepare($koneksi, $sql);

    if (!$stmt) {
        $errorTabel = mysqli_error($koneksi);
        $hasil = false;
    } else {
        $kataKunci = '%' . $cari . '%';
        mysqli_stmt_bind_param($stmt, 'sss', $kataKunci, $kataKunci, $kataKunci);
        mysqli_stmt_execute($stmt);
        $hasil = mysqli_stmt_get_result($stmt);
        mysqli_stmt_close($stmt);
    }
} else {
    $sql = $sqlDasar . "
            GROUP BY p.no_pembelian, p.tanggal, p.id_kasir, p.id_supplier,
                     k.nama_kasir, s.nama_supplier
            ORDER BY p.tanggal DESC, p.no_pembelian DESC";
    $hasil = mysqli_query($koneksi, $sql);

    if (!$hasil) {
        $errorTabel = mysqli_error($koneksi);
    }
}

$daftarPembelian = [];

if ($hasil) {
    while ($baris = mysqli_fetch_assoc($hasil)) {
        $daftarPembelian[] = $baris;
    }
}

$jumlahNota = count($daftarPembelian);
$totalNilai = 0;
$totalItem = 0;

foreach ($daftarPembelian as $nota) {
    $totalNilai += (float) $nota['total'];
    $totalItem += (int) $nota['jumlah_item'];
}

$judul = 'Daftar Pembelian';
$halaman = 'transaksi';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">Barang Masuk</p>
<h1>Daftar Pembelian</h1>
<p class="lead">
  Nota pembelian dibaca dari tabel <code>pembelian</code> dan
  <code>detail_pembelian</code>. Total dihitung dari isi detail, bukan
  disimpan di kepala nota.
</p>

<?php if ($errorTabel !== ''): ?>
  <div class="alert error">
    <strong>Tabel pembelian belum bisa dibaca.</strong>
    <br><small>Pesan MySQL: <?= htmlspecialchars($errorTabel) ?></small>
  </div>
<?php endif; ?>

<?php if (($_GET['status'] ?? '') === 'tambah'): ?>
  <div class="alert ok">Pembelian baru berhasil disimpan.</div>
<?php endif; ?>

<?php if (($_GET['status'] ?? '') === 'batal'): ?>
  <div class="alert ok">Nota berhasil dibatalkan dan stok sudah dikoreksi.</div>
<?php endif; ?>

<div class="grid">
  <div class="stat">
    <span class="stat-label">Jumlah Nota</span>
    <strong class="stat-value"><?= $jumlahNota ?></strong>
    <span class="stat-note">nota pembelian</span>
  </div>
  <div class="stat">
    <span class="stat-label">Total Nilai</span>
    <strong class="stat-value"><?= rupiah($totalNilai) ?></strong>
    <span class="stat-note">dari semua detail</span>
  </div>
  <div class="stat">
    <span class="stat-label">Baris Barang</span>
    <strong class="stat-value"><?= $totalItem ?></strong>
    <span class="stat-note">baris di detail pembelian</span>
  </div>
</div>

<div class="actions-top">
  <a class="btn" href="pembelian_tambah.php">+ Tambah Pembelian</a>
  <a class="btn btn-secondary" href="presentasi_p11_pembelian.php">Slide Pembelian</a>
  <a class="btn btn-secondary" href="transaksi.php">Hub Transaksi</a>
  <a class="btn btn-secondary" href="panduan_transaksi.php">Panduan</a>
</div>

<div class="card">
  <h2>Data Pembelian</h2>

  <form class="search-row" method="get" action="pembelian.php">
    <input type="text" name="cari" value="<?= htmlspecialchars($cari) ?>"
           placeholder="Cari nomor nota, kasir, atau supplier...">
    <button class="btn" type="submit">Cari</button>
    <?php if ($cari !== ''): ?>
      <a class="btn btn-secondary" href="pembelian.php">Reset</a>
    <?php endif; ?>
  </form>

  <?php if ($cari !== ''): ?>
    <p class="muted">Hasil pencarian untuk: <strong><?= htmlspecialchars($cari) ?></strong></p>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>No. Pembelian</th>
        <th>Tanggal</th>
        <th>Supplier</th>
        <th>Kasir</th>
        <th class="num">Item</th>
        <th class="num">Total</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($jumlahNota === 0): ?>
        <tr>
          <td class="empty" colspan="7">
            <?= $cari !== '' ? 'Tidak ada pembelian yang cocok.' : 'Belum ada pembelian. Klik "Tambah Pembelian".' ?>
          </td>
        </tr>
      <?php else: ?>
        <?php foreach ($daftarPembelian as $nota): ?>
          <tr>
            <td><code><?= htmlspecialchars($nota['no_pembelian']) ?></code></td>
            <td><?= htmlspecialchars($nota['tanggal']) ?></td>
            <td><?= htmlspecialchars($nota['nama_supplier'] ?? '-') ?></td>
            <td><?= htmlspecialchars($nota['nama_kasir'] ?? '-') ?></td>
            <td class="num"><?= (int) $nota['jumlah_item'] ?></td>
            <td class="num"><?= rupiah($nota['total']) ?></td>
            <td>
              <a class="btn btn-sm btn-secondary"
                 href="pembelian_detail.php?no=<?= urlencode($nota['no_pembelian']) ?>">Detail</a>
              <a class="btn btn-sm btn-danger"
                 href="pembelian_batal.php?no=<?= urlencode($nota['no_pembelian']) ?>">Batalkan</a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<div class="task">
  <h3>// TUGAS</h3>
  <ol>
    <li>Buka salah satu detail pembelian, lalu perhatikan snapshot nama dan harga.</li>
    <li>Cocokkan total nota dengan hasil <code>SUM(jumlah * harga_beli)</code>.</li>
    <li>Setelah menambah pembelian, cek baris baru di tabel <code>stok</code>.</li>
  </ol>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
