<?php
session_start();

$root = $_SERVER['DOCUMENT_ROOT'] . '/final-project-sbd';
include $root . '/components/header.php';
include $root . '/config/koneksi.php';

$user = $_SESSION['username'] ?? 'guest';

// Auto-unlock jika lebih dari 5 menit
$conn->query("UPDATE global_lock 
              SET is_locked = 0, locked_by = NULL, locked_at = NULL 
              WHERE module = 'penjualan-tambah' 
              AND is_locked = 1 
              AND TIMESTAMPDIFF(MINUTE, locked_at, NOW()) >= 5");


// Auto-unlock untuk penambahan transaksi
$now = date('Y-m-d H:i:s');
$conn->query("UPDATE t_jual SET is_locked = 0, locked_by = NULL, locked_at = NULL 
              WHERE kd_trans = '__NEW__' AND is_locked = 1 
              AND TIMESTAMPDIFF(MINUTE, locked_at, '$now') >= 5");

// Cek apakah dummy data sedang di-lock user lain
$dummyLocked = false;
$dummyBy = '';
$check = $conn->query("SELECT is_locked, locked_by FROM t_jual WHERE kd_trans = '__NEW__'");
if ($check && $r = $check->fetch_assoc()) {
  if ($r['is_locked'] == 1 && $r['locked_by'] !== $user) {
    $dummyLocked = true;
    $dummyBy = $r['locked_by'];
  }
}
?>

<div class="container mt-5">
  <nav aria-label="breadcrumb" class="mb-5">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="/final-project-sbd/index.php">Home</a></li>
      <li class="breadcrumb-item active" aria-current="page">Daftar Transaksi Penjualan</li>
    </ol>
  </nav>

  <div class="d-flex justify-content-between align-items-center">
    <div>
      <h1 class="fs-3 fw-bold">Daftar Transaksi Penjualan</h1>
      <p>Berikut adalah daftar transaksi penjualan saat ini.</p>
    </div>
    <?php if ($dummyLocked): ?>
      <button class="btn btn-secondary" disabled>
        User lain sedang menambahkan transaksi (<?= $dummyBy ?>)
      </button>
    <?php else: ?>
      <a href="lock-create.php" class="btn" style="font-weight: bold;background-color: #6b91e4; color: white;">
        Tambah Transaksi
      </a>
    <?php endif; ?>
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
          <th>Kode Transaksi</th>
          <th>Tanggal Transaksi</th>
          <th>Kode Barang</th>
          <th>Jumlah Jual</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT * FROM t_jual WHERE kd_trans != '__NEW__'");
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
            <a href="show.php?transaksi=<?= $row['kd_trans'] ?>" class="btn btn-sm btn-info text-white">Detail</a>
            <a href="create-edit.php?transaksi=<?= $row['kd_trans'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="lock-delete.php?transaksi=<?= $row['kd_trans'] ?>" class="btn btn-sm btn-danger">Hapus</a>
          </td>
        </tr>
        <?php endwhile; else: ?>
        <tr>
          <td colspan="6" class="text-center text-muted py-4">Belum ada data Transaksi.</td>
        </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Auto Show Modal Delete -->
<?php if (isset($_GET['delete'])):
  $kode = $_GET['delete'];
  $res = $conn->query("SELECT * FROM t_jual WHERE kd_trans = '$kode'");
  $data = $res->fetch_assoc();

  if (!$data) {
    $_SESSION['error'] = "Data transaksi $kode sudah dihapus oleh user lain.";
    header("Location: index.php");
    exit;
  }
?>
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
        <h5 class="modal-title">Hapus Transaksi?</h5>
        <a href="cancel.php?id=<?= $data['kd_trans'] ?>" class="btn-close"></a>
      </div>
      <div class="modal-body text-center">
        <img src="/final-project-sbd/img/ex.jpg" alt="Peringatan" style="width: 90px; margin-bottom: 15px;">
        <p>Yakin ingin menghapus <strong><?= $data['kd_trans'] ?></strong>?</p>
      </div>
      <div class="modal-footer">
        <a href="cancel.php?id=<?= $data['kd_trans'] ?>" class="btn btn-secondary">Batal</a>
        <a href="delete-confirm.php?transaksi=<?= $data['kd_trans'] ?>" class="btn btn-danger">Hapus</a>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

<?php include $root . '/components/footer.php'; ?>
