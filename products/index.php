<?php

declare(strict_types=1);

$pageTitle = 'Products';
$pageIcon  = 'bi-capsule-pill';

require_once '../config/session.php';
require_once '../config/database.php';
require_once 'product_data.php';

requireLogin();

include '../includes/header.php';

?>

<div class="wrapper">

    <?php include '../includes/sidebar.php'; ?>

    <div class="main">

        <?php include '../includes/navbar.php'; ?>

        <div class="content">

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h2 class="fw-bold mb-1">
                                <i class="bi bi-capsule-pill text-primary me-2"></i>Products
                            </h2>
                            <p class="text-muted mb-0">
                                Kelola seluruh data produk kesehatan yang tersedia pada sistem KMS Medical.
                            </p>
                        </div>
                        <a href="create.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Produk
                        </a>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow rounded-4">
                <div class="card-body p-4">

                    <form method="GET" class="row g-3 mb-4 align-items-center">
                        <div class="col-lg-6 col-md-8">
                            <div class="input-group search-box">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input
                                    type="text"
                                    name="search"
                                    class="form-control shadow-sm rounded-pill"
                                    placeholder="Cari nama produk..."
                                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="col-auto d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-search me-2"></i>Search
                            </button>
                            <a href="index.php" class="btn btn-outline-secondary rounded-pill px-4">
                                Reset
                            </a>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle mb-0">
                            <thead class="table-header">
                                <tr>
                                    <th class="text-center" style="width: 50px;">No</th>
                                    <th class="text-center" style="width: 80px;">Photo</th>
                                    <th>Code</th>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Supplier</th>
                                    <th style="min-width:120px;">Purchase</th>
                                    <th style="min-width:120px;">Selling</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center" style="width: 100px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($products) && count($products) > 0): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td class="text-center">
                                                <?php if (!empty($product['photo'])): ?>
                                                    <img
                                                        src="upload/<?= htmlspecialchars($product['photo']); ?>"
                                                        alt="<?= htmlspecialchars($product['product_name']); ?>"
                                                        class="product-image">
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">No Image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">
                                                    <?= htmlspecialchars($product['product_code']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <strong><?= htmlspecialchars($product['product_name']); ?></strong>
                                                <br>
                                                <small class="text-muted"><?= htmlspecialchars($product['unit'] ?? ''); ?></small>
                                            </td>
                                            <td><?= htmlspecialchars($product['category_name'] ?? '-'); ?></td>
                                            <td><?= htmlspecialchars($product['supplier_name'] ?? '-'); ?></td>
                                            <td class="text-nowrap">
                                                <span class="price">
                                                    Rp <?= number_format((float)($product['purchase_price'] ?? 0), 0, ',', '.'); ?>
                                                </span>
                                            </td>
                                            <td class="text-nowrap">
                                                <span class="price fw-semibold text-primary">
                                                    Rp <?= number_format((float)($product['selling_price'] ?? 0), 0, ',', '.'); ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                $stock = (int)($product['stock'] ?? 0);
                                                if ($stock > 100) {
                                                    $badge = 'bg-success';
                                                } elseif ($stock >= 20) {
                                                    $badge = 'bg-warning text-dark';
                                                } else {
                                                    $badge = 'bg-danger';
                                                }
                                                ?>
                                                <span class="badge <?= $badge; ?> px-3 py-2 rounded-pill">
                                                    <?= $stock; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a
                                                        href="edit.php?id=<?= $product['id']; ?>"
                                                        class="btn btn-warning btn-sm action-btn text-white"
                                                        title="Edit Produk">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <button
                                                        type="button"
                                                        class="btn btn-danger btn-sm action-btn"
                                                        title="Hapus Produk"
                                                        onclick="confirmDelete(<?= $product['id']; ?>)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="text-center py-5">
                                            <i class="bi bi-box-seam fs-1 text-secondary"></i>
                                            <p class="text-muted mt-2 mb-0">Belum ada data produk.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            <?php include '../includes/footer.php'; ?>

        </div> <!-- /.content -->

    </div> <!-- /.main -->

</div> <!-- /.wrapper -->

<?php include '../includes/scripts.php'; ?>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus Produk?',
        text: 'Data produk yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'delete.php?id=' + id;
        }
    });
}

<?php if (isset($_GET['success'])): ?>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: 'Produk berhasil ditambahkan.',
    timer: 1800,
    showConfirmButton: false
});
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: 'Produk berhasil diperbarui.',
    timer: 1800,
    showConfirmButton: false
});
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: 'Produk berhasil dihapus.',
    timer: 1800,
    showConfirmButton: false
});
<?php endif; ?>
</script>

</body>
</html>