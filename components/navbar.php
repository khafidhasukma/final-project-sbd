<?php
    $current = basename($_SERVER['PHP_SELF']);
    $uri = $_SERVER['REQUEST_URI'];
?>
<div class="navbar d-flex align-items-center justify-content-between px-4 py-2 shadow-sm">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
        <img src="/final-project-sbd/img/logo1.jpeg" alt="Logo" width="55" height="55" class="me-2">
        <span class="fw-bold fs-4">Stokit</span>
    </a>

    <ul class="nav nav-pills d-flex gap-4">
        <li class="nav-item">
            <a class="nav-link <?= ($current == 'index.php' && strpos($uri, '/stok/') === false && strpos($uri, '/penjualan/') === false) ? 'active' : '' ?>"
            href="/final-project-sbd/index.php">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= strpos($uri, '/stok/') !== false ? 'active' : '' ?>"
            href="/final-project-sbd/stok/index.php">Stok</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= strpos($uri, '/penjualan/') !== false ? 'active' : '' ?>"
            href="/final-project-sbd/penjualan/index.php">Penjualan</a>
        </li>
    </ul>
</div>
