<?php
session_start();
include '../config/koneksi.php';

$user = 'anonymous@' . $_SERVER['REMOTE_ADDR'];
$kode = $_GET['kode'] ?? '';

// Lepas kunci hanya jika user yang sama
if ($kode) {
  $conn->query("UPDATE stok 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE kode_brg = '$kode' AND locked_by = '$user'");
}

header("Location: index.php");
exit;