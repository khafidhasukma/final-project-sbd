<?php
session_start();
include '../config/koneksi.php';

$user = 'anonymous@' . $_SERVER['REMOTE_ADDR'];
$id = $_GET['id'] ?? '';

if (!$id) {
  $_SESSION['error'] = "ID transaksi tidak valid.";
  header("Location: index.php");
  exit;
}

// Cek apakah record terkunci oleh user ini
$cek = $conn->query("SELECT locked_by FROM t_jual WHERE kd_trans = '$id'");
$data = $cek->fetch_assoc();

if (!$data) {
  $_SESSION['error'] = "Data tidak ditemukan.";
} else {
  // Hapus data transaksi
  try {
    $conn->query("DELETE FROM t_jual WHERE kd_trans = '$id'");
    $_SESSION['success'] = "Transaksi berhasil dihapus.";
  } catch (Exception $e) {
    $_SESSION['error'] = "Gagal menghapus: " . $e->getMessage();
  }
}

// Lepas kunci, jika masih ada
$conn->query("UPDATE t_jual 
              SET is_locked = 0, locked_by = NULL, locked_at = NULL 
              WHERE kd_trans = '$id' AND locked_by = '$user'");

header("Location: index.php");
exit;