<?php
$judul = 'Latihan Hapus Produk';
$halaman = 'produk';
require __DIR__ . '/../config/database.php';

$kode = strtoupper(trim($_GET['kode'] ?? $_POST['kode_produk'] ?? ''));
$error = [];
$produk = null;

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $produk) {
    // BAGIAN 1 - HAPUS DATA
    // TODO: jalankan DELETE FROM produk WHERE kode_produk = ?
    // memakai prepared statement.

    // BAGIAN 2 - REDIRECT
    // TODO: jika berhasil, arahkan ke index.php?status=hapus lalu exit.
}

require __DIR__ . '/partials/header.php';
?>

<p class="tag">Mode Latihan &middot; DELETE</p>
<h1>Hapus Produk</h1>
<p class="lead">
  Data produk sudah dibaca dan ditampilkan. Lengkapi bagian <code>TODO</code>
  untuk menjalankan penghapusan.
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
      </tbody>
    </table>
  </div>

  <form method="post" action="hapus.php">
    <input type="hidden" name="kode_produk" value="<?= htmlspecialchars($kode) ?>">
    <div class="actions-top">
      <button class="btn btn-danger" type="submit">Ya, Hapus Produk</button>
      <a class="btn btn-secondary" href="index.php">Batal</a>
    </div>
  </form>
<?php else: ?>
  <div class="actions-top">
    <a class="btn" href="index.php">Kembali ke Daftar Produk</a>
  </div>
<?php endif; ?>

<?php require __DIR__ . '/partials/footer.php'; ?>
