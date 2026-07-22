<?php

declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header('Location: index.php');
    exit;

}

/*
|--------------------------------------------------------------------------
| Ambil Data
|--------------------------------------------------------------------------
*/

$product_code = trim($_POST['product_code'] ?? '');

$product_name = trim($_POST['product_name'] ?? '');

$category_id = (int) ($_POST['category_id'] ?? 0);

$supplier_id = (int) ($_POST['supplier_id'] ?? 0);

$unit = trim($_POST['unit'] ?? '');

$purchase_price = (float) ($_POST['purchase_price'] ?? 0);

$selling_price = (float) ($_POST['selling_price'] ?? 0);

$stock = (int) ($_POST['stock'] ?? 0);

$description = trim($_POST['description'] ?? '');

if (

    $product_code === '' ||

    $product_name === '' ||

    $category_id <= 0 ||

    $supplier_id <= 0

) {

    header('Location: create.php');
    exit;

}

/*
|--------------------------------------------------------------------------
| Upload Photo
|--------------------------------------------------------------------------
*/

$photoName = '';

if (

    isset($_FILES['photo']) &&

    $_FILES['photo']['error'] === UPLOAD_ERR_OK

) {

    $extension = strtolower(pathinfo(

        $_FILES['photo']['name'],

        PATHINFO_EXTENSION

    ));

    $allowed = [

        'jpg',

        'jpeg',

        'png',

        'webp'

    ];

    if (!in_array($extension, $allowed)) {

        die('Format gambar tidak didukung.');

    }

    $photoName = uniqid('product_', true) . '.' . $extension;

    $destination = __DIR__ . '/upload/' . $photoName;

    move_uploaded_file(

        $_FILES['photo']['tmp_name'],

        $destination

    );

}
/*
|--------------------------------------------------------------------------
| Cek Product Code
|--------------------------------------------------------------------------
*/

$sql = "
SELECT id
FROM products
WHERE product_code = :product_code
LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':product_code' => $product_code

]);

if ($stmt->fetch()) {

    echo "

    <script>

        alert('Product Code sudah digunakan!');

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
INSERT INTO products
(

    category_id,

    supplier_id,

    product_code,

    product_name,

    unit,

    purchase_price,

    selling_price,

    stock,

    photo,

    description

)
VALUES
(

    :category_id,

    :supplier_id,

    :product_code,

    :product_name,

    :unit,

    :purchase_price,

    :selling_price,

    :stock,

    :photo,

    :description

)
";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':category_id'     => $category_id,

    ':supplier_id'     => $supplier_id,

    ':product_code'    => $product_code,

    ':product_name'    => $product_name,

    ':unit'            => $unit,

    ':purchase_price'  => $purchase_price,

    ':selling_price'   => $selling_price,

    ':stock'           => $stock,

    ':photo'           => $photoName,

    ':description'     => $description

]);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header('Location: index.php?success=1');

exit;