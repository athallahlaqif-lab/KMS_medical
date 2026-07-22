<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Session Configuration
|--------------------------------------------------------------------------
|
| File ini digunakan untuk menjalankan session aplikasi.
| Semua halaman yang membutuhkan login akan memanggil file ini.
|
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/*
|--------------------------------------------------------------------------
| Helper Function
|--------------------------------------------------------------------------
*/

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

/*
|--------------------------------------------------------------------------
| Redirect Jika Belum Login
|--------------------------------------------------------------------------
*/

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: /kms_project/auth/login.php');
        exit;
    }
}
/**
 * Fungsi untuk mencatat aktivitas pengguna ke database
 */
function logActivity(PDO $pdo, string $action, string $description): void {
    if (isset($_SESSION['user_id'])) {
        $userId   = $_SESSION['user_id'];
        $userName = $_SESSION['user_name'] ?? 'System';

        $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, user_name, action, description, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([$userId, $userName, $action, $description]);
    }
}