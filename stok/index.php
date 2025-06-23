<?php
session_start();
include '../components/header.php';
include '../config/koneksi.php';
?>

<div class="container mt-5">
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h1 class="fs-3 fw-bold">Daftar Stok Barang</h1>
      <p>Berikut adalah daftar stok barang saat ini.</p>
    </div>
    <a href="create-edit.php" class="btn btn-primary">Tambah Data</a>
  </div>

  <!-- Alert Success (session-based) -->
  <?php if (isset($_SESSION['success'])): ?>
  <div id="alert-success" class="alert alert-success alert-dismissible fade show mt-4" role="alert">
    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>


  <div class="table-responsive mt-4">
    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th>Satuan</th>
          <th>Jumlah Stok</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT * FROM stok");
        $no = 1;
        if ($result->num_rows > 0):
          while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= $row['kode_brg'] ?></td>
          <td><?= $row['nama_brg'] ?></td>
          <td><?= $row['satuan'] ?></td>
          <td><?= $row['jml_stok'] ?></td>
          <td>
            <a href="create-edit.php?kode=<?= $row['kode_brg'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
              data-bs-target="#deleteModal<?= $row['kode_brg'] ?>">
              Hapus
            </button>
          </td>
        </tr>

        <!-- Modal Hapus -->
        <div class="modal fade" id="deleteModal<?= $row['kode_brg'] ?>" tabindex="-1"
          aria-labelledby="deleteModalLabel<?= $row['kode_brg'] ?>" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteModalLabel<?= $row['kode_brg'] ?>">Hapus Data?</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus <strong><?= $row['nama_brg'] ?></strong>?</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="delete.php?kode=<?= $row['kode_brg'] ?>" class="btn btn-danger">Hapus</a>
              </div>
            </div>
          </div>
        </div>
        <?php endwhile; else: ?>
        <tr>
          <td colspan="6" class="text-center text-muted py-4">Belum ada data stok barang.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include '../components/footer.php'; ?>