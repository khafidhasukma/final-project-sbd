<?php
session_start();
include '../config/koneksi.php';

$username = $_SESSION['username'] ?? 'anonymous';

$kode = $_POST['kode_brg'] ?? '';
$nama = $_POST['nama_brg'] ?? '';
$satuan = $_POST['satuan'] ?? '';
$stok = $_POST['jml_stok'] ?? 0;

try {
  $conn->query("CALL insert_stok('$kode', '$nama', '$satuan', $stok)");

  // 🔓 Unlock global_lock (karena mode tambah)
  $conn->query("UPDATE global_lock 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE module = 'stok-tambah' AND locked_by = '$username'");

  $_SESSION['success'] = "Data berhasil ditambahkan.";
  header("Location: index.php");
  exit;
} catch (mysqli_sql_exception $e) {
  $_SESSION['error'] = "Gagal menyimpan data: " . $e->getMessage();
  header("Location: create-edit.php");
  exit;
}
