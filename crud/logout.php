<?php
// ============================================================
// kasirqu/crud/logout.php
// Hapus data login KasirQu. Hanya menerima POST.
// ============================================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

unset($_SESSION['kasirqu']);
session_regenerate_id(true);

header('Location: login.php?status=logout');
exit;
