<?php
include '../components/header.php';
include '../config/koneksi.php';

// kode barang dari parameter URL
if (!isset($_GET['kode'])) {
  echo "Kode barang tidak ditemukan.";
  exit;
}

$kode = $_GET['kode'];

// data dari database
$result = $conn->query("SELECT * FROM stok WHERE kode_brg = '$kode'");
if ($result->num_rows === 0) {
  echo "Data tidak ditemukan.";
  exit;
}

$row = $result->fetch_assoc();
?>

<div class="container mt-5">
  <h1 class="fs-3 fw-bold">Edit Stok Barang</h1>
  <p>Ubah data berikut untuk memperbarui stok barang.</p>

  <div class="card mt-4">
    <div class="card-body">
      <form action="update.php" method="POST">
        <input type="hidden" name="kode_brg" value="<?= $row['kode_brg'] ?>">

        <div class="mb-3">
          <label for="nama_brg" class="form-label">Nama Barang</label>
          <input type="text" class="form-control" name="nama_brg" value="<?= $row['nama_brg'] ?>" required>
        </div>

        <div class="mb-3">
          <label for="satuan" class="form-label">Satuan</label>
          <input type="text" class="form-control" name="satuan" value="<?= $row['satuan'] ?>" required>
        </div>

        <div class="mb-3">
          <label for="jml_stok" class="form-label">Jumlah Stok</label>
          <input type="number" class="form-control" name="jml_stok" value="<?= $row['jml_stok'] ?>" required>
        </div>

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-success">Simpan</button>
          <a href="index.php" class="btn btn-secondary ms-2">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../components/footer.php'; ?>
