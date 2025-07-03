<?php
session_start();
include '../config/koneksi.php';

$user = $_SESSION['client_name'];  // Gunakan client_name untuk konsistensi
$id = $_GET['transaksi'] ?? '';

// Validasi ID
if (!$id) {
  $_SESSION['error'] = "ID transaksi tidak valid.";
  header("Location: index.php");
  exit;
}

// Cek apakah data transaksi ada
$q = $conn->query("SELECT is_locked, locked_by FROM t_jual WHERE kd_trans = '$id'");
if ($q->num_rows === 0) {
  $_SESSION['error'] = "Data transaksi tidak ditemukan.";
  header("Location: index.php");
  exit;
}

$data = $q->fetch_assoc();

// Cek apakah sedang dikunci oleh user lain
if ($data['is_locked'] == 1 && $data['locked_by'] !== $user) {
  $_SESSION['error'] = "Data sedang digunakan oleh user lain: {$data['locked_by']}.";
  header("Location: index.php");
  exit;
}

// Kunci record untuk user ini
$conn->query("UPDATE t_jual 
              SET is_locked = 1, locked_by = '$user', locked_at = NOW() 
              WHERE kd_trans = '$id'");

// Redirect ke index untuk tampilkan modal hapus otomatis
header("Location: index.php?delete=$id");
exit;
