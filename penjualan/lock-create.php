<?php
session_start();
include '../config/koneksi.php';

$user = $_SESSION['username'] ?? $_SESSION['client_name'];

// Cek lock
$q = $conn->query("SELECT * FROM global_lock WHERE module = 'penjualan-tambah'");
$lock = $q->fetch_assoc();

if ($lock && $lock['is_locked'] == 1 && $lock['locked_by'] !== $user) {
  $_SESSION['error'] = "Form tambah penjualan sedang digunakan oleh user lain.";
  header("Location: index.php");
  exit;
}

// Kunci global_lock
$conn->query("UPDATE global_lock 
  SET is_locked = 1, locked_by = '$user', locked_at = NOW() 
  WHERE module = 'penjualan-tambah'");

header("Location: create-edit.php");
exit;