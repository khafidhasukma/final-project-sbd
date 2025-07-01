<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'] . '/final-project-sbd';

include $root . '/components/header.php';
include $root . '/config/koneksi.php';

// Auto-unlock record yang udah dikunci > 5 menit
$now = date('Y-m-d H:i:s');
$conn->query("UPDATE stok SET is_locked = 0, locked_by = NULL, locked_at = NULL 
              WHERE is_locked = 1 AND TIMESTAMPDIFF(MINUTE, locked_at, '$now') >= 5");

// ⏰ Auto-unlock TAMBAH DATA stok jika idle > 5 menit
$conn->query("UPDATE global_lock 
              SET is_locked = 0, locked_by = NULL, locked_at = NULL 
              WHERE module = 'stok-tambah' 
              AND is_locked = 1 
              AND TIMESTAMPDIFF(MINUTE, locked_at, NOW()) >= 5");
?>

<div class="container mt-5">
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-5">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="/final-project-sbd/index.php">Home</a>
      <li class="breadcrumb-item active" aria-current="page">Daftar Stok Barang</li>
    </ol>
  </nav>
  
  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h1 class="fs-3 fw-bold">Daftar Stok Barang</h1>
      <p>Berikut adalah daftar stok barang saat ini.</p>
    </div>
    <a href="lock-create.php" class="btn" style="font-weight: bold;background-color: #6b91e4; color: white;">Tambah Data</a>
  </div>

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
          <td class="d-flex gap-2 align-items-center">
            <a href="show.php?kode=<?= $row['kode_brg'] ?>" class="btn btn-sm btn-info text-white">Detail</a>
            <a href="create-edit.php?kode=<?= $row['kode_brg'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="delete.php?kode=<?= $row['kode_brg'] ?>" class="btn btn-sm btn-danger">Hapus</a>

          </td>
        </tr>
        <?php endwhile; else: ?>
        <tr>
          <td colspan="6" class="text-center text-muted py-4">Belum ada data stok barang.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
// Modal delete otomatis kalau ada ?delete
if (isset($_GET['delete'])):
  $kode = $_GET['delete'];
  $result = $conn->query("SELECT * FROM stok WHERE kode_brg = '$kode'");
  $data = $result->fetch_assoc();

  // ❌ Kalau data sudah dihapus oleh user lain
  if (!$data) {
    $_SESSION['error'] = "Data dengan kode $kode sudah dihapus oleh user lain.";
    header("Location: index.php");
    exit;
  }
?>
<!-- Modal Delete Otomatis -->
<script>
  window.addEventListener('DOMContentLoaded', () => {
    const modal = new bootstrap.Modal(document.getElementById('deleteModalAuto'));
    modal.show();
  });
</script>

<div class="modal fade" id="deleteModalAuto" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Hapus Data?</h5>
        <a href="cancel.php?kode=<?= $data['kode_brg'] ?>" class="btn-close"></a>
      </div>
      <div class="modal-body text-center">
        <img src="/final-project-sbd/img/ex.jpg" alt="Peringatan" style="width: 90px; margin-bottom: 15px;">
        <p>Yakin ingin menghapus <strong><?= $data['nama_brg'] ?></strong>?</p>
      </div>
      <div class="modal-footer">
        <a href="cancel.php?kode=<?= $data['kode_brg'] ?>" class="btn btn-secondary">Batal</a>
        <a href="delete.php?kode=<?= $data['kode_brg'] ?>" class="btn btn-danger">Hapus</a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php include $root . '/components/footer.php'; ?>
