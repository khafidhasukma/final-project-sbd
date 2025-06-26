<?php
session_start();
include '../config/koneksi.php';

$id = $_GET['id'];
$user = $_SESSION['username'] ?? '';

// Hanya lepas lock kalau yang unlock adalah user yang ngunci
$conn->query("UPDATE t_jual SET is_locked = 0, locked_by = NULL WHERE kd_trans = '$id' AND locked_by = '$user'");

header("Location: index.php");
exit;
