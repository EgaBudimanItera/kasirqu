<?php
$judul = 'Edit Produk';
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

$nama = $produk['nama_produk'] ?? '';
$hargaBeliInput = $produk ? (string) $produk['harga_beli'] : '';
$hargaJualInput = $produk ? (string) $produk['harga_jual'] : '';
$stokInput = $produk ? (string) $produk['stok_awal'] : '';
//batas akhir proses menampilkan di form
// proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $produk) {
    $nama = trim($_POST['nama_produk'] ?? '');
    $hargaBeliInput = trim((string) ($_POST['harga_beli'] ?? ''));
    $hargaJualInput = trim((string) ($_POST['harga_jual'] ?? ''));
    $stokInput = trim((string) ($_POST['stok_awal'] ?? ''));

    if ($nama === '') {
        $error[] = 'Nama produk wajib diisi.';
    } elseif (mb_strlen($nama) > 50) {
        $error[] = 'Nama produk maksimal 50 karakter.';
    }

    if ($hargaBeliInput === '' || !is_numeric($hargaBeliInput) || (float) $hargaBeliInput < 0) {
        $error[] = 'Harga beli harus berupa angka 0 atau lebih.';
    }

    if ($hargaJualInput === '' || !is_numeric($hargaJualInput) || (float) $hargaJualInput < 0) {
        $error[] = 'Harga jual harus berupa angka 0 atau lebih.';
    }

    $stokValid = filter_var($stokInput, FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 0],
    ]);
    if ($stokInput === '' || $stokValid === false) {
        $error[] = 'Stok awal harus berupa bilangan bulat 0 atau lebih.';
    }

    if (count($error) === 0) {
        $hargaBeli = (float) $hargaBeliInput;
        $hargaJual = (float) $hargaJualInput;
        $stok = (int) $stokValid;

        $stmt = mysqli_prepare(
            $koneksi,
            'UPDATE produk
             SET nama_produk = ?, harga_beli = ?, harga_jual = ?, stok_awal = ?
             WHERE kode_produk = ?'
             //update produk set nama_produk = 'apel' , harga_beli=1000
            //  where kode_produk='90000'
        );

        if (!$stmt) {
            $error[] = 'Gagal menyiapkan query: ' . mysqli_error($koneksi);
        } else {
            mysqli_stmt_bind_param($stmt, 'sddis', $nama, $hargaBeli, $hargaJual, $stok, $kode);

            if (mysqli_stmt_execute($stmt)) {
                header('Location: index.php?status=edit');
                exit;
            }

            $error[] = 'Gagal menyimpan perubahan: ' . mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}
//akhir proses update

require __DIR__ . '/partials/header.php';
?>

<p class="tag">Pertemuan 14 &middot; UPDATE</p>
<h1>Edit Produk</h1>
<p class="lead">
  Ubah data produk, lalu klik Simpan Perubahan. Kode produk tidak boleh diubah
  karena menjadi <em>primary key</em> di tabel.
</p>

<?php if (count($error) > 0): ?>
  <div class="alert error">
    <strong>Perubahan belum bisa disimpan:</strong>
    <ul>
      <?php foreach ($error as $pesan): ?>
        <li><?= htmlspecialchars($pesan) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php if ($produk): ?>
  <div class="card">
    <h2>Form Edit Produk</h2>
    <form method="post" action="edit.php">
      <input type="hidden" name="kode_produk" value="<?= htmlspecialchars($kode) ?>">

      <div class="form-grid">
        <div class="field">
          <label for="kode_produk_tampil">Kode Produk</label>
          <input type="text" id="kode_produk_tampil" value="<?= htmlspecialchars($kode) ?>" readonly>
          <small>Kode produk tidak bisa diubah.</small>
        </div>

        <div class="field">
          <label for="nama_produk">Nama Produk</label>
          <input type="text" id="nama_produk" name="nama_produk" maxlength="50"
                 value="<?= htmlspecialchars($nama) ?>" required>
        </div>

        <div class="field">
          <label for="harga_beli">Harga Beli (Rp)</label>
          <input type="number" id="harga_beli" name="harga_beli" min="0" step="0.01"
                 value="<?= htmlspecialchars($hargaBeliInput) ?>" required>
        </div>

        <div class="field">
          <label for="harga_jual">Harga Jual (Rp)</label>
          <input type="number" id="harga_jual" name="harga_jual" min="0" step="0.01"
                 value="<?= htmlspecialchars($hargaJualInput) ?>" required>
        </div>

        <div class="field">
          <label for="stok_awal">Stok Awal</label>
          <input type="number" id="stok_awal" name="stok_awal" min="0" step="1"
                 value="<?= htmlspecialchars($stokInput) ?>" required>
        </div>
      </div>

      <div class="actions-top">
        <button class="btn" type="submit">Simpan Perubahan</button>
        <a class="btn btn-secondary" href="index.php">Batal</a>
      </div>
    </form>
  </div>
<?php else: ?>
  <div class="actions-top">
    <a class="btn" href="index.php">Kembali ke Daftar Produk</a>
  </div>
<?php endif; ?>

<div class="task">
  <h3>// CATATAN</h3>
  <p>
    Perhatikan query <code>UPDATE ... WHERE kode_produk = ?</code>. Tanpa
    <code>WHERE</code>, semua baris di tabel akan ikut berubah. Selalu batasi
    update ke satu baris yang benar.
  </p>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
