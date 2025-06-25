<?php
session_start();
include '../components/header.php';
include '../config/koneksi.php';

if (!isset($_GET['transaksi'])) {
  $_SESSION['error'] = "Transaksi tidak ditemukan.";
  header("Location: index.php");
  exit;
}

$kode = $_GET['transaksi'];
$result = $conn->query("SELECT t.*, s.nama_brg FROM t_jual t JOIN stok s ON t.kode_brg = s.kode_brg WHERE kd_trans = '$kode'");

if ($result->num_rows === 0) {
  $_SESSION['error'] = "Transaksi dengan kode $kode tidak ditemukan.";
  header("Location: index.php");
  exit;
}

$data = $result->fetch_assoc();
?>

<div class="container mt-5">
  <h1 class="fs-3 fw-bold">Detail Transaksi Penjualan</h1>
  <div class="card mt-4">
    <div class="card-body">
      <dl class="row">
        <dt class="col-sm-4">Kode Transaksi</dt>
        <dd class="col-sm-8"><?= $data['kd_trans'] ?></dd>

        <dt class="col-sm-4">Tanggal Transaksi</dt>
        <dd class="col-sm-8"><?= $data['tgl_trans'] ?></dd>

        <dt class="col-sm-4">Kode Barang</dt>
        <dd class="col-sm-8"><?= $data['kode_brg'] ?> - <?= $data['nama_brg'] ?></dd>

        <dt class="col-sm-4">Jumlah Jual</dt>
        <dd class="col-sm-8"><?= $data['jml_jual'] ?></dd>
      </dl>
    </div>
    <div class="card-footer text-end">
      <a href="index.php" class="btn btn-secondary">Kembali</a>
    </div>
  </div>
</div>

<?php include '../components/footer.php'; ?>