<?php

declare(strict_types=1);

$pageTitle = 'Edit Customer';
$pageIcon  = 'bi-pencil-square';

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

/*
|--------------------------------------------------------------------------
| Ambil ID
|--------------------------------------------------------------------------
*/

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {

    header('Location: index.php');
    exit;

}

/*
|--------------------------------------------------------------------------
| Ambil Data Customer
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT *
FROM customers
WHERE id = ?
LIMIT 1
");

$stmt->execute([$id]);

$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {

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

                            Edit Customer

                        </h3>

                        <p class="text-muted">

                            Perbarui informasi customer.

                        </p>

                    </div>

                    <form action="update.php" method="POST">

                        <input
                            type="hidden"
                            name="id"
                            value="<?= $customer['id']; ?>">

                        <div class="mb-3">

                            <label class="form-label">

                                Customer Code

                            </label>

                            <input
                                type="text"
                                name="customer_code"
                                class="form-control"
                                value="<?= htmlspecialchars($customer['customer_code']); ?>"
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
                                value="<?= htmlspecialchars($customer['customer_name']); ?>"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Phone

                            </label>

                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                value="<?= htmlspecialchars($customer['phone']); ?>">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Email

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($customer['email']); ?>">

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                City

                            </label>

                            <input
                                type="text"
                                name="city"
                                class="form-control"
                                value="<?= htmlspecialchars($customer['city']); ?>">

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Address

                            </label>

                            <textarea
                                name="address"
                                rows="4"
                                class="form-control"><?= htmlspecialchars($customer['address']); ?></textarea>

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

                                Update Customer

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