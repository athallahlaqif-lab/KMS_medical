<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Info Pembayaran Perusahaan
|--------------------------------------------------------------------------
| Ditampilkan di footer semua dokumen (Invoice, DO, Surat Jalan, Tax
| Invoice Internal/External). Ubah nilai di bawah ini kalau ada perubahan
| rekening atau metode pembayaran yang diterima.
|--------------------------------------------------------------------------
*/

if (!defined('COMPANY_BANK_NAME')) {
    define('COMPANY_BANK_NAME', 'Bank BCA');
}

if (!defined('COMPANY_BANK_ACCOUNT_NUMBER')) {
    define('COMPANY_BANK_ACCOUNT_NUMBER', '1234567890');
}

if (!defined('COMPANY_BANK_ACCOUNT_NAME')) {
    define('COMPANY_BANK_ACCOUNT_NAME', 'PT Karunia Medika Sejahtera');
}
