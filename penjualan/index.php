<?php
session_start();
include '../components/header.php';
include '../config/koneksi.php';
?>

<div class="container mt-5">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-5">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Daftar Transaksi Penjualan</li>
    </ol>
  </nav>

  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h1 class="fs-3 fw-bold">Daftar Transaksi Penjualan</h1>
      <p>Berikut adalah daftar transaksi penjualan saat ini.</p>
    </div>
    <a href="create-edit.php" class="btn" style="font-weight: bold;background-color: #6b91e4; color: white;">Tambah Transaksi</a>
  </div>

  <!-- Alert Success (session-based) -->
   <!-- Alert Error -->
  <?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
  </div>
  <?php endif; ?>

  <!-- Alert Success -->
  <?php if (isset($_SESSION['success'])): ?>
  <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
  </div>
  <?php endif; ?>


  <div class="table-responsive mt-4">
    <table class="table table-bordered bg-white shadow-sm">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Kode Transaksi</th>
          <th>Tanggal Transaksi</th>
          <th>Kode Barang</th>
          <th>Jumlah Jual</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT * FROM t_jual");
        $no = 1;
        if ($result->num_rows > 0):
          while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= $row['kd_trans'] ?></td>
          <td><?= $row['tgl_trans'] ?></td>
          <td><?= $row['kode_brg'] ?></td>
          <td><?= $row['jml_jual'] ?></td>
          <td class="d-flex gap-2 align-items-center">
            <a href="show.php?transaksi=<?= $row['kd_trans'] ?>" class="btn btn-sm btn-info   text-white">Detail</a>
            <a href="create-edit.php?transaksi=<?= $row['kd_trans'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
              data-bs-target="#deleteModal<?= $row['kd_trans'] ?>">
              Hapus
            </button>
          </td>
        </tr>

        <!-- Modal Hapus -->
        <div class="modal fade" id="deleteModal<?= $row['kd_trans'] ?>" tabindex="-1"
          aria-labelledby="deleteModalLabel<?= $row['kd_trans'] ?>" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteModalLabel<?= $row['kd_trans'] ?>">Hapus Data?</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body text-center">
                <img src="/final-project-sbd/img/ex.jpg" alt="Peringatan" style="width: 90px; margin-bottom: 15px;">
                <p>Apakah Anda yakin ingin menghapus <strong><?= $row['kd_trans'] ?></strong>?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="delete.php?transaksi=<?= $row['kd_trans'] ?>" class="btn btn-danger">Hapus</a>
              </div>
            </div>
          </div>
        </div>
        <?php endwhile; else: ?>
        <tr>
          <td colspan="6" class="text-center text-muted py-4">Belum ada data Transaksi.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../components/footer.php'; ?>