<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Application Configuration
|--------------------------------------------------------------------------
*/

if (!defined('BASE_URL')) {
    define('BASE_URL', '/kms_project/');
}

if (!isset($pageTitle)) {
    $pageTitle = 'Dashboard';
}

if (!isset($pageIcon)) {
    $pageIcon = 'bi-grid-fill';
}