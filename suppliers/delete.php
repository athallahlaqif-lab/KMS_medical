<?php
declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        $pdo->beginTransaction();

        // Putus hubungan supplier_id pada produk
        $stmt1 = $pdo->prepare("UPDATE products SET supplier_id = NULL WHERE supplier_id = ?");
        $stmt1->execute([$id]);

        // Hapus supplier
        $stmt2 = $pdo->prepare("DELETE FROM suppliers WHERE id = ?");
        $stmt2->execute([$id]);

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