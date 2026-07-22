<?php

declare(strict_types=1);

$pageTitle = 'Dashboard';
$pageIcon  = 'bi-speedometer2';

require_once '../config/session.php';
require_once '../config/database.php';
require_once 'dashboard_data.php';

requireLogin();

include '../includes/header.php';

?>

<div class="wrapper">

    <?php include '../includes/sidebar.php'; ?>

    <div class="main">

        <?php include '../includes/navbar.php'; ?>

        <div class="content">

            <!-- Welcome Banner -->
            <div class="card border-0 shadow rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-1">
                                <i class="bi bi-speedometer2 text-primary me-2"></i>Dashboard KMS Medical
                            </h2>
                            <p class="text-muted mb-0">
                                Selamat datang kembali, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Administrator'); ?></strong>!
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <img
                                src="<?= BASE_URL ?>assets/images/logo-kms.png"
                                class="dashboard-logo"
                                alt="KMS Logo"
                                width="110">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Action Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <a href="../products/create.php" class="card border-0 shadow-sm rounded-4 text-decoration-none quick-card h-100">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-plus-circle-fill text-primary fs-1"></i>
                            <h5 class="mt-3 text-dark fw-bold">Tambah Produk</h5>
                            <small class="text-muted">Kelola data produk</small>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <a href="../stock_in/create.php" class="card border-0 shadow-sm rounded-4 text-decoration-none quick-card h-100">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-box-arrow-in-down text-success fs-1"></i>
                            <h5 class="mt-3 text-dark fw-bold">Stock In</h5>
                            <small class="text-muted">Input barang masuk</small>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <a href="../stock_out/create.php" class="card border-0 shadow-sm rounded-4 text-decoration-none quick-card h-100">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-box-arrow-up text-danger fs-1"></i>
                            <h5 class="mt-3 text-dark fw-bold">Stock Out</h5>
                            <small class="text-muted">Input barang keluar</small>
                        </div>
                    </a>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <a href="../reports/index.php" class="card border-0 shadow-sm rounded-4 text-decoration-none quick-card h-100">
                        <div class="card-body text-center py-4">
                            <i class="bi bi-file-earmark-bar-graph text-warning fs-1"></i>
                            <h5 class="mt-3 text-dark fw-bold">Reports</h5>
                            <small class="text-muted">Lihat seluruh laporan</small>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Kartu Statistik Utama (Teks & Angka Putih) -->
            <div class="row g-4 mb-4">

                <!-- Total Products -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-primary text-white border-0 rounded-4 shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center p-4">
                            <div>
                                <small class="text-white-50 fw-semibold text-uppercase">Total Products</small>
                                <p class="mb-2 text-white-50 small opacity-75">Updated Today</p>
                                <h2 class="fw-bold mb-0 text-white display-6"><?= $totalProducts ?></h2>
                            </div>
                            <i class="bi bi-capsule fs-1 text-white opacity-50"></i>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-success text-white border-0 rounded-4 shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center p-4">
                            <div>
                                <small class="text-white-50 fw-semibold text-uppercase">Categories</small>
                                <p class="mb-2 text-white-50 small opacity-75">Active</p>
                                <h2 class="fw-bold mb-0 text-white display-6"><?= $totalCategories ?></h2>
                            </div>
                            <i class="bi bi-tags-fill fs-1 text-white opacity-50"></i>
                        </div>
                    </div>
                </div>

                <!-- Suppliers -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-warning text-white border-0 rounded-4 shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center p-4">
                            <div>
                                <small class="text-white-50 fw-semibold text-uppercase">Suppliers</small>
                                <p class="mb-2 text-white-50 small opacity-75">Registered</p>
                                <h2 class="fw-bold mb-0 text-white display-6"><?= $totalSuppliers ?></h2>
                            </div>
                            <i class="bi bi-truck fs-1 text-white opacity-50"></i>
                        </div>
                    </div>
                </div>

                <!-- Users -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card bg-danger text-white border-0 rounded-4 shadow-sm">
                        <div class="card-body d-flex justify-content-between align-items-center p-4">
                            <div>
                                <small class="text-white-50 fw-semibold text-uppercase">Users</small>
                                <p class="mb-2 text-white-50 small opacity-75">System Users</p>
                                <h2 class="fw-bold mb-0 text-white display-6"><?= $totalUsers ?></h2>
                            </div>
                            <i class="bi bi-people-fill fs-1 text-white opacity-50"></i>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Low Stock & Recent Transactions -->
            <div class="row mb-4">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="card border-0 shadow rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Stok Menipis
                            </h5>
                            <?php if (isset($lowStocks) && count($lowStocks) > 0): ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($lowStocks as $item): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                            <span><?= htmlspecialchars($item['product_name']); ?></span>
                                            <span class="badge bg-danger rounded-pill px-3 py-2">
                                                <?= $item['stock']; ?>
                                            </span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="alert alert-success mb-0 rounded-3">
                                    <i class="bi bi-check-circle-fill me-2"></i>Semua stok produk aman.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card border-0 shadow rounded-4 h-100">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-clock-history text-primary me-2"></i>Transaksi Terakhir
                            </h5>
                            <?php if (isset($recentTransactions) && count($recentTransactions) > 0): ?>
                                <ul class="list-group list-group-flush">
                                    <?php foreach ($recentTransactions as $trx): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                            <div>
                                                <strong><?= htmlspecialchars($trx['product_name']); ?></strong>
                                                <br>
                                                <small class="text-muted">
                                                    <?= date('d M Y', strtotime($trx['transaction_date'])); ?>
                                                </small>
                                            </div>
                                            <?php if ($trx['type'] == 'IN'): ?>
                                                <span class="badge bg-success rounded-pill px-3 py-2">
                                                    +<?= $trx['qty']; ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                                    -<?= $trx['qty']; ?>
                                                </span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <div class="alert alert-secondary mb-0 rounded-3">
                                    Belum ada transaksi recorded.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart & Quick Info -->
            <div class="row">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <div class="card border-0 shadow rounded-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-4">
                                <i class="bi bi-bar-chart-fill text-primary me-2"></i>Dashboard Analytics
                            </h5>
                            <canvas id="dashboardChart" height="120"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow rounded-4">
                        <div class="card-body p-4">
                            <h5 class="fw-bold mb-3">
                                <i class="bi bi-lightning-charge-fill text-warning me-2"></i>Quick Information
                            </h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                    <span>Products</span>
                                    <strong><?= $totalProducts ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                    <span>Categories</span>
                                    <strong><?= $totalCategories ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                    <span>Suppliers</span>
                                    <strong><?= $totalSuppliers ?></strong>
                                </li>
                                <li class="list-group-item d-flex justify-content-between px-0 py-2">
                                    <span>Users</span>
                                    <strong><?= $totalUsers ?></strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <?php include '../includes/footer.php'; ?>

        </div> <!-- /.content -->

    </div> <!-- /.main -->

</div> <!-- /.wrapper -->

<?php include '../includes/scripts.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('dashboardChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Products', 'Categories', 'Suppliers', 'Users'],
                datasets: [{
                    label: 'Total Data',
                    data: [
                        <?= (int)$totalProducts ?>,
                        <?= (int)$totalCategories ?>,
                        <?= (int)$totalSuppliers ?>,
                        <?= (int)$totalUsers ?>
                    ],
                    backgroundColor: [
                        'rgba(37, 99, 235, 0.75)',
                        'rgba(16, 185, 129, 0.75)',
                        'rgba(245, 158, 11, 0.75)',
                        'rgba(239, 68, 68, 0.75)'
                    ],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
</script>

</body>
</html>