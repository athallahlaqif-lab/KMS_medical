<?php
declare(strict_types=1);

$pageTitle = 'Add User';
$pageIcon  = 'bi-person-plus-fill';

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'Staff';

    if (empty($fullname) || empty($username) || empty($password)) {
        $error = 'Semua kolom wajib diisi!';
    } else {
        // Cek username duplikat
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = 'Username sudah digunakan!';
        } else {
            // Hash password & simpan
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (fullname, username, password, role, created_at) VALUES (?, ?, ?, ?, NOW())");
            
            if ($stmt->execute([$fullname, $username, $hashedPassword, $role])) {
                logActivity($pdo, 'CREATE', 'Menambahkan user baru: ' . $fullname);
                header('Location: index.php?success=1');
                exit;
            } else {
                $error = 'Gagal menyimpan data user.';
            }
        }
    }
}

include '../includes/header.php';
?>

<div class="wrapper">
    <?php include '../includes/sidebar.php'; ?>
    <div class="main">
        <?php include '../includes/navbar.php'; ?>

        <div class="content">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="fw-bold mb-0">
                            <i class="bi bi-person-plus-fill text-primary me-2"></i>Tambah User Baru
                        </h3>
                        <a href="index.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                    <?php if ($error): ?>
                        <div class="alert alert-danger rounded-3"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <input type="text" name="fullname" class="form-control" required placeholder="Masukkan nama lengkap">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <input type="text" name="username" class="form-control" required placeholder="Masukkan username">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Masukkan password">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Role</label>
                            <select name="role" class="form-select">
                                <option value="Staff">Staff</option>
                                <option value="Administrator">Administrator</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> Simpan User
                        </button>
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