<?php 
session_start();
include '../config/koneksi.php';

$transaksi = $_POST['kd_trans'] ?? '';
$tanggal   = $_POST['tgl_trans'] ?? '';
$kode      = $_POST['kode_brg'] ?? '';
$jml_jual  = (int) ($_POST['jml_jual'] ?? 0);
$user      = $_SESSION['username'] ?? 'anonymous@' . $_SERVER['REMOTE_ADDR'];

try {
  // 🚫 Validasi input jual
  if ($jml_jual <= 0) {
    throw new Exception("Jumlah jual harus lebih dari 0.");
  }

  // 🚫 Cek stok barang
  $cekStok = $conn->query("SELECT jml_stok FROM stok WHERE kode_brg = '$kode'");
  if ($cekStok->num_rows === 0) {
    throw new Exception("Barang tidak ditemukan.");
  }
  $stokTersedia = (int) $cekStok->fetch_assoc()['jml_stok'];

  if ($jml_jual > $stokTersedia) {
    throw new Exception("Stok tidak cukup. Sisa stok: $stokTersedia");
  }

  // 🔐 Validasi kunci record
  $check = $conn->query("SELECT is_locked, locked_by FROM t_jual WHERE kd_trans = '$transaksi'");
  if ($check->num_rows === 0) {
    throw new Exception("Data transaksi tidak ditemukan.");
  }

  $lock = $check->fetch_assoc();
  if ($lock['is_locked'] == 1 && $lock['locked_by'] !== $user) {
    throw new Exception("Data sedang dikunci oleh user lain.");
  }

  // 🔄 Kunci record jika belum
  if ($lock['is_locked'] == 0) {
    $conn->query("UPDATE t_jual 
                  SET is_locked = 1, locked_by = '$user', locked_at = NOW() 
                  WHERE kd_trans = '$transaksi'");
  }

  // ✅ Update via stored procedure
  $conn->query("CALL update_penjualan('$transaksi', '$tanggal', '$kode', $jml_jual)");

  // 🔓 Lepas kunci setelah simpan
  $conn->query("UPDATE t_jual 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE kd_trans = '$transaksi'");

  $_SESSION['success'] = "Data berhasil diperbarui.";
  header("Location: index.php");
  exit;

} catch (Exception $e) {
  // Pastikan unlock jika terjadi error
  $conn->query("UPDATE t_jual 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE kd_trans = '$transaksi' AND locked_by = '$user'");

  $_SESSION['error'] = "Gagal memperbarui: " . $e->getMessage();
  header("Location: create-edit.php?transaksi=$transaksi");
  exit;
}