<?php

declare(strict_types=1);

$pageTitle = 'Customers';
$pageIcon  = 'bi-people';

require_once '../config/session.php';
require_once '../config/database.php';
require_once 'customer_data.php';

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
                                <i class="bi bi-people text-primary me-2"></i>Customers
                            </h2>
                            <p class="text-muted mb-0">
                                Kelola seluruh data customer yang tersedia pada sistem KMS Medical.
                            </p>
                        </div>

                        <a href="create.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
                            <i class="bi bi-plus-circle me-2"></i>Add Customer
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
                                    placeholder="Cari customer..."
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

                                    <th class="text-center" style="width:60px;">No</th>
                                    <th>Code</th>
                                    <th>Customer</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>City</th>
                                    <th class="text-center" style="width:120px;">Action</th>

                                </tr>

                            </thead>

                            <tbody>

                            <?php if(count($customers)>0): ?>

                                <?php $no=1; ?>

                                <?php foreach($customers as $customer): ?>

                                <tr>

                                    <td class="text-center"><?= $no++; ?></td>

                                    <td><?= htmlspecialchars($customer['customer_code']); ?></td>

                                    <td>
                                        <strong><?= htmlspecialchars($customer['customer_name']); ?></strong>
                                    </td>

                                    <td><?= htmlspecialchars($customer['phone'] ?: '-'); ?></td>

                                    <td><?= htmlspecialchars($customer['email'] ?: '-'); ?></td>

                                    <td><?= htmlspecialchars($customer['city'] ?: '-'); ?></td>

                                    <td>

                                        <div class="d-flex justify-content-center gap-2">

                                            <a
                                                href="edit.php?id=<?= $customer['id']; ?>"
                                                class="btn btn-warning btn-sm text-white">

                                                <i class="bi bi-pencil-square"></i>

                                            </a>

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm"
                                                onclick="confirmDelete(<?= $customer['id']; ?>)">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </div>

                                    </td>

                                </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>

                                    <td colspan="7" class="text-center py-5">

                                        <i class="bi bi-people fs-1 text-secondary"></i>

                                        <p class="text-muted mt-2 mb-0">
                                            Belum ada data customer.
                                        </p>

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

function confirmDelete(id){

    Swal.fire({

        title:'Hapus Customer?',

        text:'Data customer yang dihapus tidak dapat dikembalikan.',

        icon:'warning',

        showCancelButton:true,

        confirmButtonColor:'#dc3545',

        cancelButtonColor:'#6c757d',

        confirmButtonText:'Ya, Hapus',

        cancelButtonText:'Batal',

        reverseButtons:true

    }).then((result)=>{

        if(result.isConfirmed){

            window.location='delete.php?id='+id;

        }

    });

}

<?php if(isset($_GET['success'])): ?>

Swal.fire({
    icon:'success',
    title:'Berhasil',
    text:'Customer berhasil ditambahkan.',
    timer:1800,
    showConfirmButton:false
});

<?php endif; ?>

<?php if(isset($_GET['updated'])): ?>

Swal.fire({
    icon:'success',
    title:'Berhasil',
    text:'Customer berhasil diperbarui.',
    timer:1800,
    showConfirmButton:false
});

<?php endif; ?>

<?php if(isset($_GET['deleted'])): ?>

Swal.fire({
    icon:'success',
    title:'Berhasil',
    text:'Customer berhasil dihapus.',
    timer:1800,
    showConfirmButton:false
});

<?php endif; ?>

</script>

</body>
</html>