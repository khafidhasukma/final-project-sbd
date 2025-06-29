<?php 
session_start();
include '../config/koneksi.php';

$id   = $_GET['id'] ?? '';
$user = $_SESSION['username'] ?? '';

if (!$id || !$user) {
  $_SESSION['error'] = "Gagal membatalkan. Data tidak valid.";
  header("Location: index.php");
  exit;
}

// Lepaskan lock hanya jika yang mengunci adalah user yang login
$conn->query("UPDATE t_jual SET is_locked = 0, locked_by = NULL WHERE kd_trans = '$id' AND locked_by = '$user'");

$_SESSION['success'] = "Perubahan dibatalkan dan kunci dilepas.";
header("Location: index.php");
exit;
