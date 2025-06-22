<?php
session_start();
include '../config/koneksi.php';

if (isset($_GET['kode'])) {
  $kode = $_GET['kode'];

  $sql = "CALL delete_stok('$kode')";
  $conn->query($sql);

  $_SESSION['success'] = "Data berhasil dihapus.";
}

header("Location: index.php");
exit;