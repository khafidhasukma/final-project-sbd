<?php
session_start();
include '../components/header.php';
include '../config/koneksi.php';

$isEdit = isset($_GET['transaksi']);
$data = [
  'kd_trans' => '',
  'tgl_trans' => '',
  'kode_brg' => '',
  'jml_jual' => '',
];

// Ambil daftar barang
$barangList = $conn->query("SELECT * FROM stok")->fetch_all(MYSQLI_ASSOC);

if ($isEdit) {
  $transaksi = $_GET['transaksi'];
  $result = $conn->query("SELECT * FROM t_jual WHERE kd_trans = '$transaksi'");
  if ($result->num_rows === 0) {
    $_SESSION['error'] = "Data dengan kode $transaksi tidak ditemukan.";
    header("Location: index.php");
    exit;
  }
  $data = $result->fetch_assoc();
}
?>

<div class="container mt-5">
  <h1 class="fs-3 fw-bold"><?= $isEdit ? 'Edit Transaksi Penjualan' : 'Tambah Transaksi Penjualan' ?></h1>
  <p>
    <?= $isEdit ? 'Ubah data Transaksi Penjualan berikut.' : 'Isi form berikut untuk menambahkan data Transaksi Penjualan baru.' ?>
  </p>

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
          <label for="kd_trans" class="form-label">Kode Transaksi</label>
          <input type="text" class="form-control" id="kd_trans" name="kd_trans"
            placeholder="Masukkan kode Transaksi Penjualan..." value="<?= htmlspecialchars($data['kd_trans']) ?>"
            <?= $isEdit ? 'readonly' : 'required' ?> />
        </div>

        <div class="mb-3">
          <label for="tgl_trans" class="form-label">Tanggal Transaksi</label>
          <input type="date" class="form-control" id="tgl_trans" name="tgl_trans"
            value="<?= htmlspecialchars($data['tgl_trans']) ?>" required />
        </div>

        <div class="mb-3">
          <label for="kode_brg" class="form-label">Kode Barang</label>
          <select name="kode_brg" id="kode_brg" class="form-control" required>
            <option value="">-- Pilih Kode Barang --</option>
            <?php foreach ($barangList as $barang): ?>
            <option value="<?= $barang['kode_brg'] ?>"
              <?= ($data['kode_brg'] == $barang['kode_brg']) ? 'selected' : '' ?>>
              <?= $barang['kode_brg'] ?> - <?= $barang['nama_brg'] ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="jml_jual" class="form-label">Jumlah Jual</label>
          <input type="number" class="form-control" id="jml_jual" name="jml_jual" placeholder="Masukkan jumlah jual..."
            value="<?= htmlspecialchars($data['jml_jual']) ?>" required />
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