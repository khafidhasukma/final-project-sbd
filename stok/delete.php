<?php
session_start();
include '../config/koneksi.php';

if (isset($_GET['kode'])) {
  $kode = $_GET['kode'];

  try {
    // Cek apakah barang digunakan di penjualan
    $cek = $conn->query("SELECT COUNT(*) as total FROM t_jual WHERE kode_brg = '$kode'");
    $digunakan = $cek->fetch_assoc()['total'];

    if ($digunakan > 0) {
      $_SESSION['error'] = "Tidak bisa menghapus. Barang masih digunakan dalam $digunakan transaksi penjualan.";
    } else {
      // Hapus jika tidak digunakan
      $conn->query("CALL delete_stok('$kode')");
      $_SESSION['success'] = "Data berhasil dihapus.";
    }

  } catch (mysqli_sql_exception $e) {
    $_SESSION['error'] = "Gagal menghapus: " . $e->getMessage();
  }
}

header("Location: index.php");
exit;