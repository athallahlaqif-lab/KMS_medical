<?php

declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: index.php');
    exit;

}

$product_id = (int) ($_POST['product_id'] ?? 0);
$qty = (int) ($_POST['qty'] ?? 0);
$transaction_date = $_POST['transaction_date'] ?? date('Y-m-d');
$note = trim($_POST['note'] ?? '');

if ($product_id <= 0 || $qty <= 0) {

    header('Location: create.php');
    exit;

}

try {

    $pdo->beginTransaction();

    /*
    |--------------------------------------------------------------------------
    | Ambil stok produk
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        SELECT stock
        FROM products
        WHERE id = :id
        LIMIT 1
    ");

    $stmt->execute([

        ':id' => $product_id

    ]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {

        throw new Exception('Produk tidak ditemukan.');

    }

    $stock_before = (int) $product['stock'];
    $stock_after  = $stock_before + $qty;
        /*
    |--------------------------------------------------------------------------
    | Update stok produk
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        UPDATE products
        SET stock = :stock
        WHERE id = :id
    ");

    $stmt->execute([

        ':stock' => $stock_after,

        ':id'    => $product_id

    ]);

    /*
    |--------------------------------------------------------------------------
    | Simpan transaksi Stock In
    |--------------------------------------------------------------------------
    */

    $stmt = $pdo->prepare("
        INSERT INTO stock_in
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

        ':product_id'       => $product_id,

        ':qty'              => $qty,

        ':stock_before'     => $stock_before,

        ':stock_after'      => $stock_after,

        ':transaction_date' => $transaction_date,

        ':note'             => $note

    ]);

    /*
    |--------------------------------------------------------------------------
    | Commit
    |--------------------------------------------------------------------------
    */

    $pdo->commit();

    header('Location: index.php?success=1');

    exit;

} catch (Exception $e) {

    if ($pdo->inTransaction()) {

        $pdo->rollBack();

    }

    die('Terjadi kesalahan: ' . $e->getMessage());

}