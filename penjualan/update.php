<?php 
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['db_user'])) {
  header("Location: /final-project-sbd/login/index.php");
  exit;
}

$transaksi = $_POST['kd_trans'] ?? '';
$tanggal   = $_POST['tgl_trans'] ?? '';
$kode      = $_POST['kode_brg'] ?? '';
$jml_jual  = (int) ($_POST['jml_jual'] ?? 0);
$client_name = $_SESSION['client_name'];

try {
  // 🚫 Validasi input jumlah jual
  if ($jml_jual <= 0) {
    throw new Exception("Jumlah jual harus lebih dari 0.");
  }

  // 🚫 Validasi stok barang
  $cekStok = $conn->query("SELECT jml_stok FROM stok WHERE kode_brg = '$kode'");
  if ($cekStok->num_rows === 0) {
    throw new Exception("Barang tidak ditemukan.");
  }

  $stokTersedia = (int) $cekStok->fetch_assoc()['jml_stok'];
  if ($jml_jual > $stokTersedia) {
    throw new Exception("Stok tidak cukup. Sisa stok: $stokTersedia");
  }

  // 🔐 Validasi kunci transaksi
  $cek = $conn->query("SELECT is_locked, locked_by FROM t_jual WHERE kd_trans = '$transaksi'");
  $data = $cek->fetch_assoc();

  if (!$data) {
    $_SESSION['error'] = "Transaksi tidak ditemukan.";
    header("Location: index.php");
    exit;
  }

  if ($data['is_locked'] == 1 && $data['locked_by'] !== $client_name) {
    $_SESSION['error'] = "Data sedang dikunci oleh user lain: <b>{$data['locked_by']}</b>.";
    header("Location: index.php");
    exit;
  }

  // ✅ Jalankan prosedur update
  $conn->query("CALL update_penjualan('$transaksi', '$tanggal', '$kode', $jml_jual)");

  // 🔓 Unlock setelah simpan
  $conn->query("UPDATE t_jual 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE kd_trans = '$transaksi'");

  $_SESSION['success'] = "Data berhasil diperbarui.";
  header("Location: index.php");
  exit;

} catch (Exception $e) {
  // 🔓 Unlock jika gagal
  $conn->query("UPDATE t_jual 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE kd_trans = '$transaksi' AND locked_by = '$client_name'");

  $_SESSION['error'] = "Gagal memperbarui: " . $e->getMessage();
  header("Location: create-edit.php?transaksi=$transaksi");
  exit;
}
