<?php 
session_start();
include '../components/header.php';
include '../config/koneksi.php';

$user = 'anonymous@' . $_SERVER['REMOTE_ADDR'];

$isEdit = isset($_GET['kode']);
$data = [
  'kode_brg' => '',
  'nama_brg' => '',
  'satuan' => '',
  'jml_stok' => '',
];

// MODE EDIT
if ($isEdit) {
  $kode = $_GET['kode'];

  // Ambil data stok
  $result = $conn->query("SELECT * FROM stok WHERE kode_brg = '$kode'");
  if ($result->num_rows === 0) {
    $_SESSION['error'] = "Data dengan kode $kode tidak ditemukan.";
    header("Location: index.php");
    exit;
  }

  $data = $result->fetch_assoc();

  // Cek apakah sedang dikunci oleh user lain
  if ($data['is_locked'] == 1 && $data['locked_by'] !== $user) {
    $_SESSION['error'] = "Data sedang digunakan oleh user lain: {$data['locked_by']}.";
    header("Location: index.php");
    exit;
  }

  // Lock data untuk user ini
  $conn->query("UPDATE stok 
                SET is_locked = 1, locked_by = '$user', locked_at = NOW() 
                WHERE kode_brg = '$kode'");
}
?>

<div class="container mt-5">
  <nav aria-label="breadcrumb" class="mb-5">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="/final-project-sbd/index.php">Home</a>
      <li class="breadcrumb-item"><a href="/final-project-sbd/stok/index.php">Daftar Stok Barang</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Edit Stok' : 'Tambah Stok' ?></li>
    </ol>
  </nav>

  <h1 class="fs-3 fw-bold"><?= $isEdit ? 'Edit Stok' : 'Tambah Stok' ?></h1>
  <p><?= $isEdit ? 'Ubah data barang berikut.' : 'Isi form berikut untuk menambahkan data stok baru.' ?></p>

  <?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php endif; ?>

  <div class="card overflow-hidden mt-4">
    <div class="card-body pt-0 bg-light">
      <form action="<?= $isEdit ? 'update.php' : 'store.php' ?>" method="POST" class="mt-4">
        <div class="mb-3">
          <label for="kode_brg" class="form-label">Kode Barang</label>
          <input type="text" class="form-control" id="kode_brg" name="kode_brg"
            value="<?= htmlspecialchars($data['kode_brg']) ?>" <?= $isEdit ? 'readonly' : 'required' ?> />
        </div>
        <div class="mb-3">
          <label for="nama_brg" class="form-label">Nama Barang</label>
          <input type="text" class="form-control" id="nama_brg" name="nama_brg"
            value="<?= htmlspecialchars($data['nama_brg']) ?>" required />
        </div>
        <div class="mb-3">
          <label for="satuan" class="form-label">Satuan</label>
          <input type="text" class="form-control" id="satuan" name="satuan"
            value="<?= htmlspecialchars($data['satuan']) ?>" required />
        </div>
        <div class="mb-3">
          <label for="jml_stok" class="form-label">Jumlah Stok</label>
          <input type="number" class="form-control" id="jml_stok" name="jml_stok"
            value="<?= htmlspecialchars($data['jml_stok']) ?>" required />
        </div>
        <div class="d-flex justify-content-end mt-5 gap-2">
          <button type="submit" class="btn btn-success">
            <?= $isEdit ? 'Simpan Perubahan' : 'Simpan' ?>
          </button>

          <button type="reset" class="btn btn-danger">Reset</button>

          <a href="cancel.php<?= $isEdit ? '?kode=' . $data['kode_brg'] : '' ?>" class="btn btn-secondary">Batal</a>
        </div>

      </form>
    </div>
  </div>
</div>

<?php include '../components/footer.php'; ?>