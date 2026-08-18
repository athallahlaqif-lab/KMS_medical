<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Application Configuration
|--------------------------------------------------------------------------
*/

if (!defined('BASE_URL')) {
    /*
    |----------------------------------------------------------------------
    | BASE_URL Otomatis
    |----------------------------------------------------------------------
    | Dihitung otomatis dari lokasi folder project ini terhadap document
    | root server. Jadi tidak perlu diubah manual lagi, baik project ada
    | di subfolder (misal: localhost/kms_project/) maupun langsung di
    | domain root (misal: kms-medical.my.id/).
    |
    | Kalau deteksi otomatis gagal (misal DOCUMENT_ROOT tidak tersedia),
    | fallback ke '/' (asumsi project ada di root domain).
    |----------------------------------------------------------------------
    */
    $projectRoot  = realpath(__DIR__ . '/..');
    $documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? realpath($_SERVER['DOCUMENT_ROOT']) : false;

    if ($projectRoot && $documentRoot && strpos($projectRoot, $documentRoot) === 0) {
        $relative = str_replace('\\', '/', substr($projectRoot, strlen($documentRoot)));
        $relative = trim($relative, '/');
        $base     = $relative === '' ? '/' : '/' . $relative . '/';
    } else {
        $base = '/';
    }

    define('BASE_URL', $base);
}

if (!isset($pageTitle)) {
    $pageTitle = 'Dashboard';
}

if (!isset($pageIcon)) {
    $pageIcon = 'bi-grid-fill';
}
