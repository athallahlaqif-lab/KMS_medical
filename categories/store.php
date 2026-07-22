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
| Ambil Data Form
|--------------------------------------------------------------------------
*/

$category_name = trim($_POST['category_name'] ?? '');
$description   = trim($_POST['description'] ?? '');

/*
|--------------------------------------------------------------------------
| Validasi
|--------------------------------------------------------------------------
*/

if ($category_name === '') {

    header('Location: create.php');
    exit;

}

/*
|--------------------------------------------------------------------------
| Cek Nama Kategori Sudah Ada
|--------------------------------------------------------------------------
*/

$sql = "
SELECT id
FROM categories
WHERE category_name = :category_name
LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':category_name' => $category_name

]);

if ($stmt->fetch()) {

    echo "

    <script>

        alert('Nama kategori sudah digunakan!');

        window.location='create.php';

    </script>

    ";

    exit;

}

/*
|--------------------------------------------------------------------------
| Simpan Data
|--------------------------------------------------------------------------
*/

$sql = "
INSERT INTO categories
(
    category_name,
    description
)
VALUES
(
    :category_name,
    :description
)
";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':category_name' => $category_name,

    ':description' => $description

]);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header('Location: index.php?success=1');
exit;