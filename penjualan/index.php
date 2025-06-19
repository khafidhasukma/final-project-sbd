<?php include '../components/header.php'; ?>
<div class="mt-5">
  <div class="d-flex justify-content-between align-items-center flex-wrap">
    <div>
      <h1 class="fs-3 fw-bold">Daftar Penjualan</h1>
      <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Unde pariatur veritatis culpa.</p>
    </div>
    <a href="/penjualan/create.php" class="btn btn-primary">Tambah Data</a>
  </div>
  <div class="table-responsive mt-5">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Kode Transaksi</th>
          <th scope="col">Tanggal</th>
          <th scope="col">Kode Barang</th>
          <th scope="col">Jumlah Jual</th>
          <th scope="col">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>TSC001</td>
          <td>19 Januari 2025</td>
          <td>BRG001</td>
          <td>20</td>
          <td>
            <div class="d-flex gap-2 align-items-center">
              <a href="#" class="btn btn-sm btn-primary">Edit</a>
              <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Hapus
              </button>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data?</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin menghapus data?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger">Hapus</button>
      </div>
    </div>
  </div>
</div>
<?php include '../components/footer.php'; ?>