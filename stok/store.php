<?php
session_start();
include '../config/koneksi.php';

$kode = $_POST['kode_brg'] ?? '';
$nama = $_POST['nama_brg'] ?? '';
$satuan = $_POST['satuan'] ?? '';
$stok = $_POST['jml_stok'] ?? 0;

try {
  $sql = "CALL insert_stok('$kode', '$nama', '$satuan', $stok)";
  $conn->query($sql);
  $_SESSION['success'] = "Data berhasil ditambahkan.";
  header("Location: index.php");
  exit;
} catch (mysqli_sql_exception $e) {
  // Jika error karena kode duplikat (duplicate entry)
  if (str_contains($e->getMessage(), 'Duplicate entry')) {
    $_SESSION['error'] = "Kode barang '$kode' sudah digunakan. Silakan gunakan kode lain.";
  } else {
    $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
  }
  header("Location: create-edit.php");
  exit;
}