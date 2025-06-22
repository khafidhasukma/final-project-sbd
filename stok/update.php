<?php
session_start();
include '../config/koneksi.php';

$kode = $_POST['kode_brg'] ?? '';
$nama = $_POST['nama_brg'] ?? '';
$satuan = $_POST['satuan'] ?? '';
$stok = $_POST['jml_stok'] ?? 0;

try {
  $sql = "CALL update_stok('$kode', '$nama', '$satuan', $stok)";
  $conn->query($sql);
  $_SESSION['success'] = "Data berhasil diperbarui.";
  header("Location: index.php");
  exit;
} catch (mysqli_sql_exception $e) {
  $_SESSION['error'] = "Gagal mengupdate data: " . $e->getMessage();
  header("Location: create-edit.php?kode=$kode");
  exit;
}