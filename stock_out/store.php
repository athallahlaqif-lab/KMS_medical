<?php

declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$product_id = (int)($_POST['product_id'] ?? 0);
$qty = (int)($_POST['qty'] ?? 0);
$transaction_date = $_POST['transaction_date'] ?? date('Y-m-d');
$note = trim($_POST['note'] ?? '');

if ($product_id <= 0 || $qty <= 0) {

    $_SESSION['error'] = 'Data tidak valid.';

    header('Location: create.php');

    exit;
}

try {

    $pdo->beginTransaction();

    /*
    |--------------------------------------------------------------------------
    | Ambil Data Produk
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        SELECT
            id,
            product_name,
            stock
        FROM products
        WHERE id = ?
        FOR UPDATE
    ");

    $stmt->execute([$product_id]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {

        throw new Exception('Produk tidak ditemukan.');

    }

    $stock_before = (int)$product['stock'];

    if ($qty > $stock_before) {

        throw new Exception('Stock tidak mencukupi.');

    }

    $stock_after = $stock_before - $qty;
        /*
    |--------------------------------------------------------------------------
    | Update Stock Produk
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        UPDATE products
        SET stock = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $stock_after,
        $product_id
    ]);

    /*
    |--------------------------------------------------------------------------
    | Simpan Riwayat Stock Out
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        INSERT INTO stock_out
        (
            product_id,
            qty,
            stock_before,
            stock_after,
            transaction_date,
            note
        )
        VALUES
        (
            :product_id,
            :qty,
            :stock_before,
            :stock_after,
            :transaction_date,
            :note
        )
    ");

    $stmt->execute([

        'product_id'       => $product_id,

        'qty'              => $qty,

        'stock_before'     => $stock_before,

        'stock_after'      => $stock_after,

        'transaction_date' => $transaction_date,

        'note'             => $note

    ]);

    /*
    |--------------------------------------------------------------------------
    | Commit
    |--------------------------------------------------------------------------
    */

    $pdo->commit();

    $_SESSION['success'] = 'Stock berhasil dikurangi.';

    header('Location: index.php');

    exit;

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {

        $pdo->rollBack();

    }

    $_SESSION['error'] = $e->getMessage();

    header('Location: create.php');

    exit;

}