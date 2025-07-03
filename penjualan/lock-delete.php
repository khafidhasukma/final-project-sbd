<?php
session_start();
include '../config/koneksi.php';

$user = $_SESSION['username'] ?? $_SESSION['client_name'];
$id = $_GET['transaksi'] ?? '';

$q = $conn->query("SELECT * FROM t_jual WHERE kd_trans = '$id'");
if ($q->num_rows === 0) {
  $_SESSION['error'] = "Data tidak ditemukan.";
  header("Location: index.php");
  exit;
}

$data = $q->fetch_assoc();
if ($data['is_locked'] == 1 && $data['locked_by'] !== $user) {
  $_SESSION['error'] = "Data sedang dikunci oleh user lain.";
  header("Location: index.php");
  exit;
}

// Kunci record
$conn->query("UPDATE t_jual 
              SET is_locked = 1, locked_by = '$user', locked_at = NOW() 
              WHERE kd_trans = '$id'");

header("Location: index.php?delete=$id");
exit;