<?php

declare(strict_types=1);

require_once '../config/database.php';

/*
|--------------------------------------------------------------------------
| Search Supplier
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');

if ($search == '') {

    $stmt = $pdo->query("
        SELECT *
        FROM suppliers
        ORDER BY id DESC
    ");

} else {

    $stmt = $pdo->prepare("
        SELECT *
        FROM suppliers
        WHERE supplier_name LIKE ?
           OR contact_person LIKE ?
           OR phone LIKE ?
           OR email LIKE ?
        ORDER BY id DESC
    ");

    $like = "%{$search}%";

    $stmt->execute([
        $like,
        $like,
        $like,
        $like
    ]);

}

$suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);