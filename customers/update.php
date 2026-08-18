<?php

declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

/*
|--------------------------------------------------------------------------
| Hanya menerima POST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Ambil Data
|--------------------------------------------------------------------------
*/

$id            = (int)($_POST['id'] ?? 0);
$customer_code = trim($_POST['customer_code'] ?? '');
$customer_name = trim($_POST['customer_name'] ?? '');
$phone         = trim($_POST['phone'] ?? '');
$email         = trim($_POST['email'] ?? '');
$city          = trim($_POST['city'] ?? '');
$address       = trim($_POST['address'] ?? '');

/*
|--------------------------------------------------------------------------
| Validasi
|--------------------------------------------------------------------------
*/

if ($id <= 0 || $customer_code === '' || $customer_name === '') {
    header('Location: index.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Cek Customer Code selain dirinya sendiri
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT id
FROM customers
WHERE customer_code = ?
AND id != ?
LIMIT 1
");

$stmt->execute([$customer_code, $id]);

if ($stmt->fetch()) {

    echo "
    <script>
        alert('Customer Code sudah digunakan!');
        history.back();
    </script>
    ";

    exit;
}

/*
|--------------------------------------------------------------------------
| Cek Customer Name selain dirinya sendiri
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT id
FROM customers
WHERE customer_name = ?
AND id != ?
LIMIT 1
");

$stmt->execute([$customer_name, $id]);

if ($stmt->fetch()) {

    echo "
    <script>
        alert('Customer sudah terdaftar!');
        history.back();
    </script>
    ";

    exit;
}

/*
|--------------------------------------------------------------------------
| Update Data
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
UPDATE customers
SET
    customer_code = ?,
    customer_name = ?,
    phone = ?,
    email = ?,
    city = ?,
    address = ?
WHERE id = ?
");

$stmt->execute([
    $customer_code,
    $customer_name,
    $phone,
    $email,
    $city,
    $address,
    $id
]);

/*
|--------------------------------------------------------------------------
| Activity Log
|--------------------------------------------------------------------------
*/

logActivity(
    $pdo,
    'UPDATE CUSTOMER',
    'Mengubah customer: ' . $customer_name
);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header('Location: index.php?updated=1');
exit;