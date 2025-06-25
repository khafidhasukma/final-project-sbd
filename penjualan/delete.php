<?php
session_start();
include '../config/koneksi.php';

if (isset($_GET['transaksi'])) {
  $transaksi = $_GET['transaksi'];

  $sql = "CALL delete_penjualan('$transaksi')";
  $conn->query($sql);

  $_SESSION['success'] = "Data Transaksi Penjualan berhasil dihapus.";
}

header("Location: index.php");
exit;