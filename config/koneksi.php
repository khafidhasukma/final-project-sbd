<?php

// Redirect kalau belum login
if (!isset($_SESSION['db_user']) || !isset($_SESSION['db_pass'])) {
  header("Location: /login/index.php");
  exit;
}

$host = 'localhost';
$user = $_SESSION['db_user'];
$pass = $_SESSION['db_pass'];
$db   = 'inventori_uas';

// Koneksi ke database pakai user login
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Koneksi gagal: " . $conn->connect_error);
}
?>
