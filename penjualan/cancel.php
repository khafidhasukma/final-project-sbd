<?php
session_start();
include '../config/koneksi.php';

$user = $_SESSION['client_name'];
$id = $_GET['id'] ?? '';

// 🔓 Unlock MODE EDIT (jika ada id transaksi)
if ($id) {
  $conn->query("UPDATE t_jual 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE kd_trans = '$id' AND locked_by = '$user'");
} else {
  // 🔓 Unlock semua record penjualan yang dikunci user ini (misalnya batal hapus)
  $conn->query("UPDATE t_jual 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE is_locked = 1 AND locked_by = '$user'");
}

// 🔓 Unlock MODE TAMBAH
$conn->query("UPDATE global_lock 
              SET is_locked = 0, locked_by = NULL, locked_at = NULL 
              WHERE module = 'penjualan-tambah' AND locked_by = '$user'");

header("Location: index.php");
exit;
