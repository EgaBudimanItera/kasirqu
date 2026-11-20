<?php
// ============================================================
// kasirqu/crud/buat_hash.php
// ALAT BANTU P17: membuat hash password untuk akun demo.
//
// Halaman ini TIDAK memakai database dan TIDAK menyimpan apa pun.
// Buka di browser, ketik password, salin hash, tempel ke SQL.
// Dipakai sebagai pasangan slide "Cara Membuat Hash Sendiri".
//
// Cara lain (terminal):
//   php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"
// ============================================================

$hash = '';
$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password'] ?? '');

    if ($password === '') {
        $pesan = 'Password belum diisi.';
    } elseif (strlen($password) > 100) {
        $pesan = 'Password terlalu panjang (maksimal 100 karakter).';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Hash Password - KasirQu</title>
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      background: #F4F6FA;
      color: #262B33;
      font-family: "Segoe UI", Tahoma, sans-serif;
      line-height: 1.6;
    }
    header {
      background: #1F3864;
      color: #fff;
      padding: 18px 20px;
    }
    header strong { color: #E87D0E; }
    .wrap {
      max-width: 820px;
      margin: 28px auto;
      padding: 0 18px 48px;
    }
    .card {
      background: #fff;
      border: 1px solid #E1E6EE;
      border-radius: 12px;
      padding: 22px;
      margin-bottom: 18px;
      box-shadow: 0 2px 10px rgba(31, 56, 100, .06);
    }
    h1 { color: #1F3864; font-size: 26px; margin: 0 0 6px; }
    h2 { color: #1F3864; font-size: 18px; margin: 0 0 10px; }
    p { margin: 0 0 10px; }
    .muted { color: #5A6370; font-size: 14px; }
    label { display: block; color: #1F3864; font-size: 13px; font-weight: 700; margin-bottom: 6px; }
    input {
      width: 100%;
      padding: 11px 12px;
      border: 1px solid #C9D2E0;
      border-radius: 8px;
      font: inherit;
      font-size: 14px;
    }
    input:focus { outline: 2px solid #E87D0E; border-color: #E87D0E; }
    .btn {
      display: inline-block;
      margin-top: 12px;
      padding: 10px 18px;
      border: 0;
      border-radius: 8px;
      background: #E87D0E;
      color: #fff;
      font: inherit;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
    }
    .btn:hover { background: #CF6D08; }
    .btn-secondary { background: #1F3864; text-decoration: none; }
    .btn-secondary:hover { background: #162A4D; }
    pre {
      background: #141E30;
      color: #E8EFF7;
      border-radius: 8px;
      padding: 14px 16px;
      overflow-x: auto;
      font-family: Consolas, "Courier New", monospace;
      font-size: 13px;
      line-height: 1.5;
      margin: 10px 0;
    }
    code {
      background: #EEF2F9;
      color: #B35300;
      padding: 1px 5px;
      border-radius: 5px;
      font-family: Consolas, "Courier New", monospace;
      font-size: 13px;
    }
    .hasil {
      background: #E4F7EC;
      border-left: 5px solid #1B8A4C;
      padding: 12px 16px;
      border-radius: 8px;
      overflow-wrap: anywhere;
      font-family: Consolas, "Courier New", monospace;
      font-size: 14px;
    }
    .alert {
      background: #FDE7E5;
      border-left: 5px solid #C0392B;
      padding: 10px 14px;
      border-radius: 8px;
      margin-bottom: 12px;
    }
    .catatan {
      background: #FFF6EA;
      border-left: 5px solid #E87D0E;
      padding: 12px 16px;
      border-radius: 0 8px 8px 0;
    }
    .aksi { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
  </style>
</head>
<body>
  <header><strong>KasirQu</strong> &middot; Alat Bantu Pertemuan 17</header>
  <main class="wrap">
    <div class="card">
      <h1>Buat Hash Password</h1>
      <p class="muted">
        Ketik password, klik tombol, lalu salin hasilnya ke kolom
        <code>password</code> di tabel <code>pengguna</code>.
      </p>

      <?php if ($pesan !== ''): ?>
        <div class="alert"><?= htmlspecialchars($pesan, ENT_QUOTES, 'UTF-8') ?></div>
      <?php endif; ?>

      <form method="post" action="buat_hash.php">
        <label for="password">Password yang ingin dipakai</label>
        <input type="text" id="password" name="password" value="" autocomplete="off"
               placeholder="misal: admin123">
        <button class="btn" type="submit">Buat Hash</button>
      </form>
    </div>

    <?php if ($hash !== ''): ?>
      <div class="card">
        <h2>Hash siap dipakai</h2>
        <div class="hasil"><?= htmlspecialchars($hash, ENT_QUOTES, 'UTF-8') ?></div>
        <p class="muted" style="margin-top: 12px;">
          Salin seluruh teks di atas, termasuk <code>$2y$10$...</code>.
        </p>
        <pre>UPDATE pengguna
SET password = '<?= htmlspecialchars($hash, ENT_QUOTES, 'UTF-8') ?>'
WHERE username = 'admin';</pre>
      </div>
    <?php endif; ?>

    <div class="card">
      <h2>Cara di terminal (opsional)</h2>
      <pre>php -r "echo password_hash('admin123', PASSWORD_DEFAULT);"</pre>
      <p class="muted" style="margin-bottom: 0;">
        Perintah di atas bisa dijalankan di terminal Laragon/XAMPP. Hasilnya sama
        bentuknya dengan file ini.
      </p>
    </div>

    <div class="card catatan">
      <strong>Ingat:</strong> hash yang sama untuk password yang sama bisa berbeda
      setiap kali dibuat karena <em>salt</em>. Itu normal. Jangan pernah menulis
      password asli ke kolom <code>password</code>, dan jangan membandingkan
      password memakai <code>==</code> &mdash; selalu <code>password_verify()</code>.
      Alat ini hanya untuk latihan lokal.
    </div>

    <div class="aksi">
      <a class="btn btn-secondary" href="panduan_session.php">Panduan Session</a>
      <a class="btn btn-secondary" href="setup_pengguna.sql">Lihat SQL Akun Demo</a>
    </div>
  </main>
</body>
</html>
