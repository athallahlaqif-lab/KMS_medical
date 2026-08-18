<?php
declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: create.php');
    exit;
}

$customerId       = (int)($_POST['customer_id'] ?? 0);
$productIds       = $_POST['product_id'] ?? [];
$qtys             = $_POST['qty'] ?? [];
$transactionDate  = $_POST['transaction_date'] ?? date('Y-m-d');
$note             = trim($_POST['note'] ?? '');
$paymentMethod    = $_POST['payment_method'] ?? 'Transfer';

$allowedPaymentMethods = ['COD', 'CBD', 'Transfer'];
if (!in_array($paymentMethod, $allowedPaymentMethods, true)) {
    $paymentMethod = 'Transfer';
}

// Susun ulang jadi array item yang rapi: [['product_id'=>x, 'qty'=>y], ...]
$items = [];
foreach ($productIds as $i => $pid) {
    $pid = (int)$pid;
    $qty = (int)($qtys[$i] ?? 0);

    if ($pid > 0 && $qty > 0) {
        $items[] = ['product_id' => $pid, 'qty' => $qty];
    }
}

if ($customerId <= 0 || count($items) === 0) {
    $_SESSION['error'] = 'Data tidak valid. Pastikan customer dan minimal 1 produk terisi.';
    header('Location: create.php');
    exit;
}

try {

    $pdo->beginTransaction();

    $totalSubtotal = 0.0;
    $lineItems = []; // dipakai untuk insert transaction_details setelah transaction_id ada

    foreach ($items as $item) {

        $productId = $item['product_id'];
        $qty       = $item['qty'];

        // Ambil produk (lock baris supaya aman dari race condition)
        $stmt = $pdo->prepare("
            SELECT *
            FROM products
            WHERE id = ?
            FOR UPDATE
        ");
        $stmt->execute([$productId]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$product) {
            throw new Exception('Produk dengan ID ' . $productId . ' tidak ditemukan.');
        }

        $stockBefore = (int)$product['stock'];

        if ($qty > $stockBefore) {
            throw new Exception('Stock tidak mencukupi untuk produk: ' . $product['product_name']);
        }

        $stockAfter = $stockBefore - $qty;

        $purchasePrice = (float)$product['purchase_price'];
        $sellingPrice  = (float)$product['selling_price'];
        $subtotal      = $sellingPrice * $qty;

        // Simpan riwayat Stock Out (1 baris per produk)
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
                ?,?,?,?,?,?
            )
        ");
        $stmt->execute([
            $productId,
            $qty,
            $stockBefore,
            $stockAfter,
            $transactionDate,
            $note
        ]);

        // Update stock produk
        $stmt = $pdo->prepare("
            UPDATE products
            SET stock = ?
            WHERE id = ?
        ");
        $stmt->execute([$stockAfter, $productId]);

        $totalSubtotal += $subtotal;

        $lineItems[] = [
            'product_id'     => $productId,
            'purchase_price' => $purchasePrice,
            'selling_price'  => $sellingPrice,
            'qty'            => $qty,
            'subtotal'       => $subtotal,
        ];
    }

    $ppn        = $totalSubtotal * 0.11;
    $grandTotal = $totalSubtotal + $ppn;

    // Generate Invoice Number
    $invoiceNumber = 'INV-' . date('YmdHis');

    // Simpan Transaction (1 baris untuk seluruh transaksi, walau isinya banyak produk)
    $stmt = $pdo->prepare("
        INSERT INTO transactions
        (
            customer_id,
            invoice_number,
            total_amount,
            pay_amount,
            change_amount,
            ppn_amount,
            grand_total,
            payment_method,
            source,
            transaction_date
        )
        VALUES
        (
            ?,?,?,?,?,?,?,?,?,?
        )
    ");

    $stmt->execute([
        $customerId,
        $invoiceNumber,
        $totalSubtotal,
        $grandTotal,
        0,
        $ppn,
        $grandTotal,
        $paymentMethod,
        'offline',
        date('Y-m-d H:i:s')
    ]);

    $transactionId = $pdo->lastInsertId();

    // Simpan detail per produk (transaction_details) - 1 baris per produk
    $stmt = $pdo->prepare("
        INSERT INTO transaction_details
        (
            transaction_id,
            product_id,
            purchase_price,
            selling_price,
            quantity,
            subtotal
        )
        VALUES
        (
            ?,?,?,?,?,?
        )
    ");

    foreach ($lineItems as $li) {
        $stmt->execute([
            $transactionId,
            $li['product_id'],
            $li['purchase_price'],
            $li['selling_price'],
            $li['qty'],
            $li['subtotal']
        ]);
    }

    $pdo->commit();

    header("Location: ../modules/transaction_success/index.php?id=" . $transactionId);
    exit;

} catch (Throwable $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['error'] = $e->getMessage();
    header('Location: create.php');
    exit;
}
