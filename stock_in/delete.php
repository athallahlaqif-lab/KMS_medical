<?php
declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        $pdo->beginTransaction();

        // 1. Ambil data transaksi stock in
        $stmt = $pdo->prepare("SELECT product_id, qty FROM stock_in WHERE id = ? FOR UPDATE");
        $stmt->execute([$id]);
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($transaction) {
            $productId = (int)$transaction['product_id'];
            $qty       = (int)$transaction['qty'];

            // 2. Kembalikan stok produk (kurangi stok karena transaksi stock in dibatalkan)
            $stmtUpdate = $pdo->prepare("UPDATE products SET stock = GREATEST(0, stock - ?) WHERE id = ?");
            $stmtUpdate->execute([$qty, $productId]);

            // 3. Hapus baris transaksi dari tabel stock_in
            $stmtDelete = $pdo->prepare("DELETE FROM stock_in WHERE id = ?");
            $stmtDelete->execute([$id]);
        }

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollBack();
    }
}

// Redirect kembali ke index
header("Location: index.php?success=1");
exit;