<?php
session_start();
include '../config/koneksi.php';

$user      = $_SESSION['username'] ?? '';
$transaksi = $_POST['kd_trans'] ?? '';
$tanggal   = $_POST['tgl_trans'] ?? '';
$kode      = $_POST['kode_brg'] ?? '';
$jml_jual  = (int) ($_POST['jml_jual'] ?? 0);

try {
  // Validasi
  if ($jml_jual <= 0) throw new Exception("Jumlah jual harus lebih dari 0.");

  // Cek stok
  $cekStok = $conn->query("SELECT jml_stok FROM stok WHERE kode_brg = '$kode'");
  if ($cekStok->num_rows === 0) throw new Exception("Barang tidak ditemukan.");
  $stokTersedia = (int) $cekStok->fetch_assoc()['jml_stok'];

  if ($jml_jual > $stokTersedia)
    throw new Exception("Stok tidak cukup. Sisa stok: $stokTersedia");

  // 🔐 Simpan transaksi
  $conn->begin_transaction();
  $conn->query("INSERT INTO t_jual (kd_trans, tgl_trans, kode_brg, jml_jual) VALUES ('$transaksi', '$tanggal', '$kode', $jml_jual)");
  $conn->query("UPDATE stok SET jml_stok = jml_stok - $jml_jual WHERE kode_brg = '$kode'");
  $conn->commit();

  // 🧹 Bersihin dummy record dan unlock
  $conn->query("DELETE FROM t_jual WHERE kd_trans = '__NEW__' AND locked_by = '$user'");
  $conn->query("UPDATE global_lock 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE module = 'penjualan-tambah' AND locked_by = '$user'");

  $_SESSION['success'] = "Transaksi berhasil disimpan.";
  $conn->query("DELETE FROM t_jual WHERE kd_trans = '__NEW__' AND locked_by = '{$_SESSION['username']}'");
  header("Location: index.php");
  exit;

} catch (Exception $e) {
  $conn->rollback();
  $_SESSION['error'] = "Gagal menyimpan: " . $e->getMessage();
  header("Location: create-edit.php");
  exit;
}
