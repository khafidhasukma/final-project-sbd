<?php
session_start();
include '../config/koneksi.php';

$user = 'anonymous@' . $_SERVER['REMOTE_ADDR'];
$kode = $_GET['kode'] ?? '';

// Jika mode EDIT
if ($kode) {
  $conn->query("UPDATE stok 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE kode_brg = '$kode' AND locked_by = '$user'");
}

// Jika mode TAMBAH
$conn->query("UPDATE global_lock 
              SET is_locked = 0, locked_by = NULL, locked_at = NULL 
              WHERE module = 'stok-tambah' AND locked_by = '$user'");

header("Location: index.php");
exit;