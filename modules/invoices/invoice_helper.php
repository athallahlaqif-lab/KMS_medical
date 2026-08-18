<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| KMS Medical - Invoice Helper
|--------------------------------------------------------------------------
| Digunakan oleh:
| - invoice_print.php
| - tax_invoice_internal.php
| - tax_invoice_external.php
| - delivery_order.php
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../shared/document_helper.php';

/*
|--------------------------------------------------------------------------
| CATATAN
|--------------------------------------------------------------------------
| Fungsi pengambilan data (dulu bernama getInvoice()) sudah dipindahkan
| ke modules/shared/document_helper.php sebagai getTransactionHeader()
| + getTransactionItems(), supaya invoice_print.php dan invoice_pdf.php memakai SATU sumber data yang sama
| (sebelumnya dua fungsi terpisah dengan query identik menyebabkan tanggal
| invoice di halaman print dan di PDF bisa berbeda).
|--------------------------------------------------------------------------
*/

/**
 * Membuat Nomor Invoice
 * Contoh:
 * INV-20260728-000015
 */
function generateInvoiceNumber(int $id): string
{
    return 'INV-' . date('Ymd') . '-' . str_pad((string)$id, 6, '0', STR_PAD_LEFT);
}

/**
 * Menghitung subtotal
 */
function calculateSubtotal(array $data): float
{
    return (float)$data['selling_price'] * (int)$data['qty'];
}

/**
 * Menghitung PPN 11%
 */
function calculatePPN(float $subtotal): float
{
    return $subtotal * 0.11;
}

/**
 * Grand Total
 */
function calculateGrandTotal(float $subtotal): float
{
    return $subtotal + calculatePPN($subtotal);
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
 * Contoh:
 * 28 Juli 2026
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