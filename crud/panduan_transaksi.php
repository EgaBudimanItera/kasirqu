<?php
// ============================================================
// kasirqu/crud/panduan_transaksi.php
// Panduan web: algoritma pembelian (barang masuk).
//
// Penjualan sengaja belum dibahas di sini. Polanya sama, hanya arah
// stok yang berbeda, dan akan dibangun sendiri pada sesi berikutnya.
// ============================================================
$judul = 'Panduan Transaksi';
$halaman = 'transaksi';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">Panduan</p>
<h1>Panduan Pembelian</h1>
<p class="lead">
  Semua transaksi memakai pola yang sama: <strong>kepala nota</strong>,
  <strong>isi nota</strong>, dan <strong>kartu stok</strong>. Pelajari
  urutannya sebelum menulis kode.
</p>

<div class="card">
  <h2>1. Peta Tabel</h2>
  <table>
    <thead>
      <tr>
        <th>Tabel</th>
        <th>Isi</th>
        <th>Kunci</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>pembelian</code></td>
        <td>Kepala nota pembelian: tanggal, kasir, supplier</td>
        <td><code>no_pembelian</code></td>
      </tr>
      <tr>
        <td><code>detail_pembelian</code></td>
        <td>Baris barang yang dibeli + harga beli saat itu</td>
        <td><code>id_detail</code></td>
      </tr>
      <tr>
        <td><code>stok</code></td>
        <td>Riwayat keluar-masuk barang dan saldo akhir</td>
        <td><code>id_stok</code></td>
      </tr>
      <tr>
        <td><code>kasir</code></td>
        <td>Nama kasir yang aktif, dipakai di selectbox</td>
        <td><code>id_kasir</code></td>
      </tr>
      <tr>
        <td><code>supplier</code></td>
        <td>Daftar pemasok, dipakai di selectbox</td>
        <td><code>id_supplier</code></td>
      </tr>
      <tr>
        <td><code>produk</code></td>
        <td>Barang yang bisa dibeli + <code>stok_awal</code></td>
        <td><code>kode_produk</code></td>
      </tr>
    </tbody>
  </table>
  <p class="muted">
    Tidak ada kolom total di kepala nota. Total selalu dihitung dari detail:
    <code>SUM(jumlah * harga_beli)</code>.
  </p>
</div>

<div class="card">
  <h2>2. Alur Pembelian (Barang Masuk)</h2>
  <ol class="steps">
    <li>Ambil daftar kasir aktif, supplier, dan produk.</li>
    <li>Validasi tanggal, kasir, supplier, serta setiap baris barang.</li>
    <li>Buka transaksi database: <code>mysqli_begin_transaction()</code>.</li>
    <li>Buat nomor nota, misalnya <code>PB-0005</code>.</li>
    <li>INSERT kepala nota ke <code>pembelian</code>.</li>
    <li>Untuk setiap barang: INSERT <code>detail_pembelian</code>, lalu INSERT <code>stok</code> masuk.</li>
    <li>Jika semua berhasil: <code>commit</code>. Jika ada error: <code>rollback</code>.</li>
    <li>Arahkan pengguna ke halaman detail pembelian.</li>
  </ol>

  <h3>Kepala nota</h3>
  <pre><code>$noPembelian = buat_nomor($koneksi, 'PB-', 'pembelian', 'no_pembelian');

$stmt = mysqli_prepare($koneksi,
    'INSERT INTO pembelian (no_pembelian, tanggal, id_kasir, id_supplier)
     VALUES (?, ?, ?, ?)');
mysqli_stmt_bind_param($stmt, 'ssii', $noPembelian, $tanggal, $idKasir, $idSupplier);
mysqli_stmt_execute($stmt);</code></pre>

  <h3>Stok masuk</h3>
  <pre><code>$saldoAwal  = saldo_stok_terakhir($koneksi, $kodeProduk);
$saldoAkhir = $saldoAwal + $jumlah;

// INSERT INTO stok
// (kode_produk, tanggal, jumlah_masuk, jumlah_keluar,
//  saldo_awal, saldo_akhir, no_penjualan, no_pembelian, keterangan)
// VALUES (?, ?, ?, 0, ?, ?, NULL, ?, 'Beli dari supplier')</code></pre>
</div>

<div class="card">
  <h2>3. Pola Wajib</h2>
  <ul class="steps">
    <li><strong>Snapshot:</strong> simpan <code>nama_produk</code> dan harga ke detail, supaya nota lama tidak berubah saat produk diedit.</li>
    <li><strong>Prepared statement:</strong> semua input dari form masuk lewat <code>?</code>, bukan disambung ke SQL.</li>
    <li><strong>Transaksi database:</strong> kepala + detail + stok harus berhasil bersama. Jika satu gagal, semuanya dibatalkan.</li>
    <li><strong>Validasi ganda:</strong> validasi di PHP sebelum query, lalu cek ulang di dalam transaksi.</li>
    <li><strong>Output aman:</strong> tampilkan data dengan <code>htmlspecialchars()</code>.</li>
    <li><strong>Aksi ubah data:</strong> gunakan POST, bukan link GET.</li>
  </ul>
</div>

<div class="card">
  <h2>4. Rumus Stok</h2>
  <table>
    <thead>
      <tr>
        <th>Kejadian</th>
        <th>Jumlah Masuk</th>
        <th>Jumlah Keluar</th>
        <th>Saldo Akhir</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Pembelian</td>
        <td>+ jumlah beli</td>
        <td>0</td>
        <td>saldo awal + jumlah beli</td>
      </tr>
    </tbody>
  </table>
  <p class="muted">
    Saldo awal diambil dari baris <code>stok</code> terakhir produk tersebut.
    Jika belum ada riwayat, pakai <code>produk.stok_awal</code>. Kolom
    <code>jumlah_keluar</code> sudah disiapkan untuk transaksi barang keluar
    pada sesi berikutnya.
  </p>
</div>

<div class="task">
  <h3>// TUGAS</h3>
  <ol>
    <li>Buat pembelian 2 produk, lalu periksa 2 baris baru di tabel <code>stok</code>.</li>
    <li>Coba simpan nota dengan harga kosong, lalu jelaskan mengapa data tidak tersimpan.</li>
    <li>Ubah harga beli produk di halaman edit, lalu buka nota lama. Harga di nota harus tetap.</li>
  </ol>
</div>

<div class="actions-top">
  <a class="btn" href="pembelian.php">Daftar Pembelian</a>
  <a class="btn btn-secondary" href="transaksi.php">Hub Transaksi</a>
  <a class="btn btn-secondary" href="presentasi.php">Slide P16</a>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
