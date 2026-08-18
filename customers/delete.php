<?php

declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {

    try {

        // Ambil nama customer untuk activity log
        $stmt = $pdo->prepare("
            SELECT customer_name
            FROM customers
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        $customer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$customer) {
            header("Location: index.php");
            exit;
        }

        // Hapus customer
        $stmt = $pdo->prepare("
            DELETE FROM customers
            WHERE id = ?
        ");

        $stmt->execute([$id]);

        // Activity Log
        logActivity(
            $pdo,
            'DELETE CUSTOMER',
            'Menghapus customer: ' . $customer['customer_name']
        );

        header("Location: index.php?deleted=1");
        exit;

    } catch (Throwable $e) {

        header("Location: index.php?error=1");
        exit;

    }

}

header("Location: index.php");
exit;