<?php

declare(strict_types=1);

$pageTitle = 'Add Category';
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

                            <i class="bi bi-plus-circle-fill text-primary me-2"></i>

                            Add New Category

                        </h3>

                        <p class="text-muted">

                            Tambahkan kategori baru untuk produk medis.

                        </p>

                    </div>

                    <form action="store.php" method="POST">

                        <div class="mb-3">

                            <label class="form-label">

                                Category Name

                            </label>

                            <input
                                type="text"
                                name="category_name"
                                class="form-control"
                                placeholder="Contoh : Antibiotik"
                                required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Description

                            </label>

                            <textarea
                                name="description"
                                rows="5"
                                class="form-control"
                                placeholder="Masukkan deskripsi kategori..."></textarea>

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

                                Save Category

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

    const category = document.querySelector("[name='category_name']").value.trim();

    if(category === ""){

        e.preventDefault();

        Swal.fire({

            icon: "warning",

            title: "Peringatan",

            text: "Nama kategori wajib diisi."

        });

    }

});

</script>

</body>

</html>