<?php
declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    die("ID Transaksi tidak valid.");
}

// Ambil data transaksi stock in
$stmt = $pdo->prepare("
    SELECT 
        si.*, 
        p.product_name, 
        p.product_code
    FROM stock_in si
    LEFT JOIN products p ON si.product_id = p.id
    WHERE si.id = ?
");
$stmt->execute([$id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    die("Data transaksi tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi Stock In #<?= $data['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #333;
        }
        .receipt-card {
            max-width: 600px;
            margin: 30px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        @media print {
            body { background: #fff; }
            .receipt-card { box-shadow: none; border: none; margin: 0; width: 100%; max-width: 100%; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="receipt-card">
        <!-- Header Nota -->
        <div class="d-flex justify-content-between align-items-center pb-3 mb-3 border-bottom">
            <div>
                <h4 class="fw-bold mb-0 text-primary">KMS Medical</h4>
                <small class="text-muted">Medical Management System</small>
            </div>
            <div class="text-end">
                <span class="badge bg-success px-3 py-2">STOCK IN</span>
                <div class="small text-muted mt-1">#TRX-IN-<?= str_pad((string)$data['id'], 5, '0', STR_PAD_LEFT) ?></div>
            </div>
        </div>

        <!-- Detail Transaksi -->
        <div class="row mb-4 small">
            <div class="col-6">
                <strong>Tanggal Transaksi:</strong><br>
                <?= date('d F Y', strtotime($data['transaction_date'] ?? $data['created_at'])) ?><br><br>
                <strong>Petugas Input:</strong><br>
                Administrator
            </div>
            <div class="col-6 text-end">
                <strong>Kode Barang:</strong><br>
                <?= htmlspecialchars($data['product_code'] ?? '-') ?>
            </div>
        </div>

        <!-- Tabel Barang -->
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama Produk / Obat</th>
                    <th class="text-center" width="120">Jumlah (Qty)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong class="text-dark"><?= htmlspecialchars($data['product_name'] ?? 'Produk Tidak Ditemukan') ?></strong>
                        <div class="small text-muted">Stok Sebelum: <?= $data['stock_before'] ?? 0 ?> &rarr; Sesudah: <?= $data['stock_after'] ?? 0 ?></div>
                    </td>
                    <td class="text-center fw-bold text-success fs-5">
                        +<?= number_format((float)($data['qty'] ?? 0)) ?>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php if (!empty($data['note'])): ?>
            <div class="p-3 bg-light rounded-3 mb-4 small">
                <strong>Catatan / Keterangan:</strong><br>
                <?= nl2br(htmlspecialchars($data['note'])) ?>
            </div>
        <?php endif; ?>

        <!-- Tanda Tangan -->
        <div class="row text-center mt-5 pt-3 small">
            <div class="col-6">
                <p class="mb-5">Petugas Penerima,</p>
                <p class="fw-bold mb-0">( Administrator )</p>
            </div>
            <div class="col-6">
                <p class="mb-5">Pemasok / Supplier,</p>
                <p class="fw-bold mb-0">( .................... )</p>
            </div>
        </div>

        <!-- Tombol Cetak (Hilang Saat Diprint) -->
        <div class="text-center mt-4 pt-3 border-top no-print">
            <button onclick="window.print()" class="btn btn-primary px-4 me-2 rounded-pill">
                <i class="bi bi-printer me-1"></i> Cetak Struk
            </button>
            <button onclick="window.close()" class="btn btn-secondary px-4 rounded-pill">
                Tutup
            </button>
        </div>
    </div>
</div>

</body>
</html>