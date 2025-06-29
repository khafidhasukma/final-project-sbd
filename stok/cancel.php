<?php
session_start();
include '../config/koneksi.php';

if (!isset($_GET['kode']) || !isset($_SESSION['username'])) {
  header("Location: index.php");
  exit;
}

$kode = $_GET['kode'];
$username = $_SESSION['username'];

// Cek apakah record dikunci oleh user ini, kalau iya → unlock
$conn->query("UPDATE stok 
              SET is_locked = 0, locked_by = NULL, locked_at = NULL 
              WHERE kode_brg = '$kode' AND locked_by = '$username'");

header("Location: index.php");
exit;
