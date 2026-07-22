<?php

declare(strict_types=1);

$pageTitle = 'Edit User';
$pageIcon  = 'bi-pencil-square';

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$sql = "SELECT * FROM users WHERE id = :id LIMIT 1";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $id
]);

$user = $stmt->fetch();

if (!$user) {
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

            <div class="card">

                <div class="card-body">

                    <h3 class="fw-bold mb-4">

                        <i class="bi bi-pencil-square me-2"></i>

                        Edit User

                    </h3>

                    <form action="update.php" method="POST">

                        <input
                            type="hidden"
                            name="id"
                            value="<?= $user['id']; ?>">

                        <div class="mb-3">

                            <label class="form-label">Full Name</label>

                            <input
                                type="text"
                                name="fullname"
                                class="form-control"
                                value="<?= htmlspecialchars($user['fullname']); ?>"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Username</label>

                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                value="<?= htmlspecialchars($user['username']); ?>"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">

                                Password
                                <small class="text-muted">(Kosongkan jika tidak ingin diubah)</small>

                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control">

                        </div>

                        <div class="mb-4">

                            <label class="form-label">Role</label>

                            <select
                                name="role"
                                class="form-select">

                                <option
                                    value="Administrator"
                                    <?= $user['role'] === 'Administrator' ? 'selected' : ''; ?>>

                                    Administrator

                                </option>

                                <option
                                    value="Staff"
                                    <?= $user['role'] === 'Staff' ? 'selected' : ''; ?>>

                                    Staff

                                </option>

                            </select>

                        </div>

                        <button
                            class="btn btn-primary">

                            <i class="bi bi-check-circle me-1"></i>

                            Update User

                        </button>

                        <a
                            href="index.php"
                            class="btn btn-secondary">

                            Back

                        </a>

                    </form>

                </div>

            </div>

            <?php include '../includes/footer.php'; ?>

        </div>

    </div>

</div>

<?php include '../includes/scripts.php'; ?>

</body>
</html>