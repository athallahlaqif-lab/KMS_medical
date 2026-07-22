<?php

declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$fullname = trim($_POST['fullname'] ?? '');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

if (
    $fullname === '' ||
    $username === '' ||
    $password === '' ||
    $role === ''
) {
    header('Location: create.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| Cek Username
|--------------------------------------------------------------------------
*/

$sql = "SELECT id FROM users WHERE username = :username LIMIT 1";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':username' => $username
]);

if ($stmt->fetch()) {

    echo "
    <script>
        alert('Username sudah digunakan!');
        window.location='create.php';
    </script>
    ";

    exit;
}

/*
|--------------------------------------------------------------------------
| Hash Password
|--------------------------------------------------------------------------
*/

$hashPassword = password_hash($password, PASSWORD_DEFAULT);

/*
|--------------------------------------------------------------------------
| Simpan User
|--------------------------------------------------------------------------
*/

$sql = "
INSERT INTO users
(
    fullname,
    username,
    password,
    role
)
VALUES
(
    :fullname,
    :username,
    :password,
    :role
)
";

$stmt = $pdo->prepare($sql);

$stmt->execute([

    ':fullname' => $fullname,

    ':username' => $username,

    ':password' => $hashPassword,

    ':role' => $role

]);

/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header('Location: index.php?success=1');
exit;