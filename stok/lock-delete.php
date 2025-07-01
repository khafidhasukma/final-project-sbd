<?php
session_start();
include '../config/koneksi.php';

if (!isset($_GET['kode']) || !isset($_SESSION['username'])) {
  header("Location: index.php");
  exit;
}

$kode = $_GET['kode'];
$username = $_SESSION['username'];

// Cek kalau record udah dikunci user lain
$result = $conn->query("SELECT is_locked, locked_by FROM stok WHERE kode_brg = '$kode'");
$row = $result->fetch_assoc();

if ($row['is_locked'] == 1 && $row['locked_by'] !== $username) {
  $_SESSION['error'] = "Record sedang digunakan oleh user lain.";
  header("Location: index.php");
  exit;
}

// Kunci record untuk proses delete
$conn->query("UPDATE stok SET is_locked = 1, locked_by = '$username', locked_at = NOW() WHERE kode_brg = '$kode'");

header("Location: index.php?delete=$kode");
exit;