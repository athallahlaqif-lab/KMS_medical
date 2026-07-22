<?php
declare(strict_types=1);

$search    = isset($_GET['search']) ? trim($_GET['search']) : '';
$startDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$endDate   = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';

$where = [];
$params = [];

if ($search !== '') {
    $where[] = "si.note LIKE ?";
    $params[] = "%{$search}%";
}

if ($startDate !== '') {
    $where[] = "si.transaction_date >= ?";
    $params[] = $startDate;
}

if ($endDate !== '') {
    $where[] = "si.transaction_date <= ?";
    $params[] = $endDate;
}

$sql = "
    SELECT 
        si.*, 
        p.*
    FROM stock_in si
    LEFT JOIN products p ON si.product_id = p.id
";

if (count($where) > 0) {
    $sql .= " WHERE " . implode(' AND ', $where);
}

$sql .= " ORDER BY si.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$stockIn = $stmt->fetchAll(PDO::FETCH_ASSOC);