<?php

declare(strict_types=1);

$pageTitle = 'Add Supplier';
$pageIcon  = 'bi-plus-circle-fill';

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

include '../includes/header.php';

?>

<div class="wrapper">

    <?php include '../includes/sidebar.php'; ?>

    <div class="main">

        <?php include '../includes/navbar.php'; ?>

        <div class="content">

            <div class="card shadow border-0">

                <div class="card-body">

                    <div class="mb-4">

                        <h3 class="fw-bold">

                            <i class="bi bi-truck text-primary me-2"></i>

                            Add New Supplier

                        </h3>

                        <p class="text-muted">

                            Tambahkan supplier baru.

                        </p>

                    </div>

                    <form action="store.php" method="POST">

                        <div class="mb-3">

                            <label class="form-label">

                                Supplier Name

                            </label>

                            <input
                                type="text"
                                name="supplier_name"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Contact Person

                            </label>

                            <input
                                type="text"
                                name="contact_person"
                                class="form-control">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Phone

                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Email

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control">

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Address

                            </label>

                            <textarea
                                name="address"
                                rows="4"
                                class="form-control"></textarea>

                        </div>

                        <div class="d-flex justify-content-between">

                            <a
                                href="index.php"
                                class="btn btn-secondary">

                                <i class="bi bi-arrow-left-circle me-1"></i>

                                Back

                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary">

                                <i class="bi bi-check-circle me-1"></i>

                                Save Supplier

                            </button>

                        </div>

                    </form>

                </div>

            </div>
                            </div>

            </div>

            <?php include '../includes/footer.php'; ?>

        </div>

    </div>

</div>

<?php include '../includes/scripts.php'; ?>

<script>

document.querySelector("form").addEventListener("submit", function(e){

    const supplier = document.querySelector("[name='supplier_name']").value.trim();

    if(supplier === ""){

        e.preventDefault();

        Swal.fire({

            icon: "warning",

            title: "Peringatan",

            text: "Nama supplier wajib diisi."

        });

    }

});

</script>

</body>

</html>