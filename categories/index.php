<?php

declare(strict_types=1);

$pageTitle = 'Categories';
$pageIcon  = 'bi-tags-fill';

require_once '../config/session.php';
require_once '../config/database.php';
require_once 'category_data.php';

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
                                <i class="bi bi-tags-fill text-primary me-2"></i>Categories
                            </h2>
                            <p class="text-muted mb-0">
                                Kelola seluruh kategori produk yang tersedia pada sistem KMS Medical.
                            </p>
                        </div>
                        <a href="create.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-plus-circle me-2"></i>Add Category
                        </a>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow rounded-4">
                <div class="card-body p-4">

                    <!-- Search Form -->
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
                                    placeholder="Cari kategori..."
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
                                    <th class="text-center" style="width: 70px;">No</th>
                                    <th>Category Name</th>
                                    <th>Description</th>
                                    <th style="width: 180px;">Created At</th>
                                    <th class="text-center" style="width: 120px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($categories) && count($categories) > 0): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($categories as $category): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($category['category_name'] ?? ''); ?></strong>
                                            </td>
                                            <td>
                                                <?= nl2br(htmlspecialchars($category['description'] ?? '-')); ?>
                                            </td>
                                            <td>
                                                <?= !empty($category['created_at']) ? date('d M Y H:i', strtotime($category['created_at'])) : '-'; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a
                                                        href="edit.php?id=<?= $category['id']; ?>"
                                                        class="btn btn-warning btn-sm action-btn text-white"
                                                        title="Edit Kategori">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>
                                                    <button
                                                        type="button"
                                                        class="btn btn-danger btn-sm action-btn"
                                                        title="Hapus Kategori"
                                                        onclick="confirmDelete(<?= $category['id']; ?>)">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bi bi-tags fs-1 text-secondary"></i>
                                            <p class="text-muted mt-2 mb-0">Belum ada kategori.</p>
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
        title: 'Hapus Kategori?',
        text: 'Data kategori yang dihapus tidak dapat dikembalikan.',
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
    text: 'Kategori berhasil ditambahkan.',
    timer: 1800,
    showConfirmButton: false
});
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: 'Kategori berhasil diperbarui.',
    timer: 1800,
    showConfirmButton: false
});
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: 'Kategori berhasil dihapus.',
    timer: 1800,
    showConfirmButton: false
});
<?php endif; ?>
</script>

</body>
</html>