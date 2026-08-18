<?php

declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';
require_once '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

requireLogin();

/*
|--------------------------------------------------------------------------
| Export Laporan Keuangan ke Excel (.xlsx asli, pakai PhpSpreadsheet)
|--------------------------------------------------------------------------
*/

$start = $_GET['start'] ?? '';
$end   = $_GET['end'] ?? '';

$where  = '';
$params = [];

if ($start !== '' && $end !== '') {
    $where = " WHERE t.transaction_date BETWEEN :start AND :end ";
    $params[':start'] = $start . ' 00:00:00';
    $params[':end']   = $end . ' 23:59:59';
}

$sql = "
    SELECT
        t.invoice_number,
        t.transaction_date,
        t.payment_method,
        c.customer_name,
        SUM(td.subtotal) AS revenue,
        SUM(td.purchase_price * td.quantity) AS cost
    FROM transactions t
    INNER JOIN transaction_details td
        ON td.transaction_id = t.id
    LEFT JOIN customers c
        ON c.id = t.customer_id
    $where
    GROUP BY t.id
    ORDER BY t.transaction_date ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalRevenue = 0.0;
$totalCost    = 0.0;

/*
|--------------------------------------------------------------------------
| Bangun file Excel
|--------------------------------------------------------------------------
*/

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Financial Report');

// Judul laporan
$sheet->setCellValue('A1', 'LAPORAN KEUANGAN - KMS MEDICAL');
$sheet->mergeCells('A1:H1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$periodeText = ($start !== '' && $end !== '')
    ? 'Periode: ' . date('d/m/Y', strtotime($start)) . ' s/d ' . date('d/m/Y', strtotime($end))
    : 'Periode: Semua Transaksi';

$sheet->setCellValue('A2', $periodeText);
$sheet->mergeCells('A2:H2');
$sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A2')->getFont()->setItalic(true);

// Header tabel (baris ke-4)
$headerRow = 4;
$headers = ['No', 'Invoice', 'Tanggal', 'Customer', 'Metode Bayar', 'Pendapatan', 'Modal', 'Margin'];
$col = 'A';
foreach ($headers as $h) {
    $sheet->setCellValue($col . $headerRow, $h);
    $col++;
}

$sheet->getStyle('A' . $headerRow . ':H' . $headerRow)->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
$sheet->getStyle('A' . $headerRow . ':H' . $headerRow)->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setRGB('2E7D32');
$sheet->getStyle('A' . $headerRow . ':H' . $headerRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Isi data
$rowNum = $headerRow + 1;
$no = 1;

foreach ($rows as $r) {
    $revenue = (float)$r['revenue'];
    $cost    = (float)$r['cost'];
    $margin  = $revenue - $cost;

    $totalRevenue += $revenue;
    $totalCost    += $cost;

    $sheet->setCellValue('A' . $rowNum, $no++);
    $sheet->setCellValue('B' . $rowNum, $r['invoice_number']);
    $sheet->setCellValue('C' . $rowNum, date('d/m/Y', strtotime($r['transaction_date'])));
    $sheet->setCellValue('D' . $rowNum, $r['customer_name'] ?? '-');
    $sheet->setCellValue('E' . $rowNum, $r['payment_method'] ?? '-');
    $sheet->setCellValue('F' . $rowNum, $revenue);
    $sheet->setCellValue('G' . $rowNum, $cost);
    $sheet->setCellValue('H' . $rowNum, $margin);

    $rowNum++;
}

$lastDataRow = $rowNum - 1;

// Format kolom Pendapatan/Modal/Margin sebagai angka Rupiah (pemisah ribuan)
if ($lastDataRow >= $headerRow + 1) {
    $sheet->getStyle('F' . ($headerRow + 1) . ':H' . $lastDataRow)
        ->getNumberFormat()->setFormatCode('#,##0');
}

$totalMargin = $totalRevenue - $totalCost;
$totalProfit = $totalMargin;

// Baris total (kasih 1 baris kosong dulu sebagai pemisah)
$totalStartRow = $rowNum + 1;

$labels = [
    'TOTAL PENDAPATAN' => $totalRevenue,
    'TOTAL MODAL'      => $totalCost,
    'TOTAL MARGIN'     => $totalMargin,
    'TOTAL PROFIT'     => $totalProfit,
];

$r = $totalStartRow;
foreach ($labels as $label => $value) {
    $sheet->setCellValue('E' . $r, $label);
    $sheet->setCellValue('F' . $r, $value);
    $sheet->getStyle('E' . $r)->getFont()->setBold(true);
    $sheet->getStyle('F' . $r)->getFont()->setBold(true);
    $sheet->getStyle('F' . $r)->getNumberFormat()->setFormatCode('#,##0');
    $r++;
}

// Border tipis untuk seluruh area tabel data
$sheet->getStyle('A' . $headerRow . ':H' . $lastDataRow)
    ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Lebar kolom otomatis menyesuaikan isi (INI YANG MEMPERBAIKI MASALAH #####)
foreach (range('A', 'H') as $columnId) {
    $sheet->getColumnDimension($columnId)->setAutoSize(true);
}

/*
|--------------------------------------------------------------------------
| Kirim file ke browser
|--------------------------------------------------------------------------
*/

$fileName = 'Financial-Report-' . date('Ymd-His') . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
