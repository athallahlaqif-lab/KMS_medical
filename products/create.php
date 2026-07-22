<?php

declare(strict_types=1);

$pageTitle = 'Add Product';
$pageIcon  = 'bi-plus-circle-fill';

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

          <div class="card border-0 shadow-lg rounded-4">

                <div class="card-body">

                    <h3 class="fw-bold text-primary mb-2">
                        <p class="text-muted mb-4">
    Lengkapi data produk yang akan ditambahkan ke sistem KMS Medical.
</p>

                        <i class="bi bi-capsule-pill text-primary me-2"></i>

                        Add Product

                    </h3>

                    <form action="store.php"
                          method="POST"
                          enctype="multipart/form-data">

                        <div class="row">

                            <div class="col-md-6">

                                <div class="mb-3">

                                   <label class="form-label fw-semibold">
    <i class="bi bi-upc-scan text-primary me-2"></i>
    Product Code
</label>
                                    <input
                                        type="text"
                                        name="product_code"
                                        class="form-control rounded-3 shadow-sm"
                                        required>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label fw-semibold">
    <i class="bi bi-capsule text-success me-2"></i>
    Product Name
</label>
                                    <input
                                        type="text"
                                        name="product_name"
                                        class="form-control rounded-3 shadow-sm"
                                        required>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="mb-3">

                                   <label class="form-label fw-semibold">
    <i class="bi bi-tags text-warning me-2"></i>
    Category
</label>
                                    <select
                                        name="category_id"
                                        class="form-select"
                                        required>

                                        <option value="">

                                            -- Choose Category --

                                        </option>

                                        <?php foreach($categories as $category): ?>

                                            <option value="<?= $category['id']; ?>">

                                                <?= htmlspecialchars($category['category_name']); ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label fw-semibold">
    <i class="bi bi-truck text-info me-2"></i>
    Supplier
</label>
                                    <select
                                        name="supplier_id"
                                        class="form-select"
                                        required>

                                        <option value="">

                                            -- Choose Supplier --

                                        </option>

                                        <?php foreach($suppliers as $supplier): ?>

                                            <option value="<?= $supplier['id']; ?>">

                                                <?= htmlspecialchars($supplier['supplier_name']); ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="mb-3">

                                   <label class="form-label fw-semibold">
    <i class="bi bi-box text-secondary me-2"></i>
    Unit
</label>
                                    <input
                                        type="text"
                                        name="unit"
                                        class="form-control rounded-3 shadow-sm"
                                        placeholder="Box / Strip / Botol"
                                        required>

                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="mb-3">

<label class="form-label fw-semibold">
    <i class="bi bi-cash-stack text-success me-2"></i>
    Purchase Price
</label>
                                    <input
                                        type="number"
                                        name="purchase_price"
                                        class="form-control rounded-3 shadow-sm"
                                        required>

                                </div>

                            </div>

                            <div class="col-md-4">

                                <div class="mb-3">

                                    <label class="form-label fw-semibold">
    <i class="bi bi-currency-dollar text-primary me-2"></i>
    Selling Price
</label>

                                    <input
                                        type="number"
                                        name="selling_price"
                                        class="form-control rounded-3 shadow-sm"
                                        required>

                                </div>

                            </div>
                                                        <div class="col-md-4">

                                <div class="mb-3">

                                    <label class="form-label fw-semibold">
    <i class="bi bi-box-seam text-danger me-2"></i>
    Stock
</label>
                                    <input
                                        type="number"
                                        name="stock"
                                        class="form-control rounded-3 shadow-sm"
                                        value="0"
                                        required>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label fw-semibold">

    <i class="bi bi-image me-2 text-primary"></i>

    Product Photo

</label>
                                    <input
    type="file"
    id="photo"
    name="photo"
    class="form-control rounded-3 shadow-sm"
    accept="image/*">
<div class="mt-3 text-center">

    <img
        id="preview"
        src="../assets/images/no-image.png"
        alt="Preview"
        style="
            width:180px;
            height:180px;
            object-fit:cover;
            border-radius:20px;
            border:2px dashed #dbe4f0;
            padding:8px;
            background:#fff;
        ">

</div>

                            </div>

                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Description

                                    </label>

                                    <textarea
                                        name="description"
                                        rows="4"
                                        class="form-control rounded-3 shadow-sm"></textarea>

                                </div>

                            </div>

                        </div>

                        <div class="d-flex justify-content-between mt-4">

                            <a
                                href="index.php"
                              class="btn btn-outline-secondary rounded-pill px-4">

                                <i class="bi bi-arrow-left-circle me-1"></i>

                                Back

                            </a>

                            <button
                                type="submit"
                               class="btn btn-primary rounded-pill px-4 shadow-sm">

                                <i class="bi bi-check-circle me-1"></i>

                                Save Product

                            </button>

                        </div>

                    </form>

                </div>

            </div>

            <?php include '../includes/footer.php'; ?>

        </div>

    </div>

</div>

<?php include '../includes/scripts.php'; ?>

<script>
    document.getElementById("photo").addEventListener("change", function(e){

    const file = e.target.files[0];

    if(file){

        document.getElementById("preview").src = URL.createObjectURL(file);

    }

});
document.getElementById("photo").addEventListener("change", function(e){

    const file = e.target.files[0];

    if(file){

        document.getElementById("preview").src =
            URL.createObjectURL(file);

    }

});
document.querySelector("form").addEventListener("submit", function(e){

    const code = document.querySelector("[name='product_code']").value.trim();
    const name = document.querySelector("[name='product_name']").value.trim();

    if(code === "" || name === ""){

        e.preventDefault();

        Swal.fire({

            icon:"warning",

            title:"Peringatan",

            text:"Product Code dan Product Name wajib diisi."

        });

    }

});

</script>

</body>

</html>