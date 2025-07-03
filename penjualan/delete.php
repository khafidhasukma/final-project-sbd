<?php
session_start();
include '../config/koneksi.php';

$user = $_SESSION['client_name'];
$id = $_GET['id'] ?? '';

if (!$id) {
  $_SESSION['error'] = "ID transaksi tidak valid.";
  header("Location: index.php");
  exit;
}

try {
  // ✅ Cek apakah data ada dan masih dikunci oleh user ini
  $cek = $conn->query("SELECT locked_by FROM t_jual WHERE kd_trans = '$id'");
  $data = $cek->fetch_assoc();

  if (!$data) {
    $_SESSION['error'] = "Data transaksi tidak ditemukan.";
  } elseif ($data['locked_by'] !== $user) {
    $_SESSION['error'] = "Tidak bisa menghapus. Data sedang dikunci oleh user lain: <b>{$data['locked_by']}</b>.";
  } else {
    // ✅ Hapus data menggunakan stored procedure
    $conn->query("CALL delete_penjualan('$id')");
    $_SESSION['success'] = "Transaksi berhasil dihapus.";
  }
} catch (mysqli_sql_exception $e) {
  $_SESSION['error'] = "Gagal menghapus: " . $e->getMessage();
}

// 🔓 Lepas kunci setelah selesai (berhasil atau gagal)
$conn->query("UPDATE t_jual 
              SET is_locked = 0, locked_by = NULL, locked_at = NULL 
              WHERE kd_trans = '$id' AND locked_by = '$user'");

header("Location: index.php");
exit;
