<?php
session_start();
if (!isset($_SESSION['db_user'])) {
  header("Location:  /final-project-sbd/login/index.php");
  exit;
}

include '../config/koneksi.php';

$client_name = $_SESSION['client_name']; // nama client dari login

$kode   = $_POST['kode_brg'] ?? '';
$nama   = $_POST['nama_brg'] ?? '';
$satuan = $_POST['satuan'] ?? '';
$stok   = $_POST['jml_stok'] ?? 0;

try {
  // Cek apakah kode sudah ada (kode harus unik)
  $cek = $conn->query("SELECT kode_brg FROM stok WHERE kode_brg = '$kode'");
  if ($cek->num_rows > 0) {
    $_SESSION['error'] = "Kode barang '$kode' sudah digunakan. Gunakan kode lain.";
    header("Location: create-edit.php?mode=tambah");
    exit;
  }

  // Jalankan prosedur tambah
  $conn->query("CALL insert_stok('$kode', '$nama', '$satuan', $stok)");

  // Lepas kunci global_lock setelah tambah
  $conn->query("UPDATE global_lock 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE module = 'stok-tambah' AND locked_by = '$client_name'");

  $_SESSION['success'] = "Data berhasil ditambahkan.";
  header("Location: index.php");
  exit;

} catch (mysqli_sql_exception $e) {
  $_SESSION['error'] = "Gagal menyimpan data: " . $e->getMessage();
  header("Location: create-edit.php?mode=tambah");
  exit;
}
