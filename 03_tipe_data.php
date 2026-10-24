<?php
// ============================================================
// Latihan 3 — Tipe Data
// Tujuan : mengenal jenis-jenis isi kotak (variabel).
// Buka   : http://localhost/smk4/kasirqu/03_tipe_data.php
//
// PHP otomatis tahu tipe data dari isinya. Tapi penting untuk
// paham bedanya, karena memengaruhi perhitungan & penulisan.
// ============================================================

$teks      = "Toko Maju Jaya";   // string  : kumpulan huruf/teks
$jumlah    = 20;                 // integer : bilangan bulat
$harga     = 3500.50;            // float   : bilangan desimal
$stok_ada  = true;               // boolean : benar / salah saja (true / false)
$produk    = ["Indomie", "Teh Pucuk", "Kopi"]; // array berindeks: daftar banyak nilai

$buah          = ["Apel", "Jeruk", "Mangga"];  // array berindeks
$produk_satu   = [                             // array asosiatif
    "kode"  => "P01",
    "nama"  => "Mie Instan",
    "harga" => 3500
];
$daftar_produk = [                             // array bertingkat (array of arrays)
    [ "kode" => "P01", "nama" => "Mie Instan", "harga" => 3500 ],
    [ "kode" => "P02", "nama" => "Teh Botol",  "harga" => 4000 ],
    [ "kode" => "P03", "nama" => "Roti",       "harga" => 5000 ],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Latihan 3 — Tipe Data</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <header class="topbar">
    <div class="topbar-inner">
      <span class="brand">KasirQu <small>/ Toko Maju Jaya</small></span>
      <a class="back" href="index.php">&larr; Menu Latihan</a>
    </div>
  </header>

  <main class="wrap">
    <p class="tag">Latihan 3</p>
    <h1>Tipe Data: Jenis Isi Kotak</h1>
    <p class="lead">Setiap nilai punya "jenis". PHP bisa menebak jenisnya otomatis.</p>

    <table>
      <tr><th>Tipe</th><th>Contoh nilai</th><th>Isi variabel</th></tr>
      <tr><td>string</td><td>"Toko Maju Jaya"</td><td><?php echo $teks; ?></td></tr>
      <tr><td>integer</td><td>20</td><td><?php echo $jumlah; ?></td></tr>
      <tr><td>float</td><td>3500.50</td><td><?php echo $harga; ?></td></tr>
      <tr><td>boolean</td><td>true / false</td><td><?php echo $stok_ada ? "true (stok ada)" : "false (stok habis)"; ?></td></tr>
      <tr><td>array</td><td>["Indomie", "Teh Pucuk", "Kopi"]</td><td><?php echo implode(", ", $produk); ?></td></tr>
    </table>

    <div class="card">
      <h2>Array: Berindeks vs Asosiatif vs Bertingkat</h2>

      <p><strong>A) Array Berindeks (Indexed Array)</strong> &mdash; diakses pakai nomor <code>0, 1, 2, ...</code></p>
      <p>Analogi: <strong>rak berurutan</strong>, tiap rak punya nomor urut.</p>
      <pre>&lt;?php
$buah = ["Apel", "Jeruk", "Mangga"];
echo $buah[0]; // Apel
echo $buah[1]; // Jeruk
echo $buah[2]; // Mangga
?&gt;</pre>
      <p><strong>Hasil:</strong> <?php echo $buah[0]; ?> | <?php echo $buah[1]; ?> | <?php echo $buah[2]; ?></p>
      <p><span class="pill no">Ingat</span> nomor array dimulai dari <strong>0</strong>, bukan 1.</p>

      <hr style="border:0;border-top:1px solid #E1E6EE;margin:16px 0;">

      <p><strong>B) Array Asosiatif (Associative Array)</strong> &mdash; diakses pakai <strong>label</strong> buatan sendiri</p>
      <p>Analogi: <strong>laci berlabel</strong>, bukan bernomor tapi diberi nama. Cocok untuk <strong>data satu produk</strong>.</p>
      <pre>&lt;?php
$produk_satu = [
  "kode"  => "P01",
  "nama"  => "Mie Instan",
  "harga" => 3500
];

echo $produk_satu["nama"];  // Mie Instan
echo $produk_satu["harga"]; // 3500
?&gt;</pre>
      <p><strong>Hasil:</strong> Kode: <strong><?php echo $produk_satu["kode"]; ?></strong> | Nama: <strong><?php echo $produk_satu["nama"]; ?></strong> | Harga: <strong>Rp <?php echo number_format($produk_satu["harga"], 0, ',', '.'); ?></strong></p>

      <hr style="border:0;border-top:1px solid #E1E6EE;margin:16px 0;">

      <p><strong>C) Array Bertingkat / Array of Arrays</strong> &mdash; <em>"daftar yang tiap isinya juga berupa array"</em></p>
      <p>Analogi: <strong>keranjang berisi beberapa kardus</strong>. Tiap kardus berisi data lengkap <strong>satu produk</strong> (array asosiatif).</p>
      <pre>&lt;?php
$daftar_produk = [
  [ "kode" => "P01", "nama" => "Mie Instan", "harga" => 3500 ],
  [ "kode" => "P02", "nama" => "Teh Botol",  "harga" => 4000 ],
  [ "kode" => "P03", "nama" => "Roti",       "harga" => 5000 ],
];
?&gt;</pre>

      <p><strong>Cara aksesnya (baca dari luar ke dalam):</strong></p>
      <pre>&lt;?php
// Langkah 1: pecah dulu biar lebih paham
$kardus = $daftar_produk[0];  // ambil kardus ke-0
echo $kardus["nama"];         // buka kardus, ambil nama

// Langkah 2: bisa disingkat langsung
echo $daftar_produk[0]["nama"];  // hasil sama: Mie Instan
echo $daftar_produk[1]["harga"]; // 4000
?&gt;</pre>

      <p><strong>Tampilan ke dalam tabel:</strong></p>
      <table>
        <tr><th>Kode</th><th>Nama Produk</th><th>Harga</th></tr>
        <?php
        foreach ($daftar_produk as $p) {
            echo "<tr><td>" . $p["kode"] . "</td><td>" . $p["nama"] . "</td><td>Rp " . number_format($p["harga"], 0, ',', '.') . "</td></tr>";
        }
        ?>
      </table>

      <div class="task" style="margin-top:14px;">
        <h3 style="margin-top:0;">// LATIHAN CEPAT</h3>
        <ol>
          <li>Coba tampilkan <code><?php echo htmlspecialchars('$daftar_produk[1]["nama"]'); ?></code> pakai <code>echo</code>.</li>
          <li>Tantangan: tambahkan 1 produk lagi ke <code>$daftar_produk</code>, lalu tampilkan dengan <code>foreach</code> seperti contoh di atas.</li>
        </ol>
      </div>
      <p><span class="pill no">Kunci</span> Baca urutannya: <code>$daftar_produk[0]["nama"]</code> = <em>"ambil produk ke-0, lalu ambil label 'nama'"</em>.</p>
    </div>

    <div class="card">
      <h2>Trik Detektif: var_dump()</h2>
      <p><code>var_dump()</code> menampilkan <strong>tipe data + isi</strong> secara lengkap. Berguna buat mengecek array bertingkat.</p>
      <pre>&lt;?php
var_dump($teks);
var_dump($jumlah);
var_dump($stok_ada);
?&gt;</pre>
      <p>Hasil:</p>
      <pre><?php
        var_dump($teks);
        var_dump($jumlah);
        var_dump($stok_ada);
      ?></pre>
    </div>

    <div class="task">
      <h3>// TUGAS</h3>
      <ol>
        <li>Sebelum menjalankan, tebak dulu: apa tipe data dari <code>"16"</code> (pakai tanda kutip)? Cek dengan <code>var_dump("16")</code>.</li>
        <li>Bandingkan <code>var_dump(16)</code> dan <code>var_dump("16")</code>. Apa bedanya?</li>
        <li>Tantangan: tambahkan 1 produk baru ke array <code>$produk</code> di bagian atas, lalu tampilkan lagi di tabel.</li>
      </ol>
    </div>
  </main>

  <footer class="foot">KasirQu &mdash; Pertemuan 9 &middot; Latihan 3</footer>
</body>
</html>
