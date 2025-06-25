<?php
session_start();
include '../components/header.php';
include '../config/koneksi.php';

if (!isset($_GET['kode'])) {
  $_SESSION['error'] = "Barang tidak ditemukan.";
  header("Location: index.php");
  exit;
}

$kode = $_GET['kode'];
$result = $conn->query("SELECT * FROM stok WHERE kode_brg = '$kode'");

if ($result->num_rows === 0) {
  $_SESSION['error'] = "Barang dengan kode $kode tidak ditemukan.";
  header("Location: index.php");
  exit;
}

$data = $result->fetch_assoc();
?>

<div class="container mt-5">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-5">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/stok/index.php">Daftar Stok Barang</a></li>
      <li class="breadcrumb-item active" aria-current="page">Detail Stok Barang</li>
    </ol>
  </nav>

  <h1 class="fs-3 fw-bold">Detail Stok Barang</h1>
  <div class="card mt-4">
    <div class="card-body">
      <dl class="row">
        <dt class="col-sm-4">Kode Barang</dt>
        <dd class="col-sm-8"><?= $data['kode_brg'] ?></dd>

        <dt class="col-sm-4">Nama Barang</dt>
        <dd class="col-sm-8"><?= $data['nama_brg'] ?></dd>

        <dt class="col-sm-4">Satuan</dt>
        <dd class="col-sm-8"><?= $data['satuan'] ?></dd>

        <dt class="col-sm-4">Jumlah Stok</dt>
        <dd class="col-sm-8"><?= $data['jml_stok'] ?></dd>
      </dl>
    </div>
    <div class="card-footer text-end">
      <a href="index.php" class="btn btn-secondary">Kembali</a>
    </div>
  </div>
</div>

<?php include '../components/footer.php'; ?>