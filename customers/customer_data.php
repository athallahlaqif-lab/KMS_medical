<?php

declare(strict_types=1);

require_once '../config/database.php';

/*
|--------------------------------------------------------------------------
| Search Customer
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');

if ($search == '') {

    $stmt = $pdo->query("
        SELECT *
        FROM customers
        ORDER BY id DESC
    ");

} else {

    $stmt = $pdo->prepare("
        SELECT *
        FROM customers
        WHERE customer_code LIKE ?
           OR customer_name LIKE ?
           OR phone LIKE ?
           OR email LIKE ?
           OR city LIKE ?
        ORDER BY id DESC
    ");

    $like = "%{$search}%";

    $stmt->execute([
        $like,
        $like,
        $like,
        $like,
        $like
    ]);

}

$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);