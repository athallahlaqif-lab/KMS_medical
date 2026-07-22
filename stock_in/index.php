<?php
declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

// Ambil data stock in & filter
require_once 'stockin_data.php';

$pageTitle = 'Stock In';
include '../includes/header.php';
?>

<div class="container-fluid px-4 py-3">
    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-1 text-primary"><i class="bi bi-box-arrow-in-down me-2"></i>Stock In</h4>
            <p class="text-muted small mb-0">Kelola seluruh transaksi barang masuk pada sistem KMS Medical.</p>
        </div>
        <div class="d-flex gap-2">
            <!-- Tombol Back ke Dashboard -->
            <a href="../dashboard/index.php" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Dashboard
            </a>
            <!-- Tombol Add Stock In -->
            <a href="create.php" class="btn btn-primary rounded-pill px-3 shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Add Stock In
            </a>
        </div>
    </div>

    <!-- Alert Status -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Transaksi stock in berhasil ditambahkan!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Cari Produk</label>
                    <input type="text" name="search" class="form-control form-control-sm rounded-pill" 
                           placeholder="Nama/Kode produk..." value="<?= htmlspecialchars($_GET['search'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control form-control-sm rounded-pill" 
                           value="<?= htmlspecialchars($_GET['start_date'] ?? ''); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control form-control-sm rounded-pill" 
                           value="<?= htmlspecialchars($_GET['end_date'] ?? ''); ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end mt-auto">
                    <button type="submit" class="btn btn-primary btn-sm rounded-pill w-100 me-1">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-muted text-uppercase small">
                            <th class="ps-4">No</th>
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Before</th>
                            <th class="text-center">After</th>
                            <th>Date</th>
                            <th>Note</th>
                            <th class="text-center pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stockIn)): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-1"></i> Tidak ada data transaksi stock in.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($stockIn as $row): ?>
                                <tr>
                                    <td class="ps-4 font-weight-bold"><?= $no++; ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($row['product_code'] ?? '-'); ?></span></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($row['product_name'] ?? 'Unknown'); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-success rounded-pill px-3"><?= number_format((float)$row['qty']); ?></span>
                                    </td>
                                    <td class="text-center text-muted"><?= number_format((float)$row['stock_before']); ?></td>
                                    <td class="text-center fw-bold text-success"><?= number_format((float)$row['stock_after']); ?></td>
                                    <td><?= date('d M Y', strtotime($row['transaction_date'])); ?></td>
                                    <td><?= htmlspecialchars($row['note'] ?? '-'); ?></td>
                                    <td class="text-center pe-4">
                                        <a href="print.php?id=<?= $row['id']; ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-3" title="Cetak Struk">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Script Pencegah Tombol Back Browser Kiri -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Timpa riwayat browser agar saat ditekan Back tidak kembali ke form penambahan data
    if (window.history && window.history.pushState) {
        window.history.pushState('forward', null, './index.php');
        window.onpopstate = function() {
            window.location.href = '../dashboard/index.php';
        };
    }
});
</script>

<?php include '../includes/footer.php'; ?>