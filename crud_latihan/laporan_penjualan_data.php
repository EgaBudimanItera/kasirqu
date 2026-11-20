<?php
// ============================================================
// kasirqu/crud_latihan/laporan_penjualan_data.php
// Versi latihan dari helper laporan penjualan.
//
// Sengaja disalin supaya kamu bisa bereksperimen tanpa merusak contoh
// di ../crud/laporan_penjualan_data.php.
//
// Dipakai bersama oleh:
//   - laporan_penjualan.php       (tampilan di layar)
//   - laporan_penjualan_cetak.php (halaman cetak resmi, tab baru)
// ============================================================

if (!function_exists('tanggal_laporan_sah')) {
    function tanggal_laporan_sah(string $teks): bool
    {
        $d = DateTime::createFromFormat('Y-m-d', $teks);
        return $d !== false && $d->format('Y-m-d') === $teks;
    }
}

if (!function_exists('periode_laporan_penjualan')) {
    /**
     * Ambil dan amankan filter tanggal dari URL.
     * @return array{0:string,1:string,2:string} [$dari, $sampai, $pesanError]
     */
    function periode_laporan_penjualan(): array
    {
        $awalBulan  = date('Y-m-01');
        $akhirBulan = date('Y-m-t');
        $dari       = trim($_GET['dari'] ?? $awalBulan);
        $sampai     = trim($_GET['sampai'] ?? $akhirBulan);
        $pesanError = '';

        if (!tanggal_laporan_sah($dari) || !tanggal_laporan_sah($sampai)) {
            $pesanError = 'Format tanggal harus YYYY-MM-DD. Filter dikembalikan ke bulan ini.';
            $dari       = $awalBulan;
            $sampai     = $akhirBulan;
        } elseif ($dari > $sampai) {
            $pesanError = 'Tanggal awal lebih besar dari tanggal akhir, jadi keduanya ditukar otomatis.';
            [$dari, $sampai] = [$sampai, $dari];
        }

        return [$dari, $sampai, $pesanError];
    }
}

if (!function_exists('data_laporan_penjualan')) {
    /**
     * TODO 1: coba tambahkan filter metode bayar pada query di bawah.
     * TODO 2: coba ubah ORDER BY menjadi tanggal ASC, lalu lihat bedanya
     *         di layar dan di halaman cetak.
     *
     * @return array{0:array,1:string} [$daftar, $pesanError]
     */
    function data_laporan_penjualan($koneksi, string $dari, string $sampai): array
    {
        $sql = "SELECT pj.no_penjualan,
                       pj.tanggal,
                       pj.metode_bayar,
                       COALESCE(k.nama_kasir, '-') AS nama_kasir,
                       SUM(d.jumlah) AS total_item,
                       SUM(d.jumlah * d.harga_satuan) AS total_nilai
                FROM penjualan pj
                JOIN detail_penjualan d ON d.no_penjualan = pj.no_penjualan
                LEFT JOIN kasir k ON k.id_kasir = pj.id_kasir
                WHERE pj.tanggal BETWEEN ? AND ?
                GROUP BY pj.no_penjualan, pj.tanggal, pj.metode_bayar, k.nama_kasir
                ORDER BY pj.tanggal DESC, pj.no_penjualan DESC";

        $stmt = mysqli_prepare($koneksi, $sql);
        $daftar = [];
        $pesanError = '';

        if (!$stmt) {
            return [$daftar, mysqli_error($koneksi)];
        }

        mysqli_stmt_bind_param($stmt, 'ss', $dari, $sampai);
        mysqli_stmt_execute($stmt);
        $hasil = mysqli_stmt_get_result($stmt);

        if ($hasil) {
            while ($baris = mysqli_fetch_assoc($hasil)) {
                $daftar[] = $baris;
            }
        } else {
            $pesanError = mysqli_error($koneksi);
        }

        mysqli_stmt_close($stmt);
        return [$daftar, $pesanError];
    }
}

if (!function_exists('ringkasan_laporan_penjualan')) {
    /**
     * @return array{jumlahNota:int,totalItem:int,totalNilai:float,rataNota:float}
     */
    function ringkasan_laporan_penjualan(array $daftar): array
    {
        $jumlahNota = count($daftar);
        $totalItem  = 0;
        $totalNilai = 0.0;

        foreach ($daftar as $nota) {
            $totalItem  += (int) $nota['total_item'];
            $totalNilai += (float) $nota['total_nilai'];
        }

        return [
            'jumlahNota' => $jumlahNota,
            'totalItem'  => $totalItem,
            'totalNilai' => $totalNilai,
            'rataNota'   => $jumlahNota > 0 ? $totalNilai / $jumlahNota : 0.0,
        ];
    }
}
