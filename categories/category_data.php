<?php

declare(strict_types=1);

require_once '../config/database.php';

/*
|--------------------------------------------------------------------------
| Search Category
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');

if ($search == '') {

    $stmt = $pdo->query("
        SELECT *
        FROM categories
        ORDER BY id DESC
    ");

} else {

    $stmt = $pdo->prepare("
        SELECT *
        FROM categories
        WHERE category_name LIKE ?
           OR description LIKE ?
        ORDER BY id DESC
    ");

    $like = "%{$search}%";

    $stmt->execute([
        $like,
        $like
    ]);

}

$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);