<?php
session_start();
include '../config/koneksi.php';

$username = $_SESSION['username'] ?? 'anonymous';

// Cek apakah ada lock aktif untuk tambah data
$cek = $conn->query("SELECT * FROM global_lock WHERE module = 'stok-tambah'");
$lock = $cek->fetch_assoc();

if ($lock['is_locked'] == 1 && $lock['locked_by'] !== $username) {
  $_SESSION['error'] = "User lain sedang menambahkan record.";
  header("Location: index.php");
  exit;
}

// ❌ GAK PERLU INSERT DULU

// Lock module tambah stok
$conn->query("UPDATE global_lock 
              SET is_locked = 1, locked_by = '$username', locked_at = NOW()
              WHERE module = 'stok-tambah'");

header("Location: create-edit.php");
exit;
