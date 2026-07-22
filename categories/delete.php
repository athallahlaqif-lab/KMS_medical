<?php

declare(strict_types=1);

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
| Cek Data
|--------------------------------------------------------------------------
*/

$sql = "
SELECT id
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

/*
|--------------------------------------------------------------------------
| Hapus Data
|--------------------------------------------------------------------------
*/

$sql = "
DELETE FROM categories
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