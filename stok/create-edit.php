<?php
session_start();
include '../components/header.php';
include '../config/koneksi.php';

$isEdit = isset($_GET['kode']);
$data = [
  'kode_brg' => '',
  'nama_brg' => '',
  'satuan' => '',
  'jml_stok' => '',
];

if ($isEdit) {
  $kode = $_GET['kode'];
  $result = $conn->query("SELECT * FROM stok WHERE kode_brg = '$kode'");
  if ($result->num_rows === 0) {
    $_SESSION['error'] = "Data dengan kode $kode tidak ditemukan.";
    header("Location: index.php");
    exit;
  }
  $data = $result->fetch_assoc();
}
?>

<div class="container mt-5">
   <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-5">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="/final-project-sbd/index.php">Home</a>
      <li class="breadcrumb-item"><a href="/final-project-sbd/stok/index.php">Daftar Stok Barang</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Edit Stok' : 'Tambah Stok' ?></li>
    </ol>
  </nav>

  <h1 class="fs-3 fw-bold"><?= $isEdit ? 'Edit Stok' : 'Tambah Stok' ?></h1>
  <p><?= $isEdit ? 'Ubah data barang berikut.' : 'Isi form berikut untuk menambahkan data stok baru.' ?></p>

  <!-- Alert Error -->
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
          <input type="text" class="form-control" id="kode_brg" name="kode_brg" placeholder="Masukkan kode barang..."
            value="<?= htmlspecialchars($data['kode_brg']) ?>" <?= $isEdit ? 'readonly' : 'required' ?> />
        </div>

        <div class="mb-3">
          <label for="nama_brg" class="form-label">Nama Barang</label>
          <input type="text" class="form-control" id="nama_brg" name="nama_brg" placeholder="Masukkan nama barang..."
            value="<?= htmlspecialchars($data['nama_brg']) ?>" required />
        </div>

        <div class="mb-3">
          <label for="satuan" class="form-label">Satuan</label>
          <input type="text" class="form-control" id="satuan" name="satuan" placeholder="Masukkan satuan..."
            value="<?= htmlspecialchars($data['satuan']) ?>" required />
        </div>

        <div class="mb-3">
          <label for="jml_stok" class="form-label">Jumlah Stok</label>
          <input type="number" class="form-control" id="jml_stok" name="jml_stok" placeholder="Masukkan jumlah stok..."
            value="<?= htmlspecialchars($data['jml_stok']) ?>" required />
        </div>

        <div class="d-flex justify-content-end mt-5">
          <button type="submit" class="btn btn-success"><?= $isEdit ? 'Simpan Perubahan' : 'Simpan' ?></button>
          <button type="reset" class="btn btn-danger ms-2">Reset</button>
          <a href="index.php" class="btn btn-secondary ms-2">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../components/footer.php'; ?>