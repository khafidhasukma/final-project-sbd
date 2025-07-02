<?php
session_start();
include '../config/koneksi.php';

$user = 'anonymous@' . $_SERVER['REMOTE_ADDR'];

$kode   = $_POST['kode_brg'] ?? '';
$nama   = $_POST['nama_brg'] ?? '';
$satuan = $_POST['satuan'] ?? '';
$stok   = $_POST['jml_stok'] ?? 0;

try {
  // cek apakah dikunci oleh user lain
  $cek = $conn->query("SELECT is_locked, locked_by FROM stok WHERE kode_brg = '$kode'");
  $data = $cek->fetch_assoc();

  if (!$data) {
    $_SESSION['error'] = "Data tidak ditemukan.";
    header("Location: index.php");
    exit;
  }

  if ($data['is_locked'] == 1 && $data['locked_by'] !== $user) {
    $_SESSION['error'] = "Data sedang dikunci oleh user lain: {$data['locked_by']}.";
    header("Location: index.php");
    exit;
  }

  // Jalankan stored procedure update
  $conn->query("CALL update_stok('$kode', '$nama', '$satuan', $stok)");

  // Lepas kunci setelah update
  $conn->query("UPDATE stok 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE kode_brg = '$kode'");

  $_SESSION['success'] = "Data berhasil diperbarui.";
  header("Location: index.php");
  exit;

} catch (mysqli_sql_exception $e) {
  $_SESSION['error'] = "Gagal mengupdate data: " . $e->getMessage();
  header("Location: create-edit.php?kode=$kode");
  exit;
}