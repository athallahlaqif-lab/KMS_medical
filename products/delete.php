<?php
declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        $pdo->beginTransaction();

        // 1. Hapus riwayat transaksi terkait produk ini dulu
        $stmt1 = $pdo->prepare("DELETE FROM stock_in WHERE product_id = ?");
        $stmt1->execute([$id]);

        $stmt2 = $pdo->prepare("DELETE FROM stock_out WHERE product_id = ?");
        $stmt2->execute([$id]);

        // 2. Hapus produk
        $stmt3 = $pdo->prepare("DELETE FROM products WHERE id = ?");
        $stmt3->execute([$id]);

        $pdo->commit();
        header("Location: index.php?deleted=1");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        header("Location: index.php?error=1");
        exit;
    }
}

header("Location: index.php");
exit;