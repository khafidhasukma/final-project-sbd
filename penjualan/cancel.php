<?php
session_start();
include '../config/koneksi.php';

$user = $_SESSION['username'] ?? 'anonymous@' . $_SERVER['REMOTE_ADDR'];
$id = $_GET['id'] ?? '';

// 🔓 Unlock MODE EDIT (jika ada id)
if ($id) {
  $conn->query("UPDATE t_jual 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE kd_trans = '$id' AND locked_by = '$user'");
} else {
  // 🔓 Unlock semua record yang dikunci user ini (misalnya modal hapus)
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