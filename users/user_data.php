<?php

declare(strict_types=1);

require_once '../config/database.php';

$keyword = trim($_GET['search'] ?? '');

if ($keyword == '') {

    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");

} else {

    $stmt = $pdo->prepare("
        SELECT *
        FROM users
        WHERE fullname LIKE ?
           OR username LIKE ?
           OR role LIKE ?
        ORDER BY id DESC
    ");

    $like = "%{$keyword}%";

    $stmt->execute([$like, $like, $like]);

}

$users = $stmt->fetchAll();