<?php
session_start();
include '../config/koneksi.php';

$transaksi = $_POST['kd_trans'] ?? '';
$tanggal = $_POST['tgl_trans'] ?? '';
$kode = $_POST['kodejual'] ?? '';
$stok = $_POST['jml_jual'] ?? 0;

try {
  $sql = "CALL update_t_jual('$transaksi', '$tanggal', '$kode', $stok)";
  $conn->query($sql);
  $_SESSION['success'] = "Data berhasil diperbarui.";
  header("Location: index.php");
  exit;
} catch (mysqli_sql_exception $e) {
  $_SESSION['error'] = "Gagal mengupdate data: " . $e->getMessage();
  header("Location: create-edit.php?kode=$transaksi");
  exit;
}