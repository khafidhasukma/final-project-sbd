<?php
session_start();

// ✅ Cek apakah sudah login via MySQL user
if (!isset($_SESSION['db_user'])) {
    ?>
    <script>
        window.location.href = '/final-project-sbd/login/index.php';
    </script>
    <?php
    exit;
}

$current_user = $_SESSION['client_name'];

$root = $_SERVER['DOCUMENT_ROOT'] . '/final-project-sbd';
include $root . '/components/header.php';
include $root . '/config/koneksi.php';

// Auto-unlock jika dikunci > 5 menit
$now = date('Y-m-d H:i:s');
$conn->query("UPDATE stok SET is_locked = 0, locked_by = NULL, locked_at = NULL 
              WHERE is_locked = 1 AND TIMESTAMPDIFF(MINUTE, locked_at, '$now') >= 5");

$conn->query("UPDATE global_lock 
              SET is_locked = 0, locked_by = NULL, locked_at = NULL 
              WHERE module = 'stok-tambah' 
              AND is_locked = 1 
              AND TIMESTAMPDIFF(MINUTE, locked_at, NOW()) >= 5");
?>

<div class="container mt-5">
  <nav aria-label="breadcrumb" class="mb-5">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="/final-project-sbd/index.php">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Daftar Stok Barang</li>
    </ol>
  </nav>

  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h1 class="fs-3 fw-bold">Daftar Stok Barang</h1>
      <p>Berikut adalah daftar stok barang saat ini.</p>
      <p class="text-muted">Login sebagai: <strong><?= htmlspecialchars($current_user) ?></strong></p>
    </div>
    <a href="lock-create.php" class="btn" style="font-weight: bold;background-color: #6b91e4; color: white;">Tambah
      Data</a>
  </div>

  <?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
  </div>
  <?php endif; ?>

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
            $locked = ($row['is_locked'] == 1 && $row['locked_by'] !== $current_user);
        ?>
        <tr>
          <td><?= $no++ ?></td>
          <td><?= $row['kode_brg'] ?></td>
          <td><?= $row['nama_brg'] ?></td>
          <td><?= $row['satuan'] ?></td>
          <td><?= $row['jml_stok'] ?></td>
          <td class="d-flex gap-2 align-items-center">
            <a href="show.php?kode=<?= $row['kode_brg'] ?>" class="btn btn-sm btn-info text-white">Detail</a>
            <a href="create-edit.php?kode=<?= $row['kode_brg'] ?>" class="btn btn-sm btn-warning" <?= $locked ? 'disabled' : '' ?>>Edit</a>
            <a href="lock-delete.php?kode=<?= $row['kode_brg'] ?>" class="btn btn-sm btn-danger" <?= $locked ? 'disabled' : '' ?>>
              Hapus
            </a>
          </td>
        </tr>

        <!-- Modal Delete -->
        <div class="modal fade" id="deleteModal<?= $row['kode_brg'] ?>" tabindex="-1"
          aria-labelledby="deleteModalLabel<?= $row['kode_brg'] ?>" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteModalLabel<?= $row['kode_brg'] ?>">Hapus Data?</h1>
                <a href="cancel.php?kode=<?= $row['kode_brg'] ?>" class="btn-close" aria-label="Tutup"></a>
              </div>
              <div class="modal-body text-center">
                <img src="/final-project-sbd/img/ex.jpg" alt="Peringatan" style="width: 90px; margin-bottom: 15px;">
                <p>Apakah Anda yakin ingin menghapus <strong><?= $row['nama_brg'] ?></strong>?</p>
              </div>
              <div class="modal-footer">
                <a href="cancel.php?kode=<?= $row['kode_brg'] ?>" class="btn btn-secondary">Batal</a>
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

<?php
// Auto-tampilkan modal jika redirect dari lock-delete.php
if (isset($_GET['delete'])):
  $kode = $_GET['delete'];
  $res = $conn->query("SELECT * FROM stok WHERE kode_brg = '$kode'");
  $data = $res->fetch_assoc();

  if (!$data) {
    $_SESSION['error'] = "Data $kode sudah dihapus.";
    header("Location: /final-project-sbd/index.php");
    exit;
  }
?>
<script>
window.addEventListener('DOMContentLoaded', () => {
  const modal = new bootstrap.Modal(document.getElementById('deleteModal<?= $data['kode_brg'] ?>'));
  modal.show();
});
</script>
<?php endif; ?>

<?php include $root . '/components/footer.php'; ?>
