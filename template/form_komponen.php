<?php
$judul = 'Komponen Form';
$halaman = 'form';
require __DIR__ . '/partials/header.php';

$sudahKirim = $_SERVER['REQUEST_METHOD'] === 'POST';

$nama = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$umur = trim($_POST['umur'] ?? '');
$tanggal = $_POST['tanggal'] ?? '';
$metode = $_POST['metode'] ?? '';
$produk = $_POST['produk'] ?? '';
$catatan = trim($_POST['catatan'] ?? '');
$setuju = isset($_POST['setuju']);

$topping = $_POST['topping'] ?? [];
if (!is_array($topping)) {
    $topping = [];
}
$topping = array_values(array_filter($topping, 'is_scalar'));
?>

<p class="tag">P02 &middot; Komponen Form</p>
<h1>Komponen-Komponen Form</h1>
<p class="lead">
  Form tidak hanya berisi teks. Ada input angka, email, password, tanggal,
  pilihan, area catatan, dan tombol. Semua komponen memakai pola yang sama:
  <code>label</code> + komponen + <code>name</code>.
</p>

<form method="post" action="form_komponen.php">
  <div class="card">
    <h2>1. Input teks dan angka</h2>
    <div class="form-grid">
      <div class="field">
        <label for="nama">Nama</label>
        <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>"
               placeholder="Contoh: Rina" required>
      </div>

      <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>"
               placeholder="rina@contoh.com" required>
      </div>

      <div class="field">
        <label for="sandi">Password</label>
        <input type="password" id="sandi" name="sandi" placeholder="Minimal 6 karakter">
        <span class="hint">Password tidak ditampilkan lagi setelah dikirim.</span>
      </div>

      <div class="field">
        <label for="umur">Umur</label>
        <input type="number" id="umur" name="umur" value="<?= htmlspecialchars($umur) ?>"
               min="10" max="100" placeholder="17" required>
      </div>

      <div class="field">
        <label for="tanggal">Tanggal Ambil</label>
        <input type="date" id="tanggal" name="tanggal" value="<?= htmlspecialchars($tanggal) ?>" required>
      </div>
    </div>

    <pre>&lt;input type="text"     name="nama"&gt;
&lt;input type="email"    name="email"&gt;
&lt;input type="password" name="sandi"&gt;
&lt;input type="number"   name="umur" min="10" max="100"&gt;
&lt;input type="date"     name="tanggal"&gt;</pre>
  </div>

  <div class="card">
    <h2>2. Pilihan: radio, checkbox, dan select</h2>
    <div class="form-grid">
      <div class="field">
        <label>Metode Bayar (radio)</label>
        <div class="choice-group">
          <label class="choice">
            <input type="radio" name="metode" value="Tunai" <?= $metode === 'Tunai' ? 'checked' : '' ?>>
            Tunai
          </label>
          <label class="choice">
            <input type="radio" name="metode" value="Transfer" <?= $metode === 'Transfer' ? 'checked' : '' ?>>
            Transfer
          </label>
          <label class="choice">
            <input type="radio" name="metode" value="QRIS" <?= $metode === 'QRIS' ? 'checked' : '' ?>>
            QRIS
          </label>
        </div>
        <span class="hint">Radio: pilih satu.</span>
      </div>

      <div class="field">
        <label>Topping (checkbox)</label>
        <div class="choice-group">
          <label class="choice">
            <input type="checkbox" name="topping[]" value="Keju" <?= in_array('Keju', $topping, true) ? 'checked' : '' ?>>
            Keju
          </label>
          <label class="choice">
            <input type="checkbox" name="topping[]" value="Cokelat" <?= in_array('Cokelat', $topping, true) ? 'checked' : '' ?>>
            Cokelat
          </label>
          <label class="choice">
            <input type="checkbox" name="topping[]" value="Kacang" <?= in_array('Kacang', $topping, true) ? 'checked' : '' ?>>
            Kacang
          </label>
        </div>
        <span class="hint">Checkbox: boleh pilih lebih dari satu.</span>
      </div>

      <div class="field">
        <label for="produk">Produk (select)</label>
        <select id="produk" name="produk" required>
          <option value="">-- Pilih produk --</option>
          <option value="Beras 5 kg" <?= $produk === 'Beras 5 kg' ? 'selected' : '' ?>>Beras 5 kg</option>
          <option value="Minyak Goreng 1 L" <?= $produk === 'Minyak Goreng 1 L' ? 'selected' : '' ?>>Minyak Goreng 1 L</option>
          <option value="Gula Pasir 1 kg" <?= $produk === 'Gula Pasir 1 kg' ? 'selected' : '' ?>>Gula Pasir 1 kg</option>
          <option value="Telur 1 kg" <?= $produk === 'Telur 1 kg' ? 'selected' : '' ?>>Telur 1 kg</option>
        </select>
      </div>
    </div>

    <pre>&lt;input type="radio" name="metode" value="Tunai"&gt;
&lt;input type="checkbox" name="topping[]" value="Keju"&gt;
&lt;select name="produk"&gt;
  &lt;option value="Beras 5 kg"&gt;Beras 5 kg&lt;/option&gt;
&lt;/select&gt;</pre>
  </div>

  <div class="card">
    <h2>3. Area teks dan tombol</h2>
    <div class="field">
      <label for="catatan">Catatan Pesanan</label>
      <textarea id="catatan" name="catatan" placeholder="Contoh: antar ke kantin sekolah"><?= htmlspecialchars($catatan) ?></textarea>
    </div>

    <div class="choice-group" style="margin-top:14px;">
      <label class="choice">
        <input type="checkbox" name="setuju" value="1" <?= $setuju ? 'checked' : '' ?>>
        Saya sudah memeriksa data pesanan.
      </label>
    </div>

    <pre>&lt;textarea name="catatan" rows="4"&gt;&lt;/textarea&gt;
&lt;button type="submit" name="kirim" value="1"&gt;Kirim&lt;/button&gt;
&lt;button type="reset"&gt;Reset&lt;/button&gt;</pre>

    <button class="btn" type="submit" name="kirim" value="1">Kirim Form</button>
    <button class="btn" type="reset">Reset</button>
  </div>
</form>

<?php if ($sudahKirim): ?>
  <div class="result">
    <h2>Ringkasan Data yang Diterima PHP</h2>
    <table>
      <thead>
        <tr>
          <th>Komponen</th>
          <th>Kunci PHP</th>
          <th>Nilai</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Nama</td>
          <td><code>$_POST['nama']</code></td>
          <td><?= $nama !== '' ? htmlspecialchars($nama) : '(kosong)' ?></td>
        </tr>
        <tr>
          <td>Email</td>
          <td><code>$_POST['email']</code></td>
          <td><?= $email !== '' ? htmlspecialchars($email) : '(kosong)' ?></td>
        </tr>
        <tr>
          <td>Password</td>
          <td><code>$_POST['sandi']</code></td>
          <td>(disembunyikan)</td>
        </tr>
        <tr>
          <td>Umur</td>
          <td><code>$_POST['umur']</code></td>
          <td><?= $umur !== '' ? htmlspecialchars($umur) : '(kosong)' ?></td>
        </tr>
        <tr>
          <td>Tanggal</td>
          <td><code>$_POST['tanggal']</code></td>
          <td><?= $tanggal !== '' ? htmlspecialchars($tanggal) : '(kosong)' ?></td>
        </tr>
        <tr>
          <td>Metode bayar</td>
          <td><code>$_POST['metode']</code></td>
          <td><?= $metode !== '' ? htmlspecialchars($metode) : '(belum dipilih)' ?></td>
        </tr>
        <tr>
          <td>Topping</td>
          <td><code>$_POST['topping']</code></td>
          <td>
            <?php if (count($topping) === 0): ?>
              (tidak ada)
            <?php else: ?>
              <?= htmlspecialchars(implode(', ', $topping)) ?>
            <?php endif; ?>
          </td>
        </tr>
        <tr>
          <td>Produk</td>
          <td><code>$_POST['produk']</code></td>
          <td><?= $produk !== '' ? htmlspecialchars($produk) : '(belum dipilih)' ?></td>
        </tr>
        <tr>
          <td>Catatan</td>
          <td><code>$_POST['catatan']</code></td>
          <td><?= $catatan !== '' ? htmlspecialchars($catatan) : '(kosong)' ?></td>
        </tr>
        <tr>
          <td>Konfirmasi</td>
          <td><code>isset($_POST['setuju'])</code></td>
          <td><?= $setuju ? 'Ya' : 'Tidak' ?></td>
        </tr>
      </tbody>
    </table>
  </div>
<?php endif; ?>

<div class="task">
  <h3>// TUGAS</h3>
  <ol>
    <li>Pilih dua topping, lalu kirim. Perhatikan bahwa <code>name="topping[]"</code> mengirim banyak nilai.</li>
    <li>Ganti <code>type="number"</code> menjadi <code>type="text"</code>. Apakah batas <code>min</code> dan <code>max</code> masih bekerja?</li>
    <li>Isi catatan dengan <code>&lt;b&gt;Penting&lt;/b&gt;</code>, lalu lihat hasilnya di tabel ringkasan.</li>
    <li>Tambah satu pilihan produk baru di <code>&lt;select&gt;</code>.</li>
  </ol>
</div>

<div class="actions-top">
  <a class="btn" href="form_output.php">Lanjut: Output Form</a>
  <a class="btn" href="form_pengenalan.php">Kembali: Pengenalan</a>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>
