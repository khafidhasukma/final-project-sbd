<?php
session_start();
include '../config/koneksi.php';

// Gunakan IP sebagai identitas user
$username = 'anonymous@' . $_SERVER['REMOTE_ADDR'];

// Ambil data global_lock untuk stok-tambah
$cek = $conn->query("SELECT * FROM global_lock WHERE module = 'stok-tambah'");
$lock = $cek->fetch_assoc();

// Jika data belum ada (row kosong), insert dulu default
if (!$lock) {
  $conn->query("INSERT INTO global_lock (module, is_locked, locked_by, locked_at) VALUES ('stok-tambah', 0, NULL, NULL)");
  $lock = ['is_locked' => 0, 'locked_by' => null];
}

// Jika sedang dikunci oleh user lain
if ($lock['is_locked'] == 1 && $lock['locked_by'] !== $username) {
  $_SESSION['error'] = "Form tambah data sedang digunakan oleh user lain.";
  header("Location: index.php");
  exit;
}

// Kunci modul untuk user ini
$conn->query("UPDATE global_lock 
              SET is_locked = 1, locked_by = '$username', locked_at = NOW() 
              WHERE module = 'stok-tambah'");

// Redirect ke form tambah
header("Location: create-edit.php?mode=tambah");
exit;