<?php
session_start();
include '../components/header.php';
include '../config/koneksi.php';

$user = $_SESSION['username'] ?? 'anonymous@' . $_SERVER['REMOTE_ADDR'];
$isEdit = isset($_GET['transaksi']);
$isTambah = !isset($_GET['transaksi']);

$data = [
  'kd_trans' => '',
  'tgl_trans' => '',
  'kode_brg' => '',
  'jml_jual' => '',
];

// Ambil daftar barang dari tabel stok
$barangList = $conn->query("SELECT * FROM stok")->fetch_all(MYSQLI_ASSOC);

// ✅ CEK JIKA MODE TAMBAH
if ($isTambah) {
  $cek = $conn->query("SELECT * FROM global_lock WHERE module = 'penjualan-tambah'");
  $lock = $cek->fetch_assoc();

  if ($lock['is_locked'] == 1 && $lock['locked_by'] !== $user) {
    $_SESSION['error'] = "Form tambah data sedang digunakan oleh user lain.";
    header("Location: index.php");
    exit;
  }

  // Lock form tambah
  $conn->query("UPDATE global_lock 
                SET is_locked = 1, locked_by = '$user', locked_at = NOW() 
                WHERE module = 'penjualan-tambah'");
}


// 🛠 MODE EDIT
if ($isEdit) {
  $transaksi = $_GET['transaksi'];

  // Cek apakah record dikunci oleh user lain
  $lockCheck = $conn->query("SELECT is_locked, locked_by FROM t_jual WHERE kd_trans = '$transaksi'");
  $lockData = $lockCheck->fetch_assoc();

  if ($lockData && $lockData['is_locked'] == 1 && $lockData['locked_by'] !== $user) {
    $_SESSION['error'] = "Transaksi sedang diedit oleh user lain.";
    header("Location: index.php");
    exit;
  }

  // Kunci record untuk user saat ini
  $conn->query("UPDATE t_jual SET is_locked = 1, locked_by = '$user', locked_at = NOW() 
                WHERE kd_trans = '$transaksi'");

  // Ambil data penjualan
  $result = $conn->query("SELECT * FROM t_jual WHERE kd_trans = '$transaksi'");
  if ($result->num_rows === 0) {
    $_SESSION['error'] = "Data dengan kode transaksi $transaksi tidak ditemukan.";
    header("Location: index.php");
    exit;
  }

  $data = $result->fetch_assoc();
}
?>

<div class="container mt-5">
  <nav aria-label="breadcrumb" class="mb-5">
    <ol class="breadcrumb align-items-center">
      <li class="breadcrumb-item"><a href="/">Home</a></li>
      <li class="breadcrumb-item"><a href="/final-project-sbd/penjualan/index.php">Daftar Penjualan</a></li>
      <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Edit Transaksi' : 'Tambah Transaksi' ?></li>
    </ol>
  </nav>

  <h1 class="fs-3 fw-bold"><?= $isEdit ? 'Edit Transaksi' : 'Tambah Transaksi' ?></h1>
  <p><?= $isEdit ? 'Ubah data transaksi berikut.' : 'Isi form berikut untuk menambahkan transaksi baru.' ?></p>

  <?php if (isset($_SESSION['error'])): ?>
  <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
  </div>
  <?php endif; ?>

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
            <option value="<?= $barang['kode_brg'] ?>"
              <?= ($data['kode_brg'] == $barang['kode_brg']) ? 'selected' : '' ?>>
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
          <a href="cancel.php?id=<?= $isEdit ? $data['kd_trans'] : '' ?>" class="btn btn-secondary ms-2">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include '../components/footer.php'; ?>