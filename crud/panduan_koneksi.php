<?php
$judul = 'Panduan Koneksi Database';
$halaman = 'panduan';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">Pertemuan 14 &middot; Panduan Koneksi</p>
<h1>Koneksi PHP ke MySQL: Dari File ke Query</h1>
<p class="lead">
  Halaman ini menjelaskan cara kerja koneksi database di KasirQu, alasan
  penempatan filenya, dan cara memanggilnya dari halaman CRUD. Materi ini bisa
  dibaca mandiri tanpa slide presentasi.
</p>

<div class="alert ok">
  Halaman ini hanya materi bacaan. Untuk menguji koneksi secara langsung,
  buka <a href="koneksi.php">Cek Koneksi Database</a>.
</div>

<div class="card">
  <h2>1. Tujuan</h2>
  <ul class="steps">
    <li>Menjelaskan fungsi koneksi PHP ke MySQL.</li>
    <li>Mengenali 4 parameter koneksi: host, user, password, dan database.</li>
    <li>Memahami alasan koneksi dipusatkan di folder <code>config/</code>.</li>
    <li>Memanggil koneksi dari halaman lain memakai <code>require</code> dan <code>__DIR__</code>.</li>
    <li>Membaca pesan error koneksi dan menentukan langkah perbaikannya.</li>
  </ul>
</div>

<div class="card">
  <h2>2. Peta folder</h2>
  <pre>smk4/
└── kasirqu/
    ├── config/
    │   └── database.php      &lt;- koneksi MySQL
    ├── crud/
    │   ├── index.php         &lt;- memakai koneksi
    │   ├── tambah.php        &lt;- memakai koneksi
    │   ├── edit.php          &lt;- memakai koneksi
    │   ├── hapus.php         &lt;- memakai koneksi
    │   ├── koneksi.php       &lt;- tes koneksi
    │   └── panduan_koneksi.php
    ├── form_produk/
    │   ├── produk_tambah.php &lt;- memakai koneksi
    │   ├── produk_daftar.php &lt;- memakai koneksi
    │   └── ...               &lt;- modul step-by-step
    └── template/
        └── form.php          &lt;- tidak memakai database</pre>

  <table>
    <thead>
      <tr>
        <th>Lokasi</th>
        <th>Isi</th>
        <th>Alasan</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>kasirqu/config/</code></td>
        <td>Pengaturan aplikasi</td>
        <td>Satu tempat untuk host, user, password, dan nama database.</td>
      </tr>
      <tr>
        <td><code>kasirqu/crud/</code></td>
        <td>Halaman yang butuh database</td>
        <td>Semua halaman CRUD memakai koneksi yang sama.</td>
      </tr>
      <tr>
        <td><code>kasirqu/template/</code></td>
        <td>Latihan template dan form</td>
        <td>Tetap bisa dibuka tanpa MySQL.</td>
      </tr>
    </tbody>
  </table>

  <p class="muted">
    Koneksi tidak ditaruh di root <code>smk4/</code> karena root berisi materi
    kursus, bukan bagian aplikasi KasirQu. Koneksi juga tidak diulang di setiap
    file CRUD agar tidak ada banyak salinan pengaturan.
  </p>
</div>

<div class="card">
  <h2>3. Isi <code>config/database.php</code></h2>
  <p>Versi ringkas dari file yang dipakai aplikasi:</p>
  <pre>&lt;?php
mysqli_report(MYSQLI_REPORT_OFF);

const DB_HOST = 'localhost';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'db_penjualan';

$koneksi = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$koneksi) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

mysqli_set_charset($koneksi, 'utf8mb4');</pre>

  <table>
    <thead>
      <tr>
        <th>Baris</th>
        <th>Fungsi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>mysqli_report(MYSQLI_REPORT_OFF)</code></td>
        <td>Mematikan error otomatis berbentuk exception agar pesan bisa ditangani sendiri.</td>
      </tr>
      <tr>
        <td><code>const DB_HOST</code></td>
        <td>Alamat server MySQL. Untuk latihan lokal biasanya <code>localhost</code>.</td>
      </tr>
      <tr>
        <td><code>const DB_USER</code></td>
        <td>Nama user MySQL. Laragon dan XAMPP biasanya memakai <code>root</code>.</td>
      </tr>
      <tr>
        <td><code>const DB_PASS</code></td>
        <td>Password user. Di Laragon biasanya kosong.</td>
      </tr>
      <tr>
        <td><code>const DB_NAME</code></td>
        <td>Nama database yang dibuka, yaitu <code>db_penjualan</code>.</td>
      </tr>
      <tr>
        <td><code>mysqli_connect()</code></td>
        <td>Membuka koneksi dan menyimpan hasilnya di variabel <code>$koneksi</code>.</td>
      </tr>
      <tr>
        <td><code>if (!$koneksi)</code></td>
        <td>Menghentikan halaman dan menampilkan pesan jika koneksi gagal.</td>
      </tr>
      <tr>
        <td><code>mysqli_set_charset()</code></td>
        <td>Mengatur karakter <code>utf8mb4</code> agar teks dan simbol tampil benar.</td>
      </tr>
    </tbody>
  </table>

  <p class="muted">
    File asli di <code>config/database.php</code> memakai pesan error yang lebih
    ramah, berisi petunjuk menyalakan MySQL dan mengimpor database.
  </p>
</div>

<div class="card">
  <h2>4. Arti 4 parameter koneksi</h2>
  <table>
    <thead>
      <tr>
        <th>Parameter</th>
        <th>Contoh</th>
        <th>Artinya</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>host</code></td>
        <td><code>localhost</code></td>
        <td>Komputer tempat MySQL berjalan.</td>
      </tr>
      <tr>
        <td><code>user</code></td>
        <td><code>root</code></td>
        <td>Nama pengguna MySQL.</td>
      </tr>
      <tr>
        <td><code>password</code></td>
        <td><code>(kosong)</code></td>
        <td>Password user MySQL.</td>
      </tr>
      <tr>
        <td><code>database</code></td>
        <td><code>db_penjualan</code></td>
        <td>Database yang ingin dipakai.</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>5. Cara memanggil koneksi</h2>
  <p>
    Di halaman CRUD, koneksi dipanggil dengan <code>require</code> di bagian
    paling atas file:
  </p>
  <pre>require __DIR__ . '/../config/database.php';</pre>

  <table>
    <thead>
      <tr>
        <th>File yang memanggil</th>
        <th>Kode require</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>kasirqu/form_produk/produk_tambah.php</code></td>
        <td><code>require __DIR__ . '/../config/database.php';</code></td>
      </tr>
      <tr>
        <td><code>kasirqu/crud/index.php</code></td>
        <td><code>require __DIR__ . '/../config/database.php';</code></td>
      </tr>
      <tr>
        <td><code>kasirqu/crud/tambah.php</code></td>
        <td><code>require __DIR__ . '/../config/database.php';</code></td>
      </tr>
      <tr>
        <td><code>kasirqu/template/form.php</code></td>
        <td>Tidak perlu require karena tidak memakai database.</td>
      </tr>
    </tbody>
  </table>

  <p>
    <code>__DIR__</code> adalah folder tempat file PHP berada. Dengan
    <code>__DIR__</code>, path tidak bergantung pada URL browser dan tidak
    berubah walaupun halaman dibuka dari alamat yang berbeda.
  </p>
  <p>
    <code>require</code> dipakai karena file koneksi wajib ada. Jika file tidak
    ditemukan, PHP langsung berhenti dan menampilkan error.
  </p>
</div>

<div class="card">
  <h2>6. Urutan require di halaman CRUD</h2>
  <pre>&lt;?php
require __DIR__ . '/../config/database.php';

$error = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // validasi dan query database
}

require __DIR__ . '/partials/header.php';
?&gt;</pre>

  <p>
    Koneksi dipanggil sebelum HTML dan sebelum <code>header()</code>. Tujuannya:
  </p>
  <ul class="steps">
    <li>Jika koneksi gagal, halaman berhenti sebelum HTML terkirim.</li>
    <li>Redirect <code>header('Location: ...')</code> masih bisa berjalan.</li>
    <li>Koneksi cukup dibuka sekali per request, bukan di dalam loop.</li>
  </ul>
</div>

<div class="card">
  <h2>7. Alur koneksi</h2>
  <ol class="steps">
    <li>Browser membuka halaman PHP, misalnya <code>crud/index.php</code>.</li>
    <li>Halaman memanggil <code>require ../config/database.php</code>.</li>
    <li><code>mysqli_connect()</code> membuka koneksi ke MySQL.</li>
    <li>Hasil koneksi disimpan di variabel <code>$koneksi</code>.</li>
    <li>Halaman menjalankan <code>mysqli_query()</code> atau <code>mysqli_prepare()</code>.</li>
    <li>Hasil query diubah menjadi tabel HTML.</li>
    <li>Koneksi ditutup otomatis oleh PHP saat request selesai.</li>
  </ol>
  <p class="muted">
    <code>mysqli_close($koneksi)</code> boleh dipakai, tetapi tidak wajib karena
    PHP menutup koneksi otomatis di akhir request.
  </p>
</div>

<div class="card">
  <h2>8. Kenapa tidak ditaruh di tempat lain?</h2>
  <table>
    <thead>
      <tr>
        <th>Lokasi</th>
        <th>Masalah jika dipakai</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>kasirqu/template/</code></td>
        <td>Halaman template P12-P13 jadi ikut butuh MySQL, padahal harus tetap statis.</td>
      </tr>
      <tr>
        <td>Root <code>smk4/</code></td>
        <td>Materi kursus bercampur dengan konfigurasi aplikasi.</td>
      </tr>
      <tr>
        <td>Diulang di setiap file CRUD</td>
        <td>Jika password berubah, banyak file harus diedit satu per satu.</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>9. Error umum dan solusinya</h2>
  <table>
    <thead>
      <tr>
        <th>Pesan error</th>
        <th>Penyebab dan solusi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>Connection refused</code></td>
        <td>MySQL belum menyala. Jalankan MySQL di Laragon atau XAMPP.</td>
      </tr>
      <tr>
        <td><code>Unknown database</code></td>
        <td>Database belum dibuat. Impor <code>db_penjualan</code>.</td>
      </tr>
      <tr>
        <td><code>Access denied for user</code></td>
        <td>User atau password salah. Periksa <code>DB_USER</code> dan <code>DB_PASS</code>.</td>
      </tr>
      <tr>
        <td><code>Call to undefined function mysqli_connect</code></td>
        <td>Ekstensi <code>mysqli</code> belum aktif di PHP.</td>
      </tr>
      <tr>
        <td><code>No such file or directory</code></td>
        <td>Path <code>require</code> salah. Periksa jumlah <code>../</code>.</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="card">
  <h2>10. Checklist praktik</h2>
  <ul class="steps">
    <li>MySQL menyala dan database <code>db_penjualan</code> tersedia.</li>
    <li><code>config/database.php</code> ada di folder <code>kasirqu/config/</code>.</li>
    <li>Halaman CRUD memanggil koneksi dengan <code>require</code> dan <code>__DIR__</code>.</li>
    <li><code>koneksi.php</code> menampilkan status berhasil.</li>
    <li><code>index.php</code> menampilkan data produk dari database.</li>
    <li>Tidak ada warning atau pesan error PHP di halaman.</li>
  </ul>
</div>

<div class="task">
  <h3>// TUGAS</h3>
  <ol>
    <li>Buka <code>config/database.php</code>, lalu tunjukkan baris yang menyimpan nama database.</li>
    <li>Buka <code>crud/index.php</code>, lalu tunjukkan baris <code>require</code> koneksi.</li>
    <li>Jelaskan mengapa koneksi tidak ditaruh di <code>template/</code>.</li>
    <li>Ubah sementara <code>DB_NAME</code> menjadi nama database yang salah, lalu buka <code>koneksi.php</code>. Catat pesan error yang muncul, kemudian kembalikan.</li>
    <li>Buat satu file baru <code>crud/latihan_koneksi.php</code> yang hanya memanggil koneksi dan menampilkan teks “Koneksi siap”.</li>
  </ol>
</div>

<div class="actions-top">
  <a class="btn" href="koneksi.php">Cek Koneksi Live</a>
  <a class="btn btn-secondary" href="panduan_crud.php">Lanjut: Panduan CRUD</a>
  <a class="btn btn-secondary" href="panduan.php">Kembali ke Panduan</a>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
