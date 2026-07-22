<?php

declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

/*
|--------------------------------------------------------------------------
| Ambil ID User
|--------------------------------------------------------------------------
*/

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Cegah Menghapus Akun Sendiri
|--------------------------------------------------------------------------
*/

if ($id === (int) $_SESSION['user_id']) {

    echo "
    <script>
        alert('Anda tidak dapat menghapus akun yang sedang digunakan.');
        window.location='index.php';
    </script>
    ";

    exit;
}

/*
|--------------------------------------------------------------------------
| Cek Apakah User Ada
|--------------------------------------------------------------------------
*/

$sql = "
SELECT id
FROM users
WHERE id = :id
LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':id' => $id
]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: index.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Hapus User
|--------------------------------------------------------------------------
*/

$sql = "
DELETE FROM users
WHERE id = :id
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':id' => $id
]);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header('Location: index.php?deleted=1');
exit;