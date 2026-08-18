<?php

require_once __DIR__ . '/../shared/document_helper.php';

$transactionId = (int)($_GET['id'] ?? 0);

if ($transactionId <= 0) {
    die('ID transaksi tidak ditemukan.');
}

$data = getTransactionHeader($pdo, $transactionId);

if (!$data) {
    die('Data invoice tidak ditemukan.');
}

$items = getTransactionItems($pdo, $transactionId);

if (count($items) === 0) {
    die('Data produk untuk invoice ini tidak ditemukan.');
}

$customer_name  = $data['customer_name'] ?? '-';
$address        = $data['address'] ?? '-';
$phone          = $data['phone'] ?? '-';
$email          = $data['email'] ?? '-';

$invoice_number = $data['invoice_number'];
$invoice_date   = date('d F Y', strtotime($data['transaction_date']));
$payment_method = $data['payment_method'] ?? 'Transfer';
$signature_path = $data['signature_path'] ?? null;

// Total dihitung dari SELURUH item (bisa lebih dari 1 produk)
$subtotal    = sumSubtotal($items);
$ppn         = $subtotal * 0.11;
$grand_total = $subtotal + $ppn;

include 'invoice_template.php';
