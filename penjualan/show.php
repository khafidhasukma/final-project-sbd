<?php
session_start();
include '../components/header.php';
include '../config/koneksi.php';

if (!isset($_GET['transaksi'])) {
  $_SESSION['error'] = "Transaksi tidak ditemukan.";
  header("Location: index.php");
  exit;
}

$kode = $conn->real_escape_string($_GET['transaksi']);
$result = $conn->query("SELECT t.*, s.nama_brg 
                        FROM t_jual t 
                        JOIN stok s ON t.kode_brg = s.kode_brg 
                        WHERE kd_trans = '$kode'");

if ($result->num_rows === 0) {
  $_SESSION['error'] = "Transaksi dengan kode $kode tidak ditemukan.";
  header("Location: index.php");
  exit;
}

$data = $result->fetch_assoc();
?>

<div class="container mt-5">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-5">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="/final-project-sbd/index.php">Home</a></li>
      <li class="breadcrumb-item"><a href="/final-project-sbd/penjualan/index.php">Daftar Penjualan</a></li>
      <li class="breadcrumb-item active" aria-current="page">Detail Transaksi Penjualan</li>
    </ol>
  </nav>

  <div class="row justify-content-center mb-5">
    <div class="col-lg-8 col-md-10">
      <div class="text-center mb-4">
        <h1 class="fs-2 fw-bold mb-2" style="color: #364C84;">Detail Transaksi Penjualan</h1>
        <p class="text-muted">Informasi lengkap transaksi penjualan</p>
      </div>

      <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom-0 py-4">
          <div class="d-flex align-items-center">
            <div class="bg-light rounded-circle p-3 me-3">
              <i class="fas fa-receipt" style="color: #95B1EE; font-size: 1.5rem;"></i>
            </div>
            <div>
              <h5 class="card-title mb-1 fw-bold">Transaksi <?= $data['kd_trans'] ?></h5>
              <p class="text-muted mb-0">Detail informasi penjualan barang</p>
            </div>
          </div>
        </div>

        <div class="card-body py-4">
          <div class="row g-4">
            <!-- Kode Transaksi -->
            <div class="col-md-6">
              <div class="d-flex align-items-center p-3 bg-light rounded">
                <div class="me-3">
                  <i class="fas fa-hashtag" style="color: #95B1EE;"></i>
                </div>
                <div>
                  <small class="text-muted d-block">Kode Transaksi</small>
                  <strong class="fs-6"><?= $data['kd_trans'] ?></strong>
                </div>
              </div>
            </div>

            <!-- Tanggal Transaksi -->
            <div class="col-md-6">
              <div class="d-flex align-items-center p-3 bg-light rounded">
                <div class="me-3">
                  <i class="fas fa-calendar-alt" style="color: #95B1EE;"></i>
                </div>
                <div>
                  <small class="text-muted d-block">Tanggal Transaksi</small>
                  <strong class="fs-6"><?= date('d F Y', strtotime($data['tgl_trans'])) ?></strong>
                </div>
              </div>
            </div>

            <!-- Kode Barang -->
            <div class="col-md-6">
              <div class="d-flex align-items-center p-3 bg-light rounded">
                <div class="me-3">
                  <i class="fas fa-box" style="color: #E7F1A8;"></i>
                </div>
                <div>
                  <small class="text-muted d-block">Kode Barang</small>
                  <strong class="fs-6"><?= $data['kode_brg'] ?></strong>
                </div>
              </div>
            </div>

            <!-- Nama Barang -->
            <div class="col-md-6">
              <div class="d-flex align-items-center p-3 bg-light rounded">
                <div class="me-3">
                  <i class="fas fa-tag" style="color: #E7F1A8;"></i>
                </div>
                <div>
                  <small class="text-muted d-block">Nama Barang</small>
                  <strong class="fs-6"><?= $data['nama_brg'] ?></strong>
                </div>
              </div>
            </div>

            <!-- Jumlah Jual -->
            <div class="col-12">
              <div class="d-flex align-items-center justify-content-center p-4 rounded" style="background: linear-gradient(45deg, #95B1EE20, #E7F1A820);">
                <div class="text-center">
                  <div class="mb-2">
                    <i class="fas fa-shopping-cart" style="color: #364C84; font-size: 2rem;"></i>
                  </div>
                  <small class="text-muted d-block">Jumlah Terjual</small>
                  <h3 class="fw-bold mb-0" style="color: #364C84;">
                    <?= $data['jml_jual'] ?> <small class="fs-6 text-muted">unit</small>
                  </h3>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer bg-white border-top-0 py-4">
          <div class="d-flex justify-content-between align-items-center">
            <div class="text-muted">
              <small><i class="fas fa-info-circle me-1"></i> Data transaksi penjualan</small>
            </div>
            <a href="index.php" class="btn" style="font-weight: bold; background-color: #6b91e4; color: white;">Kembali</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../components/footer.php'; ?>
