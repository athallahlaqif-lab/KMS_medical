<?php

declare(strict_types=1);

require_once '../../config/session.php';
require_once '../../config/database.php';

requireLogin();

$transactionId = (int)($_POST['transaction_id'] ?? 0);

if ($transactionId <= 0) {
    header('Location: ../../stock_out/index.php');
    exit;
}

$redirectBack = 'index.php?id=' . $transactionId;

if (
    $_SERVER['REQUEST_METHOD'] !== 'POST'
    || !isset($_FILES['signature'])
    || $_FILES['signature']['error'] !== UPLOAD_ERR_OK
) {
    $_SESSION['error'] = 'Upload tanda tangan gagal. Coba lagi.';
    header('Location: ' . $redirectBack);
    exit;
}

/*
|--------------------------------------------------------------------------
| Validasi file (harus gambar, maksimal 2MB)
|--------------------------------------------------------------------------
*/

$allowedTypes = ['image/png', 'image/jpeg'];
$maxSize      = 2 * 1024 * 1024; // 2MB

$file = $_FILES['signature'];

if (!in_array($file['type'], $allowedTypes, true)) {
    $_SESSION['error'] = 'Format file harus PNG atau JPG.';
    header('Location: ' . $redirectBack);
    exit;
}

if ($file['size'] > $maxSize) {
    $_SESSION['error'] = 'Ukuran file maksimal 2MB.';
    header('Location: ' . $redirectBack);
    exit;
}

/*
|--------------------------------------------------------------------------
| Simpan file ke assets/uploads/signatures/
|--------------------------------------------------------------------------
*/

$uploadDir = __DIR__ . '/../../assets/uploads/signatures/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
$fileName = 'signature_' . $transactionId . '_' . time() . '.' . strtolower($ext);
$destPath = $uploadDir . $fileName;

if (!move_uploaded_file($file['tmp_name'], $destPath)) {
    $_SESSION['error'] = 'Gagal menyimpan file tanda tangan.';
    header('Location: ' . $redirectBack);
    exit;
}

/*
|--------------------------------------------------------------------------
| Hapus file tanda tangan lama (kalau ada penggantian)
|--------------------------------------------------------------------------
*/

$stmt = $pdo->prepare("SELECT signature_path FROM transactions WHERE id = ?");
$stmt->execute([$transactionId]);
$old = $stmt->fetchColumn();

if ($old) {
    $oldFullPath = __DIR__ . '/../../' . $old;
    if (is_file($oldFullPath)) {
        @unlink($oldFullPath);
    }
}

/*
|--------------------------------------------------------------------------
| Update path di database (relatif dari root project)
|--------------------------------------------------------------------------
*/

$relativePath = 'assets/uploads/signatures/' . $fileName;

$stmt = $pdo->prepare("
    UPDATE transactions
    SET signature_path = ?
    WHERE id = ?
");
$stmt->execute([$relativePath, $transactionId]);

header('Location: ' . $redirectBack);
exit;
