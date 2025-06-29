<?php
session_start();
include '../config/koneksi.php';

$username = $_SESSION['username'] ?? 'anonymous';

// Cek apakah ada lock aktif untuk tambah penjualan
$cek = $conn->query("SELECT * FROM global_lock WHERE module = 'penjualan-tambah'");
$lock = $cek->fetch_assoc();

if ($lock && $lock['is_locked'] == 1 && $lock['locked_by'] !== $username) {
  $_SESSION['error'] = "User lain sedang menambahkan transaksi.";
  header("Location: index.php");
  exit;
}

// Lock module tambah penjualan
$conn->query("UPDATE global_lock 
              SET is_locked = 1, locked_by = '$username', locked_at = NOW()
              WHERE module = 'penjualan-tambah'");

header("Location: create-edit.php");
exit;
