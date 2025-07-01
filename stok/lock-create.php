<?php
session_start();
include '../config/koneksi.php';

$username = $_SESSION['username'] ?? 'anonymous';

// Cek apakah ada lock aktif untuk tambah data
$cek = $conn->query("SELECT * FROM global_lock WHERE module = 'stok-tambah'");
$lock = $cek->fetch_assoc();

// Kalau ada yang ngelock dan bukan user sekarang, tolak akses
if ($lock['is_locked'] == 1 && $lock['locked_by'] !== $username) {
  $_SESSION['error'] = "Form tambah data sedang digunakan oleh user lain.";
  header("Location: index.php");
  exit;
}

// Kunci modul
$conn->query("UPDATE global_lock 
              SET is_locked = 1, locked_by = '$username', locked_at = NOW()
              WHERE module = 'stok-tambah'");

// Redirect ke form tambah dengan mode
header("Location: create-edit.php?mode=tambah");
exit;
