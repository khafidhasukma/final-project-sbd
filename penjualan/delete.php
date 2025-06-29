<?php
session_start();
include '../config/koneksi.php';

$id = $_GET['id'] ?? '';
$user = $_SESSION['username'] ?? '';

// Hapus dummy atau lepas lock
if ($id === '__NEW__') {
  $conn->query("DELETE FROM t_jual WHERE kd_trans = '__NEW__' AND locked_by = '$user'");
} else {
  $conn->query("UPDATE t_jual SET is_locked = 0, locked_by = NULL WHERE kd_trans = '$id' AND locked_by = '$user'");
}

header("Location: index.php");
exit;
