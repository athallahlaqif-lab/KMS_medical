<?php

declare(strict_types=1);

$pageTitle = 'Edit Category';
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
| Ambil Data Category
|--------------------------------------------------------------------------
*/

$sql = "
SELECT *
FROM categories
WHERE id = :id
LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':id' => $id

]);

$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {

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

                            Edit Category

                        </h3>

                        <p class="text-muted">

                            Ubah informasi kategori produk.

                        </p>

                    </div>

                    <form action="update.php" method="POST">

                        <input
                            type="hidden"
                            name="id"
                            value="<?= $category['id']; ?>">

                        <div class="mb-3">

                            <label class="form-label">

                                Category Name

                            </label>

                            <input
                                type="text"
                                name="category_name"
                                class="form-control"
                                value="<?= htmlspecialchars($category['category_name']); ?>"
                                required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">

                                Description

                            </label>

                            <textarea
                                name="description"
                                rows="5"
                                class="form-control"><?= htmlspecialchars($category['description']); ?></textarea>

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

                                Update Category

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