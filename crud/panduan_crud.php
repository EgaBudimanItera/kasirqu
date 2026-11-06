<?php
$judul = 'Panduan CRUD Produk';
$halaman = 'panduan';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">Pertemuan 14 &middot; Panduan CRUD</p>
<h1>Tambah, Edit, dan Hapus Produk</h1>
<p class="lead">
  Halaman ini menjelaskan alur CRUD pada tabel <code>produk</code>: membaca
  data, menambah produk, mengubah produk, dan menghapus produk. Potongan kode
  di bawah diambil dari file yang benar-benar dipakai aplikasi.
</p>

<div class="alert ok">
  Ingin langsung mencoba? Buka <a href="index.php">Daftar Produk</a>, lalu
  gunakan tombol Tambah, Edit, atau Hapus. Kembali ke
  <a href="panduan.php">hub panduan</a> untuk materi lain.
</div>

<div class="card">
  <h2>1. Peta halaman CRUD</h2>
  <pre>kasirqu/crud/
├── index.php      &lt;- READ   : daftar produk + pencarian
├── tambah.php     &lt;- CREATE : form + INSERT
├── edit.php       &lt;- UPDATE : form + UPDATE
├── hapus.php      &lt;- DELETE : konfirmasi + DELETE
├── koneksi.php    &lt;- tes koneksi
└── panduan.php    &lt;- hub panduan</pre>

  <table>
    <thead>
      <tr>
        <th>Halaman</th>
        <th>Metode</th>
        <th>Tugas</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>index.php</code></td>
        <td>GET</td>
        <td>Menampilkan data dan menerima kata kunci pencarian.</td>
      </tr>
      <tr>
        <td><code>tambah.php</code></td>
        <td>GET + POST</td>
        <td>GET membuka form; POST memvalidasi lalu menyimpan data.</td>
      </tr>
      <tr>
        <td><code>edit.php?kode=...</code></td>
        <td>GET + POST</td>
        <td>GET mengambil data; POST menyimpan perubahan.</td>
      </tr>
      <tr>
        <td><code>hapus.php?kode=...</code></td>
        <td>GET + POST</td>
        <td>GET menampilkan konfirmasi; POST menjalankan DELETE.</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>2. Alur besar CRUD</h2>
  <ol class="steps">
    <li>Pengguna membuka <code>index.php</code> untuk melihat daftar produk.</li>
    <li>Tombol <strong>Tambah</strong> membuka <code>tambah.php</code>.</li>
    <li>Form dikirim dengan POST, divalidasi, lalu di-<code>INSERT</code>.</li>
    <li>Jika berhasil, pengguna diarahkan ke <code>index.php?status=tambah</code>.</li>
    <li>Tombol <strong>Edit</strong> membuka <code>edit.php?kode=...</code>.</li>
    <li>Perubahan dikirim dengan POST, divalidasi, lalu di-<code>UPDATE</code>.</li>
    <li>Tombol <strong>Hapus</strong> membuka <code>hapus.php?kode=...</code>.</li>
    <li>Penghapusan baru dijalankan setelah tombol konfirmasi POST ditekan.</li>
  </ol>
</div>

<div class="card">
  <h2>3. CREATE: Tambah Produk</h2>
  <h3>Field dan aturan validasi</h3>
  <table>
    <thead>
      <tr>
        <th>Field</th>
        <th>Aturan</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>kode_produk</code></td>
        <td>Wajib, 2-10 karakter, hanya huruf/angka/tanda minus, otomatis diubah ke huruf besar.</td>
      </tr>
      <tr>
        <td><code>nama_produk</code></td>
        <td>Wajib, maksimal 50 karakter.</td>
      </tr>
      <tr>
        <td><code>harga_beli</code></td>
        <td>Wajib, angka 0 atau lebih.</td>
      </tr>
      <tr>
        <td><code>harga_jual</code></td>
        <td>Wajib, angka 0 atau lebih.</td>
      </tr>
      <tr>
        <td><code>stok_awal</code></td>
        <td>Wajib, bilangan bulat 0 atau lebih.</td>
      </tr>
    </tbody>
  </table>

  <h3>Membaca data form</h3>
  <pre>$kode = strtoupper(trim($_POST['kode_produk'] ?? ''));
$nama = trim($_POST['nama_produk'] ?? '');
$hargaBeliInput = trim((string) ($_POST['harga_beli'] ?? ''));
$hargaJualInput = trim((string) ($_POST['harga_jual'] ?? ''));
$stokInput = trim((string) ($_POST['stok_awal'] ?? ''));</pre>

  <p>
    <code>trim()</code> membuang spasi di awal/akhir. <code>?? ''</code> memberi
    nilai cadangan jika field tidak dikirim. Data harga dan stok masih berupa
    teks, jadi perlu divalidasi sebelum diubah menjadi angka.
  </p>

  <h3>Cek kode duplikat</h3>
  <pre>$cek = mysqli_prepare(
    $koneksi,
    'SELECT kode_produk FROM produk WHERE kode_produk = ?'
);
mysqli_stmt_bind_param($cek, 's', $kode);
mysqli_stmt_execute($cek);
mysqli_stmt_store_result($cek);

if (mysqli_stmt_num_rows($cek) > 0) {
    $error[] = 'Kode produk sudah dipakai.';
}
mysqli_stmt_close($cek);</pre>

  <p>
    Kode produk adalah <em>primary key</em>, jadi tidak boleh sama. Pengecekan
    dilakukan sebelum <code>INSERT</code> agar pesan errornya lebih mudah
    dipahami.
  </p>

  <h3>Simpan dengan INSERT</h3>
  <pre>$stmt = mysqli_prepare(
    $koneksi,
    'INSERT INTO produk (kode_produk, nama_produk, harga_beli, harga_jual, stok_awal)
     VALUES (?, ?, ?, ?, ?)'
);

mysqli_stmt_bind_param($stmt, 'ssddi', $kode, $nama, $hargaBeli, $hargaJual, $stok);

if (mysqli_stmt_execute($stmt)) {
    header('Location: index.php?status=tambah');
    exit;
}</pre>

  <table>
    <thead>
      <tr>
        <th>Tipe</th>
        <th>Arti</th>
        <th>Dipakai untuk</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>s</code></td>
        <td>string</td>
        <td><code>kode_produk</code>, <code>nama_produk</code></td>
      </tr>
      <tr>
        <td><code>d</code></td>
        <td>double/desimal</td>
        <td><code>harga_beli</code>, <code>harga_jual</code></td>
      </tr>
      <tr>
        <td><code>i</code></td>
        <td>integer</td>
        <td><code>stok_awal</code></td>
      </tr>
    </tbody>
  </table>

  <p class="muted">
    Urutan tipe harus sama dengan urutan variabel. Untuk CREATE, urutannya
    <code>ssddi</code>: kode, nama, harga beli, harga jual, stok.
  </p>
</div>

<div class="card">
  <h2>4. UPDATE: Edit Produk</h2>
  <p>
    Halaman edit dibuka dari tombol Edit di daftar produk:
  </p>
  <pre>&lt;a href="edit.php?kode=BRG101"&gt;Edit&lt;/a&gt;</pre>

  <p>
    Kode produk diambil dari URL, lalu data lama di-<code>SELECT</code> agar
    form langsung terisi:
  </p>
  <pre>$kode = strtoupper(trim($_GET['kode'] ?? $_POST['kode_produk'] ?? ''));

$stmt = mysqli_prepare(
    $koneksi,
    'SELECT kode_produk, nama_produk, harga_beli, harga_jual, stok_awal
     FROM produk WHERE kode_produk = ?'
);
mysqli_stmt_bind_param($stmt, 's', $kode);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$produk = mysqli_fetch_assoc($hasil);
mysqli_stmt_close($stmt);</pre>

  <p>
    Kode produk adalah <em>primary key</em>, jadi tidak boleh diubah. Di form,
    kode ditampilkan <code>readonly</code> dan dikirim lewat input hidden:
  </p>
  <pre>&lt;input type="hidden" name="kode_produk" value="BRG101"&gt;</pre>

  <h3>Simpan perubahan dengan UPDATE</h3>
  <pre>$stmt = mysqli_prepare(
    $koneksi,
    'UPDATE produk
     SET nama_produk = ?, harga_beli = ?, harga_jual = ?, stok_awal = ?
     WHERE kode_produk = ?'
);

mysqli_stmt_bind_param($stmt, 'sddis', $nama, $hargaBeli, $hargaJual, $stok, $kode);

if (mysqli_stmt_execute($stmt)) {
    header('Location: index.php?status=edit');
    exit;
}</pre>

  <p>
    Tipe <code>sddis</code> berarti: nama string, harga beli double, harga jual
    double, stok integer, lalu kode string untuk <code>WHERE</code>.
  </p>

  <div class="alert error">
    <strong>Wajib ada WHERE.</strong> Tanpa <code>WHERE kode_produk = ?</code>,
    query <code>UPDATE</code> akan mengubah semua baris di tabel produk.
  </div>
</div>

<div class="card">
  <h2>5. DELETE: Hapus Produk</h2>
  <p>
    Link hapus hanya membuka halaman konfirmasi. Data belum dihapus saat link
    dibuka:
  </p>
  <pre>&lt;a href="hapus.php?kode=BRG101"&gt;Hapus&lt;/a&gt;</pre>

  <p>
    Penghapusan baru dijalankan setelah pengguna menekan tombol konfirmasi.
    Tombol itu berada di dalam form POST:
  </p>
  <pre>&lt;form method="post" action="hapus.php"&gt;
  &lt;input type="hidden" name="kode_produk" value="BRG101"&gt;
  &lt;button type="submit"&gt;Ya, Hapus Produk&lt;/button&gt;
&lt;/form&gt;</pre>

  <h3>Query DELETE</h3>
  <pre>$stmt = mysqli_prepare($koneksi, 'DELETE FROM produk WHERE kode_produk = ?');
mysqli_stmt_bind_param($stmt, 's', $kode);

if (mysqli_stmt_execute($stmt)) {
    header('Location: index.php?status=hapus');
    exit;
}</pre>

  <p>
    Sama seperti UPDATE, <code>WHERE</code> wajib ada. Tanpa itu, semua data
    produk bisa terhapus.
  </p>
  <p class="muted">
    Untuk aplikasi nyata, produk yang sudah dipakai transaksi sebaiknya tidak
    dihapus permanen. Gunakan status nonaktif (<em>soft delete</em>) agar
    riwayat penjualan tetap utuh.
  </p>
</div>

<div class="card">
  <h2>6. GET vs POST di CRUD</h2>
  <table>
    <thead>
      <tr>
        <th>Kegiatan</th>
        <th>Metode</th>
        <th>Alasan</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Membuka daftar produk</td>
        <td>GET</td>
        <td>Tidak mengubah data.</td>
      </tr>
      <tr>
        <td>Mencari produk</td>
        <td>GET</td>
        <td>Hasil bisa dibagikan lewat URL.</td>
      </tr>
      <tr>
        <td>Membuka form tambah/edit</td>
        <td>GET</td>
        <td>Hanya menampilkan form.</td>
      </tr>
      <tr>
        <td>Menyimpan produk baru</td>
        <td>POST</td>
        <td>Mengubah isi database.</td>
      </tr>
      <tr>
        <td>Menyimpan perubahan</td>
        <td>POST</td>
        <td>Mengubah isi database.</td>
      </tr>
      <tr>
        <td>Menghapus produk</td>
        <td>POST</td>
        <td>Agar tidak terhapus hanya karena link dibuka.</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>7. Kesalahan umum</h2>
  <table>
    <thead>
      <tr>
        <th>Masalah</th>
        <th>Akibat</th>
        <th>Perbaikan</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Input tidak punya <code>name</code></td>
        <td>Data tidak terkirim ke PHP.</td>
        <td>Samakan <code>name</code> dengan kunci <code>$_POST</code>.</td>
      </tr>
      <tr>
        <td>Tipe <code>bind_param</code> salah</td>
        <td>Nilai bisa berubah atau error.</td>
        <td>Cocokkan jumlah dan urutan tipe dengan variabel.</td>
      </tr>
      <tr>
        <td>Lupa <code>WHERE</code></td>
        <td>Semua baris ikut berubah/terhapus.</td>
        <td>Selalu batasi dengan <code>kode_produk = ?</code>.</td>
      </tr>
      <tr>
        <td><code>header()</code> setelah HTML</td>
        <td><em>Headers already sent</em>.</td>
        <td>Proses POST sebelum memanggil header layout.</td>
      </tr>
      <tr>
        <td>Tidak memakai <code>htmlspecialchars</code></td>
        <td>Data pengguna bisa merusak HTML.</td>
        <td>Escape semua output teks dari database/form.</td>
      </tr>
      <tr>
        <td>Kode produk duplikat</td>
        <td><code>INSERT</code> gagal karena primary key.</td>
        <td>Cek kode sebelum menyimpan.</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>8. Checklist praktik</h2>
  <ul class="steps">
    <li>Tambah satu produk baru dan lihat pesan sukses di daftar produk.</li>
    <li>Edit nama atau harga produk tersebut, lalu pastikan tabel berubah.</li>
    <li>Hapus produk baru itu dan pastikan jumlah produk kembali.</li>
    <li>Coba tambah dengan kode yang sudah ada; pastikan ditolak.</li>
    <li>Coba isi harga negatif atau stok kosong; pastikan validasi menolak.</li>
    <li>Buka halaman edit langsung tanpa kode; pastikan muncul pesan yang jelas.</li>
  </ul>
</div>

<div class="task">
  <h3>// TUGAS</h3>
  <ol>
    <li>Jelaskan perbedaan GET dan POST pada halaman tambah dan hapus.</li>
    <li>Tunjukkan baris <code>WHERE</code> pada query UPDATE dan DELETE.</li>
    <li>Ubah satu produk, lalu cek perubahannya di phpMyAdmin.</li>
    <li>Tambahkan satu produk, lalu cari lewat kotak pencarian di <code>index.php</code>.</li>
    <li>Buat catatan: mengapa kode produk tidak boleh diedit?</li>
  </ol>
</div>

<div class="actions-top">
  <a class="btn" href="tambah.php">Coba Tambah Produk</a>
  <a class="btn btn-secondary" href="index.php">Ke Daftar Produk</a>
  <a class="btn btn-secondary" href="panduan.php">Kembali ke Panduan</a>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
