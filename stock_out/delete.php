<?php
declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        $pdo->beginTransaction();

        // 1. Ambil data stock_out dulu untuk tahu product_id dan qty-nya
        $stmt = $pdo->prepare("SELECT product_id, qty FROM stock_out WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // 2. Kembalikan/Tambah stok di tabel products
            $stmtUpdate = $pdo->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
            $stmtUpdate->execute([$row['qty'], $row['product_id']]);

            // 3. Hapus data transaksi stock_out
            $stmtDelete = $pdo->prepare("DELETE FROM stock_out WHERE id = ?");
            $stmtDelete->execute([$id]);
        }

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