<?php

declare(strict_types=1);

$pageTitle = 'Add Customer';
$pageIcon  = 'bi-person-plus-fill';

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

                            <i class="bi bi-people text-primary me-2"></i>

                            Add New Customer

                        </h3>

                        <p class="text-muted">

                            Tambahkan customer baru.

                        </p>

                    </div>

                    <form action="store.php" method="POST">

                        <div class="mb-3">

                            <label class="form-label">

                                Customer Code

                            </label>

                            <input
                                type="text"
                                name="customer_code"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Customer Name

                            </label>

                            <input
                                type="text"
                                name="customer_name"
                                class="form-control"
                                required>

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

                        <div class="mb-3">

                            <label class="form-label">

                                City

                            </label>

                            <input
                                type="text"
                                name="city"
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

                                Save Customer

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

        <?php include '../includes/footer.php'; ?>

    </div>

</div>

<?php include '../includes/scripts.php'; ?>

<script>

document.querySelector("form").addEventListener("submit", function(e){

    const code = document.querySelector("[name='customer_code']").value.trim();
    const name = document.querySelector("[name='customer_name']").value.trim();

    if(code === "" || name === ""){

        e.preventDefault();

        Swal.fire({

            icon: "warning",

            title: "Peringatan",

            text: "Customer Code dan Customer Name wajib diisi."

        });

    }

});

</script>

</body>

</html>