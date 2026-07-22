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

$id = (int) ($_POST['id'] ?? 0);

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

if (

    $id <= 0 ||

    $supplier_name === ''

) {

    header('Location: index.php');
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
AND id <> :id
LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':supplier_name' => $supplier_name,

    ':id' => $id

]);

if ($stmt->fetch()) {

    echo "

    <script>

        alert('Nama supplier sudah digunakan!');

        window.history.back();

    </script>

    ";

    exit;

}

/*
|--------------------------------------------------------------------------
| Update
|--------------------------------------------------------------------------
*/

$sql = "
UPDATE suppliers
SET

supplier_name = :supplier_name,

contact_person = :contact_person,

phone = :phone,

email = :email,

address = :address

WHERE id = :id
";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':supplier_name' => $supplier_name,

    ':contact_person' => $contact_person,

    ':phone' => $phone,

    ':email' => $email,

    ':address' => $address,

    ':id' => $id

]);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header('Location: index.php?updated=1');

exit;