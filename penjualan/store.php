<?php
session_start();
include '../config/koneksi.php';

$transaksi = $_POST['kd_trans'] ?? '';
$tanggal   = $_POST['tgl_trans'] ?? '';
$kode      = $_POST['kode_brg'] ?? '';
$jml_jual  = (int) ($_POST['jml_jual'] ?? 0);

try {
  // Validasi awal
  if ($jml_jual <= 0) {
    $_SESSION['error'] = "Jumlah jual harus lebih dari 0.";
    header("Location: create-edit.php");
    exit;
  }

  // Cek apakah barang ada
  $stok_brg = $conn->query("SELECT jml_stok FROM stok WHERE kode_brg = '$kode'");
  if ($stok_brg->num_rows === 0) {
    $_SESSION['error'] = "Barang dengan kode '$kode' tidak ditemukan.";
    header("Location: create-edit.php");
    exit;
  }

  $data_stok = $stok_brg->fetch_assoc();
  $stok_tersedia = (int) $data_stok['jml_stok'];

  if ($jml_jual > $stok_tersedia) {
    $_SESSION['error'] = "Stok tidak mencukupi. Sisa stok: $stok_tersedia.";
    header("Location: create-edit.php");
    exit;
  }

  // Mulai transaksi
  $conn->begin_transaction();

  // Simpan transaksi
  $insert_sql = "INSERT INTO t_jual (kd_trans, tgl_trans, kode_brg, jml_jual)
                 VALUES ('$transaksi', '$tanggal', '$kode', $jml_jual)";
  $conn->query($insert_sql);

  // Update stok
  $sisa_stok = $stok_tersedia - $jml_jual;
  $update_sql = "UPDATE stok SET jml_stok = $sisa_stok WHERE kode_brg = '$kode'";
  $conn->query($update_sql);

  $conn->commit();
  $_SESSION['success'] = "Data berhasil ditambahkan.";
  header("Location: index.php");
  exit;

} catch (mysqli_sql_exception $e) {
  $conn->rollback();
  if (str_contains($e->getMessage(), 'Duplicate entry')) {
    $_SESSION['error'] = "Kode transaksi '$transaksi' sudah digunakan. Silakan gunakan kode lain.";
  } else {
    $_SESSION['error'] = "Terjadi kesalahan: " . $e->getMessage();
  }
  header("Location: create-edit.php");
  exit;
}