<?php
declare(strict_types=1);

$search    = isset($_GET['search']) ? trim($_GET['search']) : '';
$startDate = isset($_GET['start_date']) ? trim($_GET['start_date']) : '';
$endDate   = isset($_GET['end_date']) ? trim($_GET['end_date']) : '';

$where = [];
$params = [];

if ($search !== '') {
    $where[] = "(p.product_name LIKE ? OR p.product_code LIKE ? OR so.note LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

if ($startDate !== '') {
    $where[] = "so.transaction_date >= ?";
    $params[] = $startDate;
}

if ($endDate !== '') {
    $where[] = "so.transaction_date <= ?";
    $params[] = $endDate;
}

$sql = "
    SELECT 
        so.*, 
        p.product_code,
        p.product_name
    FROM stock_out so
    LEFT JOIN products p ON so.product_id = p.id
";

if (count($where) > 0) {
    $sql .= " WHERE " . implode(' AND ', $where);
}

$sql .= " ORDER BY so.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$stockOut = $stmt->fetchAll(PDO::FETCH_ASSOC);