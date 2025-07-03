<?php
session_start();
if (!isset($_SESSION['db_user'])) {
  header("Location:  /final-project-sbd/login/index.php");
  exit;
}

include '../config/koneksi.php';

$client_name = $_SESSION['client_name'];

$kode   = $_POST['kode_brg'] ?? '';
$nama   = $_POST['nama_brg'] ?? '';
$satuan = $_POST['satuan'] ?? '';
$stok   = $_POST['jml_stok'] ?? 0;

try {
  // Cek apakah data masih dikunci dan siapa penguncinya
  $cek = $conn->query("SELECT is_locked, locked_by FROM stok WHERE kode_brg = '$kode'");
  $data = $cek->fetch_assoc();

  if (!$data) {
    $_SESSION['error'] = "Data tidak ditemukan.";
    header("Location: index.php");
    exit;
  }

  // Jika data dikunci oleh user lain
  if ($data['is_locked'] == 1 && $data['locked_by'] !== $client_name) {
    $_SESSION['error'] = "Data sedang dikunci oleh user lain: <b>{$data['locked_by']}</b>.";
    header("Location: index.php");
    exit;
  }

  // Jalankan stored procedure untuk update data
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
