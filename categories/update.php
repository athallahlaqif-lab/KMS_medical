<?php

declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

/*
|--------------------------------------------------------------------------
| Hanya menerima metode POST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: index.php');
    exit;

}

/*
|--------------------------------------------------------------------------
| Ambil Data
|--------------------------------------------------------------------------
*/

$id = (int) ($_POST['id'] ?? 0);
$category_name = trim($_POST['category_name'] ?? '');
$description = trim($_POST['description'] ?? '');

/*
|--------------------------------------------------------------------------
| Validasi
|--------------------------------------------------------------------------
*/

if (
    $id <= 0 ||
    $category_name === ''
) {

    header('Location: index.php');
    exit;

}

/*
|--------------------------------------------------------------------------
| Cek Nama Kategori Dipakai Kategori Lain
|--------------------------------------------------------------------------
*/

$sql = "
SELECT id
FROM categories
WHERE category_name = :category_name
AND id <> :id
LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':category_name' => $category_name,
    ':id' => $id
]);

if ($stmt->fetch()) {

    echo "

    <script>

        alert('Nama kategori sudah digunakan!');

        window.history.back();

    </script>

    ";

    exit;

}

/*
|--------------------------------------------------------------------------
| Update Data
|--------------------------------------------------------------------------
*/

$sql = "
UPDATE categories
SET
    category_name = :category_name,
    description = :description
WHERE id = :id
";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':category_name' => $category_name,
    ':description' => $description,
    ':id' => $id

]);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header('Location: index.php?updated=1');
exit;