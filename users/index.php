<?php

declare(strict_types=1);

$pageTitle = 'Users';
$pageIcon  = 'bi-people-fill';

require_once '../config/session.php';
require_once '../config/database.php';
require_once 'user_data.php';

requireLogin();

include '../includes/header.php';
?>

<div class="wrapper">

    <?php include '../includes/sidebar.php'; ?>

    <div class="main">

        <?php include '../includes/navbar.php'; ?>

        <div class="content p-4">

            <div class="card shadow-sm border-0 rounded-4">

                <div class="card-body p-4">

                    <!-- Header & Tombol Add User -->
                    <!-- Header & Tombol Add User -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4" style="position: relative; z-index: 1050;">
                        <div>
                            <h3 class="fw-bold mb-1">
                                <i class="bi bi-people-fill text-primary me-2"></i>User Management
                            </h3>
                            <p class="text-muted mb-0">
                                Kelola seluruh akun pengguna sistem.
                            </p>
                        </div>

                        <div style="position: relative; z-index: 1051;">
                            <a href="create.php" class="btn btn-primary px-3 py-2 rounded-3 shadow-sm" style="pointer-events: auto !important; position: relative; z-index: 1052;">
                                <i class="bi bi-person-plus-fill me-2"></i>Add User
                            </a>
                        </div>
                    </div>
                    <hr class="text-muted opacity-25 mb-4">

                    <!-- Form Search -->
                    <form method="GET" class="mb-4">
                        <div class="row g-2">
                            <div class="col-md-5 col-lg-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input
                                        type="text"
                                        name="search"
                                        class="form-control border-start-0 ps-0"
                                        placeholder="Cari nama, username, role..."
                                        value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="col-md-4 col-lg-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Search
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Table Data -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="60" class="text-center">No</th>
                                    <th>Full Name</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Created At</th>
                                    <th width="120" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($users) > 0): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td class="text-center fw-semibold"><?= $no++; ?></td>
                                        <td>
                                            <strong class="text-dark"><?= htmlspecialchars($user['fullname']); ?></strong>
                                        </td>
                                        <td><?= htmlspecialchars($user['username']); ?></td>
                                        <td>
                                            <?php if ($user['role'] == "Administrator"): ?>
                                                <span class="badge bg-danger rounded-pill px-3 py-2">
                                                    Administrator
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-success rounded-pill px-3 py-2">
                                                    Staff
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-muted small">
                                            <?= date('d M Y H:i', strtotime($user['created_at'])); ?>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="edit.php?id=<?= $user['id']; ?>" class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button class="btn btn-outline-danger" onclick="confirmDelete(<?= $user['id']; ?>)" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                            Tidak ada data user.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

            <?php include '../includes/footer.php'; ?>

        </div>

    </div>

</div>

<?php include '../includes/scripts.php'; ?>

<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Hapus User?',
        text: 'Data yang dihapus tidak dapat dikembalikan.',
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
    text: 'Data berhasil disimpan.',
    timer: 1800,
    showConfirmButton: false
});
<?php endif; ?>

<?php if (isset($_GET['updated'])): ?>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: 'Data berhasil diperbarui.',
    timer: 1800,
    showConfirmButton: false
});
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: 'Data berhasil dihapus.',
    timer: 1800,
    showConfirmButton: false
});
<?php endif; ?>
</script>

</body>
</html>