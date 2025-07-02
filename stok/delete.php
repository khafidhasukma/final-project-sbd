<?php
session_start();
include '../config/koneksi.php';

$user = 'anonymous@' . $_SERVER['REMOTE_ADDR'];

if (isset($_GET['kode'])) {
  $kode = $_GET['kode'];

  try {
    // Cek apakah barang masih digunakan
    $cek = $conn->query("SELECT COUNT(*) as total FROM t_jual WHERE kode_brg = '$kode'");
    $digunakan = $cek->fetch_assoc()['total'];

    if ($digunakan > 0) {
      $_SESSION['error'] = "Tidak bisa menghapus. Barang masih digunakan dalam $digunakan transaksi penjualan.";

    } else {
      // Hapus data (gunakan stored procedure)
      $conn->query("CALL delete_stok('$kode')");
      
      $_SESSION['success'] = "Data berhasil dihapus.";
    }

  } catch (mysqli_sql_exception $e) {
    $_SESSION['error'] = "Gagal menghapus: " . $e->getMessage();
  }

  // 🔓 Apapun hasilnya, lepas kunci jika masih dikunci oleh user ini
  $conn->query("UPDATE stok 
                SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                WHERE kode_brg = '$kode' AND locked_by = '$user'");
}

header("Location: index.php");
exit;