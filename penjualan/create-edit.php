<?php include '../components/header.php'; ?>
  <div class="container">
    <div class="mt-5">
      <h1 class="fs-3 fw-bold">Tambah Transaksi Penjualan</h1>
      <p>Isi form berikut untuk menambahkan Transaksi Penjualan baru.</p>

      <div class="card overflow-hidden mt-4">
        <div class="card-body pt-0 bg-light">
          <form action="store.php" method="POST" class="mt-4">
              <div class="mb-3">
                <label for="kd_trans" class="form-label">Kode Transaksi</label>
                <input type="text" class="form-control" placeholder="Masukkan kode transaksi..." id="kd_trans" name="kd_trans" required />
              </div>
              <div class="mb-3">
                <label for="tgl_trans" class="form-label">Tanggal Transaksi</label>
                <input type="date" class="form-control" placeholder="Masukkan tanggal transaksi..." id="tgl_trans" name="tgl_trans" required />
              </div>
              <div class="mb-3">
                <label for="kode_brg" class="form-label">Kode Barang</label>
                <input type="text" class="form-control" placeholder="Masukkan kode barang..." id="kode_brg" name="kode_brg" required />
              </div>
              <div class="mb-3">
                <label for="jml_jual" class="form-label">Jumlah Jual</label>
                <input type="number" class="form-control" placeholder="Masukkan jumlah jual..." id="jml_jual" name="jml_jual" required />
              </div>

              <div class="d-flex justify-content-end mt-5">
                <!-- Reset -->
                <button type="submit" class="btn btn-success">Simpan</button>
                <button type="reset" class="btn btn-danger ms-2">Reset</button>
                <!-- Batal -->
                <a href="/stok/index.php" class="btn btn-secondary ms-2">Batal</a>
              </div>
            </form>
        </div>
      </div>
  </div>
</div>
<?php include '../components/footer.php'; ?>
