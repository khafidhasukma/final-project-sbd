<?php 
session_start();
include '../config/koneksi.php';

$transaksi = $_POST['kd_trans'] ?? '';
$tanggal   = $_POST['tgl_trans'] ?? '';
$kode      = $_POST['kode_brg'] ?? '';
$stok      = $_POST['jml_jual'] ?? 0;
$user      = $_SESSION['username'] ?? '';

try {
  // Cek status record
  $check = $conn->query("SELECT is_locked, locked_by FROM t_jual WHERE kd_trans = '$transaksi'");
  if ($check->num_rows === 0) {
    $_SESSION['error'] = "Data transaksi tidak ditemukan.";
    header("Location: index.php");
    exit;
  }

  $lock = $check->fetch_assoc();
  $is_locked = (int) $lock['is_locked'];
  $locked_by = $lock['locked_by'];

  // CASE 1: Rekaman dikunci oleh user lain
  if ($is_locked === 1 && $locked_by !== $user) {
    $_SESSION['error'] = "Anda tidak memiliki akses untuk mengubah data ini. Saat ini dikunci oleh user <strong>{$locked_by}</strong>.";
    header("Location: create-edit.php?transaksi=$transaksi");
    exit;
  }

  // CASE 2: Rekaman belum dikunci, maka kunci dulu
  if ($is_locked === 0) {
    $conn->query("UPDATE t_jual SET is_locked = 1, locked_by = '$user' WHERE kd_trans = '$transaksi'");
  }

  // ✅ Jalankan simpan (stored procedure)
  $conn->query("CALL update_penjualan('$transaksi', '$tanggal', '$kode', $stok)");

  // 💤 Tunggu 3 detik sebelum melepas lock
  sleep(3);

  // 🔓 Lepaskan kunci
  $conn->query("UPDATE t_jual SET is_locked = 0, locked_by = NULL WHERE kd_trans = '$transaksi'");

  $_SESSION['success'] = "Data berhasil diperbarui.";
  header("Location: index.php");
  exit;

} catch (mysqli_sql_exception $e) {
  $_SESSION['error'] = "Gagal mengupdate data: " . $e->getMessage();
  header("Location: create-edit.php?transaksi=$transaksi");
  exit;
}
