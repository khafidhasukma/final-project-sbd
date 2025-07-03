<?php
session_start();
include '../config/koneksi.php';

$user = $_SESSION['client_name'];
$kode = $_GET['kode'] ?? '';

// Unlock MODE EDIT
if ($kode) {
  $conn->query("UPDATE stok 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE kode_brg = '$kode' AND locked_by = '$user'");
} else {
  // Unlock sisa record yang terlanjur di-lock tanpa kode (misalnya akibat modal hapus)
  $conn->query("UPDATE stok 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE is_locked = 1 AND locked_by = '$user'");
}

// Unlock MODE TAMBAH
$conn->query("UPDATE global_lock 
              SET is_locked = 0, locked_by = NULL, locked_at = NULL 
              WHERE module = 'stok-tambah' AND locked_by = '$user'");

header("Location: index.php");
exit;