<?php
session_start();
include '../config/koneksi.php';

$username = $_SESSION['client_name'];

// Ambil data global_lock untuk penjualan-tambah
$cek = $conn->query("SELECT * FROM global_lock WHERE module = 'penjualan-tambah'");
$lock = $cek->fetch_assoc();

// Jika belum ada baris, insert default
if (!$lock) {
  $conn->query("INSERT INTO global_lock (module, is_locked, locked_by, locked_at) 
                VALUES ('penjualan-tambah', 0, NULL, NULL)");
  $lock = ['is_locked' => 0, 'locked_by' => null];
}

// Jika dikunci oleh user lain
if ($lock['is_locked'] == 1 && $lock['locked_by'] !== $username) {
  $_SESSION['error'] = "Form tambah penjualan sedang digunakan oleh user lain: <b>{$lock['locked_by']}</b>.";
  header("Location: index.php");
  exit;
}

// Lock oleh user ini
$conn->query("UPDATE global_lock 
              SET is_locked = 1, locked_by = '$username', locked_at = NOW() 
              WHERE module = 'penjualan-tambah'");

// Redirect ke form tambah
header("Location: create-edit.php?mode=tambah");
exit;
