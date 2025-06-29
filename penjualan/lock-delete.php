<?php
session_start();
include '../config/koneksi.php';

if (!isset($_GET['transaksi']) || !isset($_SESSION['username'])) {
  header("Location: index.php");
  exit;
}

$transaksi = $_GET['transaksi'];
$user = $_SESSION['username'];

// Cek apakah record sedang dikunci oleh user lain
$result = $conn->query("SELECT is_locked, locked_by FROM t_jual WHERE kd_trans = '$transaksi'");
if ($result->num_rows === 0) {
  $_SESSION['error'] = "Data tidak ditemukan.";
  header("Location: index.php");
  exit;
}

$row = $result->fetch_assoc();
if ($row['is_locked'] == 1 && $row['locked_by'] !== $user) {
  $_SESSION['error'] = "Data sedang digunakan oleh user lain.";
  header("Location: index.php");
  exit;
}

// Kunci record untuk proses delete
$conn->query("UPDATE t_jual 
              SET is_locked = 1, locked_by = '$user', locked_at = NOW() 
              WHERE kd_trans = '$transaksi'");

// Arahkan ke index untuk tampilkan modal delete
header("Location: index.php?delete=$transaksi");
exit;
