<?php
session_start();
include './components/header.php';
include './config/koneksi.php';

// Dapatkan jumlah stok dan penjualan
$jumlahStok = $conn->query("SELECT COUNT(*) as total FROM stok")->fetch_assoc()['total'];
$jumlahPenjualan = $conn->query("SELECT COUNT(*) as total FROM t_jual")->fetch_assoc()['total'];
?>

<div class="fullpage-landing">
  <div class="landing-text">
    Selamat Datang Di Sistem Inventori
  </div>

  <div class="container dashboard-container">
    <h1 class="fs-2 fw-bold">Dashboard Inventori</h1>

    <div class="row g-4 mt-4">
      <!-- total stok -->
      <div class="col-md-4">
        <div class="card shadow-lg border-10">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h5 class="card-title text-muted">Total Stok Barang</h5>
              <h2 class="fw-bold"><?= $jumlahStok ?></h2>
            </div>
            <i class="bi bi-box-seam fs-1 text-primary"></i>
          </div>
          <div class="card-footer bg-white text-end border-0">
            <a href="stok/index.php" class="btn btn-outline-primary btn-md">Kelola Stok</a>
          </div>
        </div>
      </div>

      <!-- total penjualan -->
      <div class="col-md-4">
        <div class="card shadow-lg border-10">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h5 class="card-title text-muted">Total Transaksi Penjualan</h5>
              <h2 class="fw-bold"><?= $jumlahPenjualan ?></h2>
            </div>
            <i class="bi bi-cart-check fs-1 text-success"></i>
          </div>
          <div class="card-footer bg-white text-end border-0">
            <a href="penjualan/index.php" class="btn btn-outline-success btn-md">Kelola Penjualan</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<?php include './components/footer.php'; ?>
