<?php

declare(strict_types=1);

require_once '../config/database.php';

function getTotal(PDO $pdo, string $table): int
{
    $stmt = $pdo->query("SELECT COUNT(*) FROM {$table}");
    return (int)$stmt->fetchColumn();
}

$totalUsers = getTotal($pdo, 'users');
$totalCategories = getTotal($pdo, 'categories');
$totalSuppliers = getTotal($pdo, 'suppliers');
$totalProducts = getTotal($pdo, 'products');
// Produk stok menipis
$stmt = $pdo->query("
SELECT product_name, stock
FROM products
WHERE stock <= 20
ORDER BY stock ASC
LIMIT 5
");

$lowStocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
// 5 transaksi terbaru
$stmt = $pdo->query("
SELECT
    'IN' AS type,
    p.product_name,
    si.qty,
    si.transaction_date
FROM stock_in si
JOIN products p ON p.id = si.product_id

UNION ALL

SELECT
    'OUT' AS type,
    p.product_name,
    so.qty,
    so.transaction_date
FROM stock_out so
JOIN products p ON p.id = so.product_id

ORDER BY transaction_date DESC
LIMIT 5
");

$recentTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);