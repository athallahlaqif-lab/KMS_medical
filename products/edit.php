<?php

declare(strict_types=1);

$pageTitle = 'Edit Product';
$pageIcon  = 'bi-pencil-square';

require_once '../config/session.php';
require_once '../config/database.php';
require_once 'product_data.php';

requireLogin();

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {

    header('Location: index.php');
    exit;

}

$sql = "
SELECT *
FROM products
WHERE id = :id
LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':id' => $id

]);

$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {

    header('Location:index.php');
    exit;

}

include '../includes/header.php';

?>

<div class="wrapper">

<?php include '../includes/sidebar.php'; ?>

<div class="main">

<?php include '../includes/navbar.php'; ?>

<div class="content">

<div class="card border-0 shadow rounded-4">

<div class="card-body p-4">

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-2">

            <i class="bi bi-pencil-square text-warning me-2"></i>

            Edit Product

        </h2>

        <p class="text-muted mb-0">

            Perbarui informasi produk pada sistem KMS Medical.

        </p>

    </div>

    <a href="index.php"
       class="btn btn-outline-secondary rounded-pill px-4">

        <i class="bi bi-arrow-left me-2"></i>

        Kembali

    </a>

</div>

<form
action="update.php"
method="POST"
enctype="multipart/form-data">

<input
type="hidden"
name="id"
value="<?= $product['id']; ?>">

<input
type="hidden"
name="old_photo"
value="<?= htmlspecialchars($product['photo']); ?>">

<div class="row">

<div class="col-md-6">

<div class="mb-3">

<label class="form-label">

Product Code

</label>

<input
type="text"
name="product_code"
class="form-control rounded-3 shadow-sm"
value="<?= htmlspecialchars($product['product_code']); ?>"
required>

</div>

</div>

<div class="col-md-6">

<div class="mb-3">

<label class="form-label">

Product Name

</label>

<input
type="text"
name="product_name"
class="form-control"
value="<?= htmlspecialchars($product['product_name']); ?>"
required>

</div>

</div>

<div class="col-md-6">

<div class="mb-3">

<label class="form-label">

Category

</label>

<select
name="category_id"
class="form-select rounded-3 shadow-sm"
required>

<?php foreach($categories as $category): ?>

<option
value="<?= $category['id']; ?>"
<?= $category['id']==$product['category_id']?'selected':''; ?>>

<?= htmlspecialchars($category['category_name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

</div>

<div class="col-md-6">

<div class="mb-3">

<label class="form-label">

Supplier

</label>

<select
name="supplier_id"
class="form-select"
required>

<?php foreach($suppliers as $supplier): ?>

<option
value="<?= $supplier['id']; ?>"
<?= $supplier['id']==$product['supplier_id']?'selected':''; ?>>

<?= htmlspecialchars($supplier['supplier_name']); ?>

</option>

<?php endforeach; ?>

</select>

</div>

</div>

<div class="col-md-4">

<div class="mb-3">

<label class="form-label">

Unit

</label>

<input
type="text"
name="unit"
class="form-control"
value="<?= htmlspecialchars($product['unit']); ?>">

</div>

</div>

<div class="col-md-4">

<div class="mb-3">

<label class="form-label">

Purchase Price

</label>

<input
type="number"
name="purchase_price"
class="form-control"
value="<?= $product['purchase_price']; ?>">

</div>

</div>

<div class="col-md-4">

<div class="mb-3">

<label class="form-label">

Selling Price

</label>

<input
type="number"
name="selling_price"
class="form-control"
value="<?= $product['selling_price']; ?>">

</div>

</div>
                            <div class="col-md-4">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Stock

                                    </label>

                                    <input
                                        type="number"
                                        name="stock"
                                        class="form-control"
                                        value="<?= $product['stock']; ?>">

                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Product Photo

                                    </label>

                                    <input
    type="file"
    id="photo"
    name="photo"
                                        accept="image/*">

                                </div>

                               <?php if(!empty($product['photo'])): ?>

<div class="mt-3 text-center">

    <img
        id="preview"
        src="upload/<?= htmlspecialchars($product['photo']); ?>"
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

<?php else: ?>

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

<?php endif; ?>
                            </div>

                            <div class="col-md-6">

                                <div class="mb-3">

                                    <label class="form-label">

                                        Description

                                    </label>

                                    <textarea
                                        name="description"
                                        rows="5"
                                       class="form-control rounded-3 shadow-sm"><?= htmlspecialchars($product['description']); ?></textarea>
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
                              class="btn btn-warning rounded-pill px-4 shadow-sm">  

                                <i class="bi bi-save me-1"></i>

                                Update Product

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

document.querySelector("form").addEventListener("submit", function(e){

    const code = document.querySelector("[name='product_code']").value.trim();

    const name = document.querySelector("[name='product_name']").value.trim();

    ...

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

</scrip>

</body>

</html>