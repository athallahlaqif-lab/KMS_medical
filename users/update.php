<?php

declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);
$fullname = trim($_POST['fullname'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

if (
    $id <= 0 ||
    $fullname === '' ||
    $username === '' ||
    $role === ''
) {
    header('Location: index.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Cek Username Dipakai User Lain
|--------------------------------------------------------------------------
*/

$sql = "
SELECT id
FROM users
WHERE username = :username
AND id <> :id
LIMIT 1
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':username' => $username,
    ':id' => $id
]);

if ($stmt->fetch()) {

    echo "
    <script>
        alert('Username sudah digunakan!');
        window.history.back();
    </script>
    ";

    exit;
}

/*
|--------------------------------------------------------------------------
| Update Tanpa Password
|--------------------------------------------------------------------------
*/

if ($password === '') {

    $sql = "
    UPDATE users
    SET
        fullname = :fullname,
        username = :username,
        role = :role
    WHERE id = :id
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':fullname' => $fullname,
        ':username' => $username,
        ':role' => $role,
        ':id' => $id
    ]);

} else {

    /*
    |--------------------------------------------------------------------------
    | Update Dengan Password Baru
    |--------------------------------------------------------------------------
    */

    $hashPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "
    UPDATE users
    SET
        fullname = :fullname,
        username = :username,
        password = :password,
        role = :role
    WHERE id = :id
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':fullname' => $fullname,
        ':username' => $username,
        ':password' => $hashPassword,
        ':role' => $role,
        ':id' => $id
    ]);

}

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header('Location: index.php?updated=1');
exit;