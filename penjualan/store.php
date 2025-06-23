<?php
session_start();
include '../config/koneksi.php';

$transaksi = $_POST['kd_trans'] ?? '';
$tanggal = $_POST['tgl_trans'] ?? '';
$kode = $_POST['kode_brg'] ?? '';
$stok = $_POST['jml_jual'] ?? 0;

try {
  $sql = "CALL insert_t_jual('$transaksi', '$tanggal', '$kode', $stok)";
  $conn->query($sql);
  $_SESSION['success'] = "Data berhasil ditambahkan.";
  header("Location: index.php");
  exit;
} catch (mysqli_sql_exception $e) {
  // Jika error karena kode duplikat (duplicate entry)
  if (str_contains($e->getMessage(), 'Duplicate entry')) {
    $_SESSION['error'] = "Kode transaksi '$transaksi' sudah digunakan. Silakan gunakan kode lain.";
  } else {
    $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
  }
  header("Location: create-edit.php");
  exit;
}