<?php
session_start();
include '../config/koneksi.php';

$user = $_SESSION['client_name'];

if (!isset($_GET['kode'])) {
  header("Location: index.php");
  exit;
}

$kode = $_GET['kode'];

// Cek kunci
$result = $conn->query("SELECT is_locked, locked_by FROM stok WHERE kode_brg = '$kode'");
if ($result->num_rows === 0) {
  $_SESSION['error'] = "Data tidak ditemukan.";
  header("Location: index.php");
  exit;
}

$row = $result->fetch_assoc();
if ($row['is_locked'] == 1 && $row['locked_by'] !== $user) {
  $_SESSION['error'] = "Data sedang digunakan oleh user lain: $row[locked_by].";
  header("Location: index.php");
  exit;
}

// Kunci record
$conn->query("UPDATE stok 
              SET is_locked = 1, locked_by = '$user', locked_at = NOW() 
              WHERE kode_brg = '$kode'");

// Arahkan ke index untuk munculkan modal otomatis
header("Location: index.php?delete=$kode");
exit;