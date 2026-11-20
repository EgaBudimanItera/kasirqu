<?php
// ============================================================
// kasirqu/crud/panduan_laporan.php
// Panduan belajar: membuat laporan dari data transaksi.
// Tidak memakai database, jadi tetap terbuka tanpa MySQL.
// ============================================================
$judul   = 'Panduan Laporan';
$halaman = 'panduan_laporan';
require __DIR__ . '/partials/header.php';
?>

<p class="tag">Panduan &middot; Pertemuan 17</p>
<h1>Laporan: Membaca Data, Bukan Menambah Data</h1>
<p class="lead">
  Semua modul sebelumnya <em>menulis</em> ke database. Laporan adalah latihan
  pertama kita <em>membaca</em> data secara ringkas untuk menjawab pertanyaan.
</p>

<div class="card">
  <h2>1. Laporan Itu Jawaban, Bukan Tabel</h2>
  <p>
    Pemilik toko tidak bertanya &ldquo;tampilkan semua baris
    <code>penjualan</code>&rdquo;. Yang dia tanya:
  </p>
  <ul class="steps">
    <li>&ldquo;Berapa omzet bulan ini?&rdquo;</li>
    <li>&ldquo;Berapa uang yang keluar untuk belanja?&rdquo;</li>
    <li>&ldquo;Barang apa yang hampir habis?&rdquo;</li>
    <li>&ldquo;Dari penjualan itu, kita untung berapa?&rdquo;</li>
  </ul>
  <p style="margin-bottom: 0;">
    Setiap pertanyaan jadi satu halaman laporan. Empat pertanyaan di atas =
    satu contoh + tiga tugasmu hari ini.
  </p>
</div>

<div class="card">
  <h2>2. Lima Bagian yang Selalu Ada</h2>
  <table>
    <thead>
      <tr>
        <th>Bagian</th>
        <th>Isi</th>
        <th>Contoh kode</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>1. Filter</td>
        <td>Rentang tanggal atau batas stok</td>
        <td><code>$_GET['dari']</code></td>
      </tr>
      <tr>
        <td>2. Query</td>
        <td>Ambil data sesuai filter</td>
        <td><code>BETWEEN ? AND ?</code></td>
      </tr>
      <tr>
        <td>3. Ringkasan</td>
        <td>Angka besar di atas tabel</td>
        <td><code>COUNT</code>, <code>SUM</code></td>
      </tr>
      <tr>
        <td>4. Tabel</td>
        <td>Rincian per baris</td>
        <td><code>foreach</code></td>
      </tr>
      <tr>
        <td>5. Cetak</td>
        <td>Tautan ke halaman cetak resmi (tab baru)</td>
        <td><code>target="_blank"</code></td>
      </tr>
    </tbody>
  </table>
  <p class="muted" style="margin-bottom: 0;">
    Pola ini sama untuk semua laporan. Kalau kamu hafal lima bagian ini,
    kamu bisa membuat laporan apa pun.
  </p>
</div>

<div class="card">
  <h2>3. Query Agregat: Mengubah Banyak Baris Jadi Satu Angka</h2>
  <p>
    Laporan penjualan memakai query ini. Baca pelan-pelan, satu baris satu
    penjelasan.
  </p>
  <pre><code>SELECT pj.no_penjualan,
       pj.tanggal,
       COALESCE(k.nama_kasir, '-') AS nama_kasir,
       SUM(d.jumlah) AS total_item,
       SUM(d.jumlah * d.harga_satuan) AS total_nilai
FROM penjualan pj
JOIN detail_penjualan d ON d.no_penjualan = pj.no_penjualan
LEFT JOIN kasir k ON k.id_kasir = pj.id_kasir
WHERE pj.tanggal BETWEEN ? AND ?
GROUP BY pj.no_penjualan, pj.tanggal, k.nama_kasir
ORDER BY pj.tanggal DESC, pj.no_penjualan DESC</code></pre>

  <table>
    <thead>
      <tr>
        <th>Potongan</th>
        <th>Artinya</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>JOIN</code></td>
        <td>Ambil baris yang punya pasangan di kedua tabel.</td>
      </tr>
      <tr>
        <td><code>LEFT JOIN</code></td>
        <td>Tetap tampilkan nota walaupun kasirnya sudah dihapus.</td>
      </tr>
      <tr>
        <td><code>COALESCE(x, '-')</code></td>
        <td>Kalau hasilnya <code>NULL</code>, ganti dengan tanda strip.</td>
      </tr>
      <tr>
        <td><code>BETWEEN ? AND ?</code></td>
        <td>Filter dua tanggal sekaligus. Tanda tanya diisi prepared statement.</td>
      </tr>
      <tr>
        <td><code>SUM(jumlah * harga)</code></td>
        <td>Kalikan dulu per baris, baru dijumlahkan.</td>
      </tr>
      <tr>
        <td><code>GROUP BY</code></td>
        <td>Satu baris hasil = satu nota, bukan satu barang.</td>
      </tr>
    </tbody>
  </table>

  <div class="alert ok">
    <strong>Aturan penting:</strong> semua kolom di <code>SELECT</code> yang
    bukan hasil <code>SUM</code>/<code>COUNT</code> harus muncul di
    <code>GROUP BY</code>. Kalau tidak, MySQL bisa menolak atau memberi angka
    yang menyesatkan.
  </div>
</div>

<div class="card">
  <h2>4. Kenapa Total Dihitung dari Detail?</h2>
  <p>
    Tabel <code>penjualan</code> hanya menyimpan kepala nota (tanggal, kasir).
    Uangnya ada di <code>detail_penjualan</code>. Jadi total selalu:
  </p>
  <pre><code>SUM(jumlah * harga_satuan)</code></pre>
  <p style="margin-bottom: 0;">
    Harga di detail adalah <em>snapshot</em>: harga saat barang itu dijual.
    Inilah sebabnya laporan lama tetap benar walaupun harga produk berubah
    hari ini.
  </p>
</div>

<div class="card">
  <h2>5. Filter Tanggal yang Aman</h2>
  <p>
    Tiga hal kecil yang sering dilupakan:
  </p>
  <ol class="steps">
    <li>
      Beri nilai default. Kalau kosong, pakai awal dan akhir bulan ini:
      <code>date('Y-m-01')</code> dan <code>date('Y-m-t')</code>.
    </li>
    <li>
      Periksa format tanggal sebelum dipakai. Kalau tidak valid,
      kembalikan ke default, jangan diteruskan ke query.
    </li>
    <li>
      Kalau tanggal awal lebih besar dari tanggal akhir, tukar. Ini lebih
      ramah daripada menampilkan tabel kosong.
    </li>
  </ol>
  <pre><code>if ($dari &gt; $sampai) {
    [$dari, $sampai] = [$sampai, $dari];
}</code></pre>
</div>

<div class="card">
  <h2>6. Tiga Tugas Hari Ini</h2>

  <h3>Tugas 1 &mdash; Laporan Pembelian</h3>
  <p>
    <strong>Pertanyaan:</strong> berapa uang yang keluar untuk belanja barang
    pada periode ini?
  </p>
  <ul class="steps">
    <li>JOIN <code>pembelian</code> + <code>detail_pembelian</code> + <code>supplier</code>.</li>
    <li>Total per nota: <code>SUM(d.jumlah * d.harga_beli)</code>.</li>
    <li>Filter: <code>p.tanggal BETWEEN ? AND ?</code>.</li>
    <li>Kolom: nomor nota, tanggal, supplier, total.</li>
  </ul>

  <h3 style="margin-top: 18px;">Tugas 2 &mdash; Laporan Stok</h3>
  <p>
    <strong>Pertanyaan:</strong> barang apa yang hampir habis?
  </p>
  <ul class="steps">
    <li>Mulai dari <code>produk</code>, jangan dari <code>stok</code>.</li>
    <li>Ambil baris stok terakhir: <code>MAX(id_stok)</code> per produk.</li>
    <li>Stok terkini: <code>COALESCE(saldo_akhir, stok_awal)</code>.</li>
    <li>Tandai merah kalau stok &le; batas yang diisi di form.</li>
  </ul>

  <h3 style="margin-top: 18px;">Tugas 3 &mdash; Laporan Laba</h3>
  <p>
    <strong>Pertanyaan:</strong> dari penjualan itu, kita untung berapa?
  </p>
  <ul class="steps">
    <li>Omzet: <code>SUM(jumlah * harga_satuan)</code> dari detail penjualan.</li>
    <li>Modal: <code>SUM(jumlah * p.harga_beli)</code> dari tabel produk.</li>
    <li>Laba: omzet &minus; modal.</li>
    <li>Tulis catatan: laba ini <em>perkiraan</em>, karena modal memakai harga beli sekarang.</li>
  </ul>
</div>

<div class="card">
  <h2>7. Cetak di Tab Baru (Laporan Resmi)</h2>
  <p>
    Tombol <strong>Cetak</strong> tidak lagi memanggil
    <code>window.print()</code> di halaman laporan. Tombol itu sekarang adalah
    tautan yang membuka halaman khusus di tab baru:
  </p>
  <pre><code>&lt;a class="btn btn-sm"
   href="laporan_penjualan_cetak.php?dari=&lt;?= urlencode($dari) ?&gt;&amp;sampai=&lt;?= urlencode($sampai) ?&gt;"
   target="_blank" rel="noopener"&gt;Cetak&lt;/a&gt;</code></pre>
  <p>
    Kenapa repot-repot halaman baru? Karena kertasnya harus bersih. Halaman
    laporan di layar punya navbar, kartu, alert, dan tombol. Halaman cetak
    memakai <code>assets/cetak.css</code> sendiri: A4, hitam putih, kop surat,
    ringkasan, tabel rincian, dan blok tanda tangan. Tidak ada menu sama
    sekali, jadi tidak perlu menyembunyikan elemen satu per satu.
  </p>

  <h3 style="margin-top: 18px;">Tiga file yang bekerja bersama</h3>
  <table>
    <thead>
      <tr>
        <th>File</th>
        <th>Tugas</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><code>laporan_penjualan_data.php</code></td>
        <td>Validasi tanggal, query agregat, dan ringkasan. Dipakai dua halaman.</td>
      </tr>
      <tr>
        <td><code>laporan_penjualan.php</code></td>
        <td>Tampilan di layar + tautan cetak dengan filter terbawa.</td>
      </tr>
      <tr>
        <td><code>laporan_penjualan_cetak.php</code></td>
        <td>Kertas resmi: kop, metadata, ringkasan, tabel, tanda tangan.</td>
      </tr>
      <tr>
        <td><code>assets/cetak.css</code></td>
        <td>Aturan <code>@page</code> A4 dan <code>@media print</code>.</td>
      </tr>
    </tbody>
  </table>

  <p>
    Potongan kunci di CSS cetak:
  </p>
  <pre><code>@page { size: A4 portrait; margin: 16mm 14mm; }
@media print {
  .toolbar { display: none !important; }
  .halaman { width: auto; margin: 0; padding: 0; box-shadow: none; }
  .rincian thead { display: table-header-group; }
}</code></pre>
  <p style="margin-bottom: 0;">
    <code>table-header-group</code> membuat judul tabel ikut terulang kalau
    laporan lebih dari satu halaman. Uji dengan tombol
    <strong>Cetak / Simpan PDF</strong>, lalu pilih <em>Save as PDF</em>.
    Halaman cetak juga tetap memakai <code>wajib_role('admin')</code>, jadi
    kasir tidak bisa membuka URL-nya langsung.
  </p>
</div>

<div class="card">
  <h2>8. Kesalahan yang Sering Terjadi</h2>
  <table>
    <thead>
      <tr>
        <th>Gejala</th>
        <th>Penyebab</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Total dobel</td>
        <td>JOIN ke tabel lain tanpa <code>GROUP BY</code> yang benar.</td>
      </tr>
      <tr>
        <td>Tabel kosong padahal ada data</td>
        <td>Format tanggal salah, atau filter memakai <code>=</code> bukan <code>BETWEEN</code>.</td>
      </tr>
      <tr>
        <td>Produk baru tidak muncul</td>
        <td>Memakai <code>JOIN</code> biasa, seharusnya <code>LEFT JOIN</code>.</td>
      </tr>
      <tr>
        <td>Muncul &ldquo;0&rdquo; padahal harusnya kosong</td>
        <td>Pakai <code>COALESCE</code> atau periksa <code>COUNT</code> sebelum menampilkan tabel.</td>
      </tr>
      <tr>
        <td>Halaman error saat kasir membuka</td>
        <td>Itu benar. Berarti <code>wajib_role('admin')</code> bekerja.</td>
      </tr>
      <tr>
        <td>Navbar ikut tercetak</td>
        <td>Halaman cetak tidak boleh memakai <code>partials/header.php</code>.</td>
      </tr>
      <tr>
        <td>Angka di kertas beda dengan layar</td>
        <td>Dua halaman memakai query berbeda. Pakai helper yang sama.</td>
      </tr>
    </tbody>
  </table>
</div>

<div class="actions-top">
  <a class="btn" href="laporan_penjualan.php">Buka Contoh Laporan</a>
  <a class="btn btn-secondary" href="laporan_penjualan_cetak.php">Buka Halaman Cetak</a>
  <a class="btn btn-secondary" href="../crud_latihan/panduan_laporan.php">Versi Latihan</a>
  <a class="btn btn-secondary" href="panduan_session.php">Kembali: Panduan Session</a>
  <a class="btn btn-secondary" href="panduan.php">Kembali ke Panduan</a>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
