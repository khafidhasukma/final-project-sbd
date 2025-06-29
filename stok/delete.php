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

      // 🔓 UNLOCK walau gagal hapus
      $conn->query("UPDATE stok SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                    WHERE kode_brg = '$kode' AND locked_by = '{$_SESSION['username']}'");

    } else {
      // Hapus data
      $conn->query("CALL delete_stok('$kode')");
      
      // 🔓 UNLOCK setelah berhasil hapus
      $conn->query("UPDATE stok SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                    WHERE kode_brg = '$kode'");

      $_SESSION['success'] = "Data berhasil dihapus.";
    }

  } catch (mysqli_sql_exception $e) {
    $_SESSION['error'] = "Gagal menghapus: " . $e->getMessage();

    // 🔓 Tetap unlock kalau error
    $conn->query("UPDATE stok SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                  WHERE kode_brg = '$kode' AND locked_by = '{$_SESSION['username']}'");
  }
}

header("Location: index.php");
exit;
