<?php
// ============================================================
// Latihan 9 — CSS Dasar (# dan .)
// Tujuan : paham cara "mempercantik" HTML dengan CSS, dan tahu
//          bedanya selector CLASS (titik) vs ID (pagar).
// Buka   : http://localhost/smk4/kasirqu/09_css_dasar.php
//
// HTML  = kerangka rumah (dinding, pintu, atap).
// CSS   = cat, wallpaper, dan perabotnya.
// PHP   = listrik & mesin di dalam rumah.
// ============================================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Latihan 9 — CSS Dasar</title>
  <link rel="stylesheet" href="assets/style.css">

  <!-- CSS khusus halaman ini. Perhatikan tanda . dan # di bawah. -->
  <style>
    /* selector ELEMEN: semua <p> di dalam .demo-area */
    .demo-area p {
      background: #EEF2F9;
      border: 2px solid #1F3864;
      border-radius: 8px;
      padding: 10px 12px;
      margin: 8px 0;
    }

    /* selector CLASS: diawali TITIK (.) -> bisa dipakai banyak elemen */
    .demo-area .sorot {
      background: #FFF1DE;
      border-color: #E87D0E;
    }

    /* selector ID: diawali PAGAR (#) -> hanya untuk SATU elemen */
    .demo-area #kotak-spesial {
      background: #E4F7EC;
      border-color: #1B8A4C;
    }
  </style>
</head>
<body>
  <header class="topbar">
    <div class="topbar-inner">
      <span class="brand">KasirQu <small>/ Toko Maju Jaya</small></span>
      <a class="back" href="index.php">&larr; Menu Latihan</a>
    </div>
  </header>

  <main class="wrap">
    <p class="tag">Latihan 9</p>
    <h1>CSS Dasar: Mengenal Tanda <code>.</code> dan <code>#</code></h1>
    <p class="lead">
      Halaman yang kamu lihat sekarang sudah memakai CSS. Coba bayangkan tanpa CSS:
      semuanya jadi teks polos, hitam di atas putih. CSS yang membuatnya rapi.
    </p>

    <div class="card">
      <h2>1. CSS Itu Apa?</h2>
      <p>
        CSS singkatan dari <strong>Cascading Style Sheets</strong>. Tugasnya mengatur
        <strong>tampilan</strong>: warna, ukuran huruf, jarak, garis, dan posisi.
      </p>
      <table>
        <tr><th>Bahasa</th><th>Perannya</th><th>Analogi</th></tr>
        <tr><td>HTML</td><td>Isi &amp; struktur</td><td>Kerangka rumah</td></tr>
        <tr><td>CSS</td><td>Tampilan &amp; warna</td><td>Cat dan perabot</td></tr>
        <tr><td>PHP</td><td>Logika &amp; data</td><td>Listrik dan mesin</td></tr>
      </table>
      <p><span class="pill">Bukti</span> Semua halaman di KasirQu ini tampil rapi karena satu file: <code>assets/style.css</code>.</p>
    </div>

    <div class="card">
      <h2>2. Tiga Cara Memasang CSS</h2>

      <p><strong>A) CSS Eksternal (paling rapi, dipakai KasirQu)</strong></p>
      <p>Satu file CSS untuk banyak halaman. Ditulis di dalam <code>&lt;head&gt;</code>.</p>
      <pre>&lt;link rel="stylesheet" href="assets/style.css"&gt;</pre>

      <p><strong>B) CSS Internal</strong></p>
      <p>Ditulis di dalam tag <code>&lt;style&gt;</code>. Cocok untuk mengatur satu halaman saja.</p>
      <pre>&lt;style&gt;
  p {
    color: #1F3864;
  }
&lt;/style&gt;</pre>

      <p><strong>C) CSS Inline</strong></p>
      <p>Ditulis langsung di atribut <code>style</code> milik elemen. Paling cepat, tapi paling susah dirawat.</p>
      <pre>&lt;p style="color:#E87D0E;"&gt;Teks oranye&lt;/p&gt;</pre>
      <p><span class="pill no">Saran</span> Untuk belajar, pakai <strong>internal</strong> atau <strong>eksternal</strong>. Hindari inline kalau elemennya banyak.</p>
    </div>

    <div class="card">
      <h2>3. Selector: Cara Menunjuk Elemen</h2>
      <p>
        Selector adalah <strong>"alamat"</strong> di CSS. Kita bilang mau mengubah elemen yang mana.
        Ada tiga yang paling sering dipakai:
      </p>

      <table>
        <tr>
          <th>Jenis</th>
          <th>Ditulis di CSS</th>
          <th>Ditulis di HTML</th>
          <th>Boleh dipakai berapa elemen?</th>
        </tr>
        <tr>
          <td>Elemen (tag)</td>
          <td><code>p { ... }</code></td>
          <td><code>&lt;p&gt;</code></td>
          <td>Semua tag <code>&lt;p&gt;</code></td>
        </tr>
        <tr>
          <td><strong>Class</strong></td>
          <td><code>.sorot { ... }</code></td>
          <td><code>&lt;p class="sorot"&gt;</code></td>
          <td><strong>Banyak</strong> (bisa diulang)</td>
        </tr>
        <tr>
          <td><strong>ID</strong></td>
          <td><code>#kotak-spesial { ... }</code></td>
          <td><code>&lt;p id="kotak-spesial"&gt;</code></td>
          <td><strong>Satu saja</strong> per halaman</td>
        </tr>
      </table>

      <div class="task" style="margin-top:14px;">
        <h3 style="margin-top:0;">Cara mengingat cepat</h3>
        <ul>
          <li><strong>TITIK (.)</strong> untuk <strong>class</strong> &rarr; dipakai <strong>banyak</strong> elemen.</li>
          <li><strong>PAGAR (#)</strong> untuk <strong>id</strong> &rarr; hanya <strong>satu</strong> elemen, seperti nomor KTP.</li>
        </ul>
      </div>

      <p><strong>Contoh CSS-nya:</strong></p>
      <pre>/* class: diawali titik */
.sorot {
  background: #FFF1DE;
  border-color: #E87D0E;
}

/* id: diawali pagar */
#kotak-spesial {
  background: #E4F7EC;
  border-color: #1B8A4C;
}</pre>

      <p><strong>Contoh HTML-nya:</strong></p>
      <pre>&lt;p&gt;Kotak biasa&lt;/p&gt;
&lt;p class="sorot"&gt;Kotak class 1&lt;/p&gt;
&lt;p class="sorot"&gt;Kotak class 2&lt;/p&gt;
&lt;p id="kotak-spesial" class="sorot"&gt;Kotak id&lt;/p&gt;</pre>
    </div>

    <div class="card">
      <h2>4. Lihat Hasilnya Langsung</h2>
      <p>Perhatikan warna tiap kotak. Semuanya ditata oleh CSS di bagian <code>&lt;style&gt;</code> halaman ini.</p>

      <div class="demo-area">
        <p>Kotak 1 &mdash; hanya kena selector elemen <code>p</code> (biru)</p>
        <p class="sorot">Kotak 2 &mdash; kena <code>.sorot</code> (oranye)</p>
        <p class="sorot">Kotak 3 &mdash; class yang sama, dipakai lagi (oranye)</p>
        <p id="kotak-spesial" class="sorot">Kotak 4 &mdash; punya <code>id</code>, jadi menang (hijau)</p>
      </div>

      <p><span class="pill">Perhatikan</span> Kotak 2 dan 3 memakai class yang sama, jadi tampilannya sama persis. Inilah kekuatan class: tulis sekali, pakai berkali-kali.</p>
      <p><span class="pill ok">Bonus</span> Kotak 4 punya <code>class="sorot"</code> juga, tapi tetap hijau. Artinya <strong>ID lebih kuat daripada class</strong>.</p>
    </div>

    <div class="card">
      <h2>5. Urutan Kekuatan (Prioritas)</h2>
      <p>Kalau satu elemen kena beberapa aturan sekaligus, yang paling kuat yang menang:</p>
      <table>
        <tr><th>Urutan</th><th>Jenis</th><th>Contoh</th><th>Kekuatan</th></tr>
        <tr><td>1</td><td>Inline style</td><td><code>style="color:red"</code></td><td>Paling kuat</td></tr>
        <tr><td>2</td><td>ID</td><td><code>#kotak-spesial</code></td><td>Kuat</td></tr>
        <tr><td>3</td><td>Class</td><td><code>.sorot</code></td><td>Sedang</td></tr>
        <tr><td>4</td><td>Elemen</td><td><code>p</code></td><td>Paling lemah</td></tr>
      </table>
      <p><span class="pill no">Ingat</span> Kalau CSS tidak mau berubah, cek dulu: jangan-jangan ada aturan lain yang lebih kuat, atau file CSS belum ke-load (tekan <strong>Ctrl + F5</strong>).</p>
    </div>

    <div class="task">
      <h3>// TUGAS</h3>
      <ol>
        <li>Buka file <code>assets/style.css</code>. Cari satu selector yang diawali <code>.</code>. Sebutkan namanya.</li>
        <li>Cari juga satu selector yang diawali <code>#</code> di seluruh folder <code>kasirqu</code>. Ada atau tidak? Kenapa?</li>
        <li>Di file ini, tambahkan satu kotak baru di dalam <code>.demo-area</code> dengan class <code>sorot</code>. Apakah warnanya ikut oranye?</li>
        <li>Ganti isi <code>.sorot</code> menjadi <code>background: #E8F0FB;</code> lalu simpan dan refresh. Apa yang berubah?</li>
        <li>Tantangan: buat class baru bernama <code>.bahaya</code> dengan latar merah muda dan garis merah. Pakai di satu kotak.</li>
        <li>Tantangan lanjut: buat <code>id="kotak-utama"</code> dengan latar kuning, lalu buktikan ID menang melawan class.</li>
      </ol>
    </div>

    <div class="task">
      <h3>// Kunci Sukses</h3>
      <ul>
        <li>CSS tidak memakai tanda <code>$</code>. Kalau ada <code>$</code>, itu PHP, bukan CSS.</li>
        <li>Setiap aturan CSS dibungkus <code>{ ... }</code> dan tiap baris diakhiri <code>;</code>.</li>
        <li>Nama class dan id sebaiknya huruf kecil dan pakai tanda hubung: <code>kotak-spesial</code>, bukan <code>Kotak Spesial</code>.</li>
        <li>Tekan <strong>Ctrl + F5</strong> setiap kali CSS tidak berubah.</li>
      </ul>
    </div>
  </main>

  <footer class="foot">KasirQu &mdash; Pertemuan 9 &middot; Latihan 9</footer>
</body>
</html>
