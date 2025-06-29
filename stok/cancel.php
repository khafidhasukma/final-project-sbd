<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['username'])) {
  $_SESSION['error'] = "Login dulu dong!";
  header("Location: /final-project-sbd/login.php");
  exit;
}

$username = $_SESSION['username'];
$kode = $_GET['kode'] ?? '';

// 🔓 Kalau ada kode barang (mode edit)
if ($kode) {
  $cek = $conn->query("SELECT * FROM stok WHERE kode_brg = '$kode'");
  if ($cek->num_rows > 0) {
    $data = $cek->fetch_assoc();
    if ($data['is_locked'] == 1 && $data['locked_by'] === $username) {
      $conn->query("UPDATE stok 
                    SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                    WHERE kode_brg = '$kode'");
    }
  }
} else {
  // 🔓 Kalau tidak ada kode (mode tambah), unlock global_lock
  $conn->query("UPDATE global_lock 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE module = 'stok-tambah' AND locked_by = '$username'");
}

header("Location: index.php");
exit;
