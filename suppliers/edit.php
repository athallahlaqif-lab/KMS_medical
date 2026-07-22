<?php

declare(strict_types=1);

$pageTitle = 'Edit Supplier';
$pageIcon  = 'bi-pencil-square';

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

/*
|--------------------------------------------------------------------------
| Ambil ID
|--------------------------------------------------------------------------
*/

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {

    header('Location: index.php');
    exit;

}

/*
|--------------------------------------------------------------------------
| Ambil Data Supplier
|--------------------------------------------------------------------------
*/

$sql = "
SELECT *
FROM suppliers
WHERE id = :id
LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':id' => $id

]);

$supplier = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$supplier) {

    header('Location: index.php');
    exit;

}

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

                            <i class="bi bi-pencil-square text-warning me-2"></i>

                            Edit Supplier

                        </h3>

                        <p class="text-muted">

                            Perbarui informasi supplier.

                        </p>

                    </div>

                    <form action="update.php" method="POST">

                        <input
                            type="hidden"
                            name="id"
                            value="<?= $supplier['id']; ?>">

                        <div class="mb-3">

                            <label class="form-label">

                                Supplier Name

                            </label>

                            <input
                                type="text"
                                name="supplier_name"
                                class="form-control"
                                value="<?= htmlspecialchars($supplier['supplier_name']); ?>"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Contact Person

                            </label>

                            <input
                                type="text"
                                name="contact_person"
                                class="form-control"
                                value="<?= htmlspecialchars($supplier['contact_person']); ?>">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Phone

                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                value="<?= htmlspecialchars($supplier['phone']); ?>">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Email

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($supplier['email']); ?>">

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Address

                            </label>

                            <textarea
                                name="address"
                                rows="4"
                                class="form-control"><?= htmlspecialchars($supplier['address']); ?></textarea>

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
                                class="btn btn-warning">

                                <i class="bi bi-save me-1"></i>

                                Update Supplier

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