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

$user = $_SESSION['username'] ?? 'guest';
$pageError = '';
$isLockedByOther = false;

$barangList = $conn->query("SELECT * FROM stok")->fetch_all(MYSQLI_ASSOC);

if ($isEdit) {
  $transaksi = $_GET['transaksi'];

  // Ambil status kunci record
  $lockCheck = $conn->query("SELECT is_locked, locked_by FROM t_jual WHERE kd_trans = '$transaksi'");
  if ($lockCheck->num_rows > 0) {
  $lockData = $lockCheck->fetch_assoc();

  // Coba ambil lock hanya jika belum terkunci atau sudah dikunci oleh user ini
  $conn->query("UPDATE t_jual SET is_locked = 1, locked_by = '$user' 
                WHERE kd_trans = '$transaksi' 
                AND (is_locked = 0 OR locked_by = '$user')");

  if ($conn->affected_rows === 0 && $lockData['locked_by'] !== $user) {
    // Gagal mengunci → berarti user lain sedang edit
    $pageError = "Record sedang dikunci oleh user lain: <strong>{$lockData['locked_by']}</strong>. Silakan tunggu hingga mereka selesai.";
    $isLockedByOther = true;
  }
}

  // Ambil data
  $result = $conn->query("SELECT * FROM t_jual WHERE kd_trans = '$transaksi'");
  if ($result->num_rows === 0) {
    $_SESSION['error'] = "Data tidak ditemukan.";
    header("Location: index.php");
    exit;
  }
  $data = $result->fetch_assoc();
}
?>

<div class="container mt-5">
  <nav aria-label="breadcrumb" class="mb-5">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="/final-project-sbd/index.php">Home</a></li>
      <li class="breadcrumb-item"><a href="/final-project-sbd/penjualan/index.php">Daftar Penjualan</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Edit Penjualan' : 'Tambah Penjualan' ?></li>
    </ol>
  </nav>

  <h1 class="fs-3 fw-bold"><?= $isEdit ? 'Edit Penjualan' : 'Tambah Penjualan' ?></h1>
  <p><?= $isEdit ? 'Ubah data Transaksi Penjualan berikut.' : 'Isi form berikut untuk menambahkan data Transaksi Penjualan baru.' ?></p>

  <!-- ALERT -->
  <?php if (!empty($pageError)): ?>
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
      <?= $pageError ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php endif; ?>

  <?php if (!$isLockedByOther): ?>
  <div class="card overflow-hidden mt-4">
    <div class="card-body pt-0 bg-light">
      <form action="<?= $isEdit ? 'update.php' : 'store.php' ?>" method="POST" class="mt-4">
        <div class="mb-3">
          <label for="kd_trans" class="form-label">Kode Transaksi</label>
          <input type="text" class="form-control" id="kd_trans" name="kd_trans"
            value="<?= htmlspecialchars($data['kd_trans']) ?>" <?= $isEdit ? 'readonly' : 'required' ?> />
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
              <option value="<?= $barang['kode_brg'] ?>" <?= ($data['kode_brg'] == $barang['kode_brg']) ? 'selected' : '' ?>>
                <?= $barang['kode_brg'] ?> - <?= $barang['nama_brg'] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="jml_jual" class="form-label">Jumlah Jual</label>
          <input type="number" class="form-control" id="jml_jual" name="jml_jual"
            value="<?= htmlspecialchars($data['jml_jual']) ?>" required />
        </div>

        <div class="d-flex justify-content-end mt-5">
          <button type="submit" class="btn btn-success"><?= $isEdit ? 'Simpan Perubahan' : 'Simpan' ?></button>
          <button type="reset" class="btn btn-danger ms-2">Reset</button>
          <a href="cancel.php?id=<?= $data['kd_trans'] ?>" class="btn btn-secondary ms-2">Batal</a>
        </div>
      </form>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php include '../components/footer.php'; ?>
