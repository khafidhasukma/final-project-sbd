<?php
session_start();
include '../config/koneksi.php';

$kode = $_POST['kode_brg'] ?? '';
$nama = $_POST['nama_brg'] ?? '';
$satuan = $_POST['satuan'] ?? '';
$stok = $_POST['jml_stok'] ?? 0;

try {
  // Proses update data
  $sql = "CALL update_stok('$kode', '$nama', '$satuan', $stok)";
  $conn->query($sql);

  // 🔓 Unlock setelah update
  $conn->query("UPDATE stok SET is_locked = 0, locked_by = NULL, locked_at = NULL WHERE kode_brg = '$kode'");

  $_SESSION['success'] = "Data berhasil diperbarui.";
  header("Location: index.php");
  exit;
} catch (mysqli_sql_exception $e) {
  $_SESSION['error'] = "Gagal mengupdate data: " . $e->getMessage();
  header("Location: create-edit.php?kode=$kode");
  exit;
}
