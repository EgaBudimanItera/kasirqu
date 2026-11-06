<?php
$judul = 'Tambah Produk';
$halaman = 'tambah';
require __DIR__ . '/../config/database.php';

$error = [];
$kode = '';
$nama = '';
$hargaBeliInput = '';
$hargaJualInput = '';
$stokInput = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = strtoupper(trim($_POST['kode_produk'] ?? ''));
    $nama = trim($_POST['nama_produk'] ?? '');
    $hargaBeliInput = trim((string) ($_POST['harga_beli'] ?? ''));
    $hargaJualInput = trim((string) ($_POST['harga_jual'] ?? ''));
    $stokInput = trim((string) ($_POST['stok_awal'] ?? ''));

    if ($kode === '') {
        $error[] = 'Kode produk wajib diisi.';
    } elseif (!preg_match('/^[A-Z0-9-]{2,10}$/', $kode)) {
        $error[] = 'Kode produk 2-10 karakter, hanya huruf, angka, atau tanda minus.';
    }

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
        $cek = mysqli_prepare($koneksi, 'SELECT kode_produk FROM produk WHERE kode_produk = ?');

        if (!$cek) {
            $error[] = 'Tabel produk belum siap: ' . mysqli_error($koneksi);
        } else {
            mysqli_stmt_bind_param($cek, 's', $kode);
            mysqli_stmt_execute($cek);
            mysqli_stmt_store_result($cek);

            if (mysqli_stmt_num_rows($cek) > 0) {
                $error[] = 'Kode produk ' . $kode . ' sudah dipakai. Gunakan kode lain.';
            }
            mysqli_stmt_close($cek);
        }
    }

    if (count($error) === 0) {
        $hargaBeli = (float) $hargaBeliInput;
        $hargaJual = (float) $hargaJualInput;
        $stok = (int) $stokValid;

        $stmt = mysqli_prepare(
            $koneksi,
            'INSERT INTO produk (kode_produk, nama_produk, harga_beli, harga_jual, stok_awal)
             VALUES (?, ?, ?, ?, ?)'
        );

        if (!$stmt) {
            $error[] = 'Gagal menyiapkan query: ' . mysqli_error($koneksi);
        } else {
            mysqli_stmt_bind_param($stmt, 'ssddi', $kode, $nama, $hargaBeli, $hargaJual, $stok);

            if (mysqli_stmt_execute($stmt)) {
                header('Location: index.php?status=tambah');
                exit;
            }

            $error[] = 'Gagal menyimpan produk: ' . mysqli_stmt_error($stmt);
            mysqli_stmt_close($stmt);
        }
    }
}

require __DIR__ . '/partials/header.php';
?>

<p class="tag">Pertemuan 14 &middot; CREATE</p>
<h1>Tambah Produk Baru</h1>
<p class="lead">
  Isi form di bawah, lalu klik Simpan. Data akan dikirim dengan metode POST,
  divalidasi di server, lalu disimpan ke tabel <code>produk</code> memakai
  <code>INSERT</code>.
</p>

<?php if (count($error) > 0): ?>
  <div class="alert error">
    <strong>Data belum bisa disimpan:</strong>
    <ul>
      <?php foreach ($error as $pesan): ?>
        <li><?= htmlspecialchars($pesan) ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<div class="card">
  <h2>Form Produk</h2>
  <form method="post" action="tambah.php">
    <div class="form-grid">
      <div class="field">
        <label for="kode_produk">Kode Produk</label>
        <input type="text" id="kode_produk" name="kode_produk" maxlength="10"
               value="<?= htmlspecialchars($kode) ?>" placeholder="Contoh: BRG106" required>
        <small>2-10 karakter, huruf/angka/tanda minus. Kode tidak boleh sama.</small>
      </div>

      <div class="field">
        <label for="nama_produk">Nama Produk</label>
        <input type="text" id="nama_produk" name="nama_produk" maxlength="50"
               value="<?= htmlspecialchars($nama) ?>" placeholder="Contoh: Kopi Sachet" required>
      </div>

      <div class="field">
        <label for="harga_beli">Harga Beli (Rp)</label>
        <input type="number" id="harga_beli" name="harga_beli" min="0" step="0.01"
               value="<?= htmlspecialchars($hargaBeliInput) ?>" placeholder="2500" required>
      </div>

      <div class="field">
        <label for="harga_jual">Harga Jual (Rp)</label>
        <input type="number" id="harga_jual" name="harga_jual" min="0" step="0.01"
               value="<?= htmlspecialchars($hargaJualInput) ?>" placeholder="3500" required>
      </div>

      <div class="field">
        <label for="stok_awal">Stok Awal</label>
        <input type="number" id="stok_awal" name="stok_awal" min="0" step="1"
               value="<?= htmlspecialchars($stokInput) ?>" placeholder="20" required>
      </div>
    </div>

    <div class="actions-top">
      <button class="btn" type="submit">Simpan Produk</button>
      <a class="btn btn-secondary" href="index.php">Batal</a>
    </div>
  </form>
</div>

<div class="task">
  <h3>// TUGAS</h3>
  <ol>
    <li>Coba simpan produk dengan kode yang sudah ada. Baca pesan errornya.</li>
    <li>Coba kosongkan nama produk. Pastikan validasi menolak.</li>
    <li>Setelah berhasil, buka phpMyAdmin dan cek baris baru di tabel <code>produk</code>.</li>
  </ol>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
