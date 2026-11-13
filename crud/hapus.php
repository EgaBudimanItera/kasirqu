<?php
// ============================================================
// kasirqu/crud/hapus.php
// DELETE produk dengan dua pengaman:
//   1. Konfirmasi lewat POST, bukan link GET.
//   2. Produk yang sudah dipakai di nota pembelian TIDAK dihapus.
//      Riwayat nota harus tetap utuh (nama & harga di detail = snapshot).
// ============================================================
$judul = 'Hapus Produk';
$halaman = 'produk';
require __DIR__ . '/../config/database.php';

$kode = strtoupper(trim($_GET['kode'] ?? $_POST['kode_produk'] ?? ''));
$error = [];
$produk = null;
$dipakai = 0;

if ($kode === '') {
    $error[] = 'Kode produk tidak dikirim. Pilih produk dari halaman daftar.';
} else {
    $stmt = mysqli_prepare(
        $koneksi,
        'SELECT kode_produk, nama_produk, harga_beli, harga_jual, stok_awal
         FROM produk WHERE kode_produk = ?'
    );

    if (!$stmt) {
        $error[] = 'Tabel produk belum siap: ' . mysqli_error($koneksi);
    } else {
        mysqli_stmt_bind_param($stmt, 's', $kode);
        mysqli_stmt_execute($stmt);
        $hasil = mysqli_stmt_get_result($stmt);
        $produk = mysqli_fetch_assoc($hasil);
        mysqli_stmt_close($stmt);

        if (!$produk) {
            $error[] = 'Produk dengan kode ' . $kode . ' tidak ditemukan.';
        }
    }
}

// Pengaman: hitung berapa baris nota pembelian yang memakai produk ini.
if ($produk) {
    $stmt = mysqli_prepare(
        $koneksi,
        'SELECT COUNT(*) AS jumlah FROM detail_pembelian WHERE kode_produk = ?'
    );

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $kode);
        mysqli_stmt_execute($stmt);
        $hasil = mysqli_stmt_get_result($stmt);
        if ($hasil && $baris = mysqli_fetch_assoc($hasil)) {
            $dipakai = (int) $baris['jumlah'];
        }
        mysqli_stmt_close($stmt);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $produk) {
    if ($dipakai > 0) {
        $error[] = 'Produk ini dipakai di ' . $dipakai . ' baris nota pembelian, '
            . 'jadi tidak boleh dihapus. Riwayat nota harus tetap utuh.';
    } else {
        $stmt = mysqli_prepare($koneksi, 'DELETE FROM produk WHERE kode_produk = ?');

        if (!$stmt) {
            $error[] = 'Gagal menyiapkan query: ' . mysqli_error($koneksi);
        } else {
            mysqli_stmt_bind_param($stmt, 's', $kode);

            if (mysqli_stmt_execute($stmt)) {
                header('Location: index.php?status=hapus');
                exit;
            }

            $error[] = 'Gagal menghapus produk: ' . mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

require __DIR__ . '/partials/header.php';
?>

<p class="tag">CRUD &middot; DELETE</p>
<h1>Hapus Produk</h1>
<p class="lead">
  Halaman ini meminta konfirmasi sebelum menghapus. Data hanya dihapus setelah
  kamu menekan tombol konfirmasi (metode POST), bukan saat link dibuka. Produk
  yang sudah dipakai transaksi akan ditolak oleh pengaman di bawah.
</p>

<?php if (count($error) > 0): ?>
  <div class="alert error">
    <strong>Produk tidak bisa diproses:</strong>
    <ul>
      <?php foreach ($error as $pesan): ?>
        <li><?= htmlspecialchars($pesan) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php if ($produk): ?>
  <div class="card">
    <h2>Produk yang Akan Dihapus</h2>
    <table>
      <tbody>
        <tr>
          <th>Kode Produk</th>
          <td><code><?= htmlspecialchars($produk['kode_produk']) ?></code></td>
        </tr>
        <tr>
          <th>Nama Produk</th>
          <td><?= htmlspecialchars($produk['nama_produk']) ?></td>
        </tr>
        <tr>
          <th>Harga Beli</th>
          <td>Rp <?= number_format((float) $produk['harga_beli'], 0, ',', '.') ?></td>
        </tr>
        <tr>
          <th>Harga Jual</th>
          <td>Rp <?= number_format((float) $produk['harga_jual'], 0, ',', '.') ?></td>
        </tr>
        <tr>
          <th>Stok Awal</th>
          <td><?= (int) $produk['stok_awal'] ?></td>
        </tr>
        <tr>
          <th>Dipakai di Nota</th>
          <td>
            <strong><?= $dipakai ?></strong> baris
            (<code>detail_pembelian</code>)
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <?php if ($dipakai > 0): ?>
    <div class="alert error">
      <strong>Penghapusan diblokir.</strong>
      Produk ini sudah tercatat di <?= $dipakai ?> baris nota pembelian. Kalau
      dihapus, nota lama kehilangan jejak barangnya. Di aplikasi nyata,
      gunakan status nonaktif (<em>soft delete</em>) daripada menghapus.
    </div>

    <div class="actions-top">
      <a class="btn btn-secondary" href="index.php">Kembali ke Daftar Produk</a>
    </div>
  <?php else: ?>
    <div class="alert error">
      <strong>Peringatan:</strong> produk yang belum dipakai transaksi boleh
      dihapus. Setelah dihapus, data tidak bisa dikembalikan.
    </div>

    <form method="post" action="hapus.php">
      <input type="hidden" name="kode_produk" value="<?= htmlspecialchars($kode) ?>">
      <div class="actions-top">
        <button class="btn btn-danger" type="submit">Ya, Hapus Produk</button>
        <a class="btn btn-secondary" href="index.php">Batal</a>
      </div>
    </form>
  <?php endif; ?>
<?php else: ?>
  <div class="actions-top">
    <a class="btn" href="index.php">Kembali ke Daftar Produk</a>
  </div>
<?php endif; ?>

<div class="task">
  <h3>// CATATAN</h3>
  <p>
    Query <code>DELETE FROM produk WHERE kode_produk = ?</code> hanya menghapus
    satu baris. Tanpa <code>WHERE</code>, semua data produk bisa hilang.
  </p>
  <p>
    Sebelum menghapus, kode ini menjalankan
    <code>SELECT COUNT(*) FROM detail_pembelian WHERE kode_produk = ?</code>.
    Kalau hasilnya lebih dari 0, penghapusan dibatalkan.
  </p>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
