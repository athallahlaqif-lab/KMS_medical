<?php

declare(strict_types=1);

require_once '../config/database.php';
require_once '../config/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($username === '' || $password === '') {
    header('Location: login.php?error=1');
    exit;
}

$sql = "SELECT * FROM users WHERE username = :username LIMIT 1";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':username' => $username
]);

$user = $stmt->fetch();

if (!$user) {
    header('Location: login.php?error=1');
    exit;
}

if (!password_verify($password, $user['password'])) {
    header('Location: login.php?error=1');
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['fullname'] = $user['fullname'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];

header('Location: ../dashboard/index.php');
exit;