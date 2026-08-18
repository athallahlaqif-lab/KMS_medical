<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| KMS Medical - Tax Invoice External Helper
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../shared/document_helper.php';

/*
|--------------------------------------------------------------------------
| CATATAN
|--------------------------------------------------------------------------
| Fungsi getInvoice() (dulu pakai LIMIT 1, jadi cuma ambil 1 produk)
| sudah dipindah ke modules/shared/document_helper.php sebagai
| getTransactionHeader() + getTransactionItems().
|--------------------------------------------------------------------------
*/

/**
 * Nomor Tax Invoice External
 */
function generateTaxExternalNumber(int $id): string
{
    return 'TIE-' . date('Ymd') . '-' . str_pad((string)$id, 6, '0', STR_PAD_LEFT);
}

/**
 * Format Rupiah
 */
function rupiah(float $nominal): string
{
    return 'Rp ' . number_format($nominal, 0, ',', '.');
}

/**
 * Format tanggal Indonesia
 */
function formatTanggal(string $tanggal): string
{
    $bulan = [
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    ];

    $time = strtotime($tanggal);

    return date('d', $time)
        . ' '
        . $bulan[(int)date('n', $time)]
        . ' '
        . date('Y', $time);
}
