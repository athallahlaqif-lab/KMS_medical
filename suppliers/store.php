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

$supplier_name = trim($_POST['supplier_name'] ?? '');
$contact_person = trim($_POST['contact_person'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$address = trim($_POST['address'] ?? '');

/*
|--------------------------------------------------------------------------
| Validasi
|--------------------------------------------------------------------------
*/

if ($supplier_name === '') {

    header('Location: create.php');
    exit;

}

/*
|--------------------------------------------------------------------------
| Cek Nama Supplier
|--------------------------------------------------------------------------
*/

$sql = "
SELECT id
FROM suppliers
WHERE supplier_name = :supplier_name
LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':supplier_name' => $supplier_name

]);

if ($stmt->fetch()) {

    echo "

    <script>

        alert('Nama supplier sudah digunakan!');

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

$sql = "
INSERT INTO suppliers
(
    supplier_name,
    contact_person,
    phone,
    email,
    address
)
VALUES
(
    :supplier_name,
    :contact_person,
    :phone,
    :email,
    :address
)
";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':supplier_name' => $supplier_name,
    ':contact_person' => $contact_person,
    ':phone' => $phone,
    ':email' => $email,
    ':address' => $address

]);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header('Location: index.php?success=1');
exit;