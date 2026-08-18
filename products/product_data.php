<?php

declare(strict_types=1);

require_once '../config/database.php';

/*
|--------------------------------------------------------------------------
| Search Product
|--------------------------------------------------------------------------
*/

$search = trim($_GET['search'] ?? '');

if ($search == '') {

    $stmt = $pdo->query("
        SELECT
            p.*,
            c.category_name,
            s.supplier_name
      FROM products p
LEFT JOIN categories c
    ON p.category_id = c.id
LEFT JOIN suppliers s
    ON p.supplier_id = s.id
        ORDER BY p.id DESC
    ");

} else {

    $stmt = $pdo->prepare("
        SELECT
            p.*,
            c.category_name,        
            s.supplier_name
       FROM products p
LEFT JOIN categories c
    ON p.category_id = c.id
LEFT JOIN suppliers s
    ON p.supplier_id = s.id
    ON p.supplier_id = s.id 
        WHERE
            p.product_name LIKE ?
            OR p.product_code LIKE ?
            OR c.category_name LIKE ?
            OR s.supplier_name LIKE ?
        ORDER BY p.id DESC
    ");

    $like = "%{$search}%";

    $stmt->execute([
        $like,
        $like,
        $like,
        $like
    ]);

}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Dropdown Category
|--------------------------------------------------------------------------
*/

$categories = $pdo->query("
    SELECT id, category_name
    FROM categories
    ORDER BY category_name
")->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Dropdown Supplier
|--------------------------------------------------------------------------
*/

$suppliers = $pdo->query("
    SELECT id, supplier_name
    FROM suppliers
    ORDER BY supplier_name
")->fetchAll(PDO::FETCH_ASSOC);