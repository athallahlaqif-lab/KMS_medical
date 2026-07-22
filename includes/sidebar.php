<?php

declare(strict_types=1);

$currentFolder = basename(dirname($_SERVER['PHP_SELF']));

if (!function_exists('isActive')) {
    function isActive(string $folder, string $currentFolder): string
    {
        return $folder === $currentFolder ? 'active' : '';
    }
}
?>

<aside class="sidebar">
    <div class="sidebar-header">
        <img src="<?= BASE_URL ?>assets/images/logo-kms.png" alt="Logo KMS" class="sidebar-logo">
        <div class="sidebar-title">
            <h4>KMS Medical</h4>
            <small>Medical Management System</small>
            <div class="sidebar-version">
                Version 1.0
            </div>
        </div>
    </div>

    <ul class="sidebar-menu">
        <li>
            <a href="<?= BASE_URL ?>dashboard/" class="<?= isActive('dashboard', $currentFolder); ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>users/" class="<?= isActive('users', $currentFolder); ?>">
                <i class="bi bi-people-fill"></i>
                <span>Users</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>categories/" class="<?= isActive('categories', $currentFolder); ?>">
                <i class="bi bi-tags-fill"></i>
                <span>Categories</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>suppliers/" class="<?= isActive('suppliers', $currentFolder); ?>">
                <i class="bi bi-truck"></i>
                <span>Suppliers</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>products/" class="<?= isActive('products', $currentFolder); ?>">
                <i class="bi bi-capsule-pill"></i>
                <span>Products</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>stock_in/" class="<?= isActive('stock_in', $currentFolder); ?>">
                <i class="bi bi-box-arrow-in-down"></i>
                <span>Stock In</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>stock_out/" class="<?= isActive('stock_out', $currentFolder); ?>">
                <i class="bi bi-box-arrow-up"></i>
                <span>Stock Out</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>reports/" class="<?= isActive('reports', $currentFolder); ?>">
                <i class="bi bi-file-earmark-bar-graph-fill"></i>
                <span>Reports</span>
            </a>
        </li>

        <li class="nav-item">
    <a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/logs/') !== false ? 'active' : '' ?>" href="<?= BASE_URL ?>logs/index.php">
        <i class="bi bi-clock-history me-2"></i> Activity Logs
    </a>
</li>
        <li>
            <a href="<?= BASE_URL ?>logs/" class="<?= isActive('logs', $currentFolder); ?>">
                <i class="bi bi-clock-history"></i>
                <span>Activity Logs</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <a href="<?= BASE_URL ?>auth/logout.php" class="logout-btn">
            <i class="bi bi-box-arrow-right me-2"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>