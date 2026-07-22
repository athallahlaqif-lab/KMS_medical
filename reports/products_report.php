<?php

declare(strict_types=1);

$pageTitle = 'Products Report';
$pageIcon  = 'bi bi-box-seam';

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

$search = trim($_GET['search'] ?? '');

if ($search == '') {

    $stmt = $pdo->query("
        SELECT
            p.id,
            p.product_code,
            p.product_name,
            c.category_name,
            s.supplier_name,
            p.stock
        FROM products p
        LEFT JOIN categories c
            ON p.category_id = c.id
        LEFT JOIN suppliers s
            ON p.supplier_id = s.id
        ORDER BY p.product_name ASC
    ");

} else {

    $stmt = $pdo->prepare("
        SELECT
            p.id,
            p.product_code,
            p.product_name,
            c.category_name,
            s.supplier_name,
            p.stock
        FROM products p
        LEFT JOIN categories c
            ON p.category_id = c.id
        LEFT JOIN suppliers s
            ON p.supplier_id = s.id
        WHERE
            p.product_name LIKE ?
            OR p.product_code LIKE ?
        ORDER BY p.product_name ASC
    ");

    $like = "%{$search}%";

    $stmt->execute([
        $like,
        $like
    ]);

}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';