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

$id = (int) ($_POST['id'] ?? 0);

$product_code = trim($_POST['product_code'] ?? '');

$product_name = trim($_POST['product_name'] ?? '');

$category_id = (int) ($_POST['category_id'] ?? 0);

$supplier_id = (int) ($_POST['supplier_id'] ?? 0);

$unit = trim($_POST['unit'] ?? '');

$purchase_price = (float) ($_POST['purchase_price'] ?? 0);

$selling_price = (float) ($_POST['selling_price'] ?? 0);

$stock = (int) ($_POST['stock'] ?? 0);

$description = trim($_POST['description'] ?? '');

$old_photo = $_POST['old_photo'] ?? '';

if (

    $id <= 0 ||

    $product_code === '' ||

    $product_name === ''

) {

    header('Location:index.php');

    exit;

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
AND id <> :id
LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':product_code' => $product_code,

    ':id' => $id

]);

if($stmt->fetch()){

    echo "

    <script>

    alert('Product Code sudah digunakan!');

    window.history.back();

    </script>

    ";

    exit;

}

/*
|--------------------------------------------------------------------------
| Upload Foto Baru
|--------------------------------------------------------------------------
*/

$photo = $old_photo;

if(

isset($_FILES['photo']) &&

$_FILES['photo']['error']==UPLOAD_ERR_OK

){

$extension=strtolower(pathinfo(

$_FILES['photo']['name'],

PATHINFO_EXTENSION

));

$allowed=['jpg','jpeg','png','webp'];

if(!in_array($extension,$allowed)){

die('Format gambar tidak didukung.');

}

$photo=uniqid('product_',true).'.'.$extension;

move_uploaded_file(

$_FILES['photo']['tmp_name'],

__DIR__.'/upload/'.$photo

);
    /*
    |--------------------------------------------------------------------------
    | Hapus Foto Lama
    |--------------------------------------------------------------------------
    */

    if (

        !empty($old_photo) &&

        file_exists(__DIR__ . '/upload/' . $old_photo)

    ) {

        unlink(__DIR__ . '/upload/' . $old_photo);

    }

}

/*
|--------------------------------------------------------------------------
| Update Database
|--------------------------------------------------------------------------
*/

$sql = "
UPDATE products
SET

    category_id = :category_id,

    supplier_id = :supplier_id,

    product_code = :product_code,

    product_name = :product_name,

    unit = :unit,

    purchase_price = :purchase_price,

    selling_price = :selling_price,

    stock = :stock,

    photo = :photo,

    description = :description

WHERE id = :id
";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':category_id'    => $category_id,

    ':supplier_id'    => $supplier_id,

    ':product_code'   => $product_code,

    ':product_name'   => $product_name,

    ':unit'           => $unit,

    ':purchase_price' => $purchase_price,

    ':selling_price'  => $selling_price,

    ':stock'          => $stock,

    ':photo'          => $photo,

    ':description'    => $description,

    ':id'             => $id

]);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header('Location: index.php?updated=1');
exit;