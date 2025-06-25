<div class="navbar d-flex align-items-center justify-content-between px-4 py-2 shadow-sm">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="/img/logo1.jpeg" alt="Logo" width="55" height="55" class="me-2"/>
        <span class="fw-bold fs-4">Stokit</span>
    </a>

    <?php $current = basename($_SERVER['PHP_SELF']); ?>
    <ul class="nav nav-pills d-flex gap-4">
        <li class="nav-item">
        <a class="nav-link <?= ($current == 'index.php' && strpos($_SERVER['REQUEST_URI'], '/stok/') === false && strpos($_SERVER['REQUEST_URI'], '/penjualan/') === false) ? 'active' : '' ?>" href="/index.php">Home</a>
        </li>
        <li class="nav-item">
        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/stok/index.php') !== false ? 'active' : '' ?>" href="/stok/index.php">Stok</a>
        </li>
        <li class="nav-item">
        <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/penjualan/index.php') !== false ? 'active' : '' ?>" href="/penjualan/index.php">Penjualan</a>
        </li>
    </ul>
</div>