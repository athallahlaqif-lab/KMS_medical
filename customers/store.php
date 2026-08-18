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

if ($customer_code === '' || $customer_name === '') {

    header('Location: create.php');
    exit;

}

/*
|--------------------------------------------------------------------------
| Cek Customer Code
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT id
FROM customers
WHERE customer_code = ?
LIMIT 1
");

$stmt->execute([$customer_code]);

if ($stmt->fetch()) {

    echo "
    <script>
        alert('Customer Code sudah digunakan!');
        window.location='create.php';
    </script>
    ";

    exit;

}

/*
|--------------------------------------------------------------------------
| Cek Customer Name
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
SELECT id
FROM customers
WHERE customer_name = ?
LIMIT 1
");

$stmt->execute([$customer_name]);

if ($stmt->fetch()) {

    echo "
    <script>
        alert('Customer sudah terdaftar!');
        window.location='create.php';
    </script>
    ";

    exit;

}

/*
|--------------------------------------------------------------------------
| Simpan Data
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("
INSERT INTO customers
(
    customer_code,
    customer_name,
    phone,
    email,
    city,
    address
)
VALUES
(
    ?,?,?,?,?,?
)
");

$stmt->execute([
    $customer_code,
    $customer_name,
    $phone,
    $email,
    $city,
    $address
]);

/*
|--------------------------------------------------------------------------
| Activity Log
|--------------------------------------------------------------------------
*/

logActivity(
    $pdo,
    'CREATE CUSTOMER',
    'Menambahkan customer: '.$customer_name
);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header('Location: index.php?success=1');
exit;