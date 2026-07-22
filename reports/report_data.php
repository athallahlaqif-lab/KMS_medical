<?php

declare(strict_types=1);

require_once '../config/database.php';

/*
|--------------------------------------------------------------------------
| Filter Tanggal
|--------------------------------------------------------------------------
*/

$start = $_GET['start'] ?? '';
$end   = $_GET['end'] ?? '';

$where = '';
$params = [];

if ($start !== '' && $end !== '') {

    $where = " WHERE created_at BETWEEN :start AND :end ";

    $params = [
        ':start' => $start . ' 00:00:00',
        ':end'   => $end . ' 23:59:59'
    ];
}

/*
|--------------------------------------------------------------------------
| Total Products
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
SELECT COUNT(*) total
FROM products
");

$totalProducts = (int)$stmt->fetchColumn();

/*
|--------------------------------------------------------------------------
| Total Stock In
|--------------------------------------------------------------------------
*/

$sql = "
SELECT COUNT(*) total
FROM stock_in
$where
";

$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$totalStockIn = (int)$stmt->fetchColumn();

/*
|--------------------------------------------------------------------------
| Total Stock Out
|--------------------------------------------------------------------------
*/

$sql = "
SELECT COUNT(*) total
FROM stock_out
$where
";

$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$totalStockOut = (int)$stmt->fetchColumn();