<?php
session_start();
if (!isset($_SESSION['db_user'])) {
  header("Location: /final-project-sbd/login/index.php");
  exit;
}

include '../config/koneksi.php';

$client_name = $_SESSION['client_name'];
$mode = $_GET['mode'] ?? '';

// Ambil input
$kode = $_POST['kd_trans'] ?? '';
$tanggal = $_POST['tgl_trans'] ?? date('Y-m-d');
$kode_brg = $_POST['kode_brg'] ?? '';
$jml = (int) ($_POST['jml_jual'] ?? 0);

try {
  if ($mode === 'tambah') {
    // Cek apakah kode transaksi sudah ada
    $cek = $conn->query("SELECT kd_trans FROM t_jual WHERE kd_trans = '$kode'");
    if ($cek->num_rows > 0) {
      $_SESSION['error'] = "Kode transaksi '$kode' sudah digunakan. Gunakan kode lain.";
      header("Location: create-edit.php?mode=tambah");
      exit;
    }

    // Jalankan prosedur insert
    $conn->query("CALL insert_penjualan('$kode', '$tanggal', '$kode_brg', $jml)");

    // Lepaskan kunci global tambah
    $conn->query("UPDATE global_lock 
                  SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                  WHERE module = 'penjualan-tambah' AND locked_by = '$client_name'");

    $_SESSION['success'] = "Data transaksi berhasil ditambahkan.";
    header("Location: index.php");
    exit;

  } elseif ($mode === 'edit') {
    // Pastikan data masih dikunci oleh user ini
    $cek = $conn->query("SELECT is_locked, locked_by FROM t_jual WHERE kd_trans = '$kode'");
    $lock = $cek->fetch_assoc();
    if (!$lock || $lock['locked_by'] !== $client_name) {
      $_SESSION['error'] = "Transaksi tidak terkunci oleh Anda. Edit dibatalkan.";
      header("Location: index.php");
      exit;
    }

    // Jalankan prosedur update
    $conn->query("CALL update_penjualan('$kode', '$tanggal', '$kode_brg', $jml)");

    // Lepaskan kunci per-record
    $conn->query("UPDATE t_jual 
                  SET is_locked = 0, locked_by = NULL, locked_at = NULL 
                  WHERE kd_trans = '$kode' AND locked_by = '$client_name'");

    $_SESSION['success'] = "Data transaksi berhasil diperbarui.";
    header("Location: index.php");
    exit;

  } else {
    $_SESSION['error'] = "Mode tidak valid.";
    header("Location: index.php");
    exit;
  }

} catch (mysqli_sql_exception $e) {
  $_SESSION['error'] = "Gagal menyimpan data: " . $e->getMessage();
  $redirectMode = ($mode === 'edit') ? "?transaksi=$kode" : "?mode=tambah";
  header("Location: create-edit.php$redirectMode");
  exit;
}
