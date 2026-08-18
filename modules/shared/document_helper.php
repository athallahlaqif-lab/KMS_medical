<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/company.php';

requireLogin();

/**
 * Mengambil data HEADER transaksi (info transaksi + customer saja,
 * TANPA produk). Selalu 1 baris per transaksi.
 */
function getTransactionHeader(PDO $pdo, int $transactionId)
{
    $sql = "
        SELECT
            t.*,

            c.customer_name,
            c.phone,
            c.address,
            c.email

        FROM transactions t

        LEFT JOIN customers c
            ON c.id = t.customer_id

        WHERE t.id = :id

        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $transactionId]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * Mengambil SEMUA item/produk dalam satu transaksi (bisa lebih dari 1
 * baris kalau transaksinya berisi banyak produk).
 */
function getTransactionItems(PDO $pdo, int $transactionId): array
{
    $sql = "
        SELECT
            td.quantity AS qty,
            td.subtotal,
            td.purchase_price,
            td.selling_price,

            p.product_code,
            p.product_name

        FROM transaction_details td

        INNER JOIN products p
            ON p.id = td.product_id

        WHERE td.transaction_id = :id

        ORDER BY td.id ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $transactionId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Menjumlahkan subtotal dari seluruh item dalam transaksi
 */
function sumSubtotal(array $items): float
{
    $total = 0.0;
    foreach ($items as $item) {
        $total += (float)$item['subtotal'];
    }
    return $total;
}
