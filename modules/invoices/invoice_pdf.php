<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/*
|--------------------------------------------------------------------------
| Render HTML dengan template YANG SAMA dengan invoice_print.php
|--------------------------------------------------------------------------
| invoice_print.php sudah menangani: guard login, ambil header transaksi,
| ambil semua item produk, hitung subtotal/ppn/grand_total, dan generate
| HTML lengkap. Kita include dalam output buffer supaya HTML-nya bisa
| dikonversi ke PDF, tanpa duplikasi logika query/mapping.
|--------------------------------------------------------------------------
*/
ob_start();
require __DIR__ . '/invoice_print.php';
$html = ob_get_clean();

$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('defaultMediaType', 'print'); // paksa Dompdf pakai CSS @media print
$options->set('chroot', realpath(__DIR__ . '/../../')); // izinkan Dompdf akses folder project (default-nya cuma boleh akses folder vendor/dompdf sendiri)

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setBasePath(__DIR__); // supaya gambar (logo) dengan path relatif bisa ditemukan Dompdf
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream(
    'Invoice-' . $invoice_number . '.pdf',
    ['Attachment' => true]
);
