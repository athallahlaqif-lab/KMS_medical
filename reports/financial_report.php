<?php

declare(strict_types=1);

$pageTitle = 'Financial Report';
$pageIcon  = 'bi bi-cash-coin';

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

/*
|--------------------------------------------------------------------------
| Filter Tanggal
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

/*
|--------------------------------------------------------------------------
| Query Pendapatan, Modal, Margin per Transaksi
|--------------------------------------------------------------------------
| Pendapatan = jumlah subtotal (harga jual x qty), TIDAK termasuk PPN
| (PPN bukan pendapatan perusahaan, cuma dipungut untuk disetor ke negara).
| Modal      = jumlah harga beli x qty (HPP / Cost of Goods Sold).
| Margin     = Pendapatan - Modal (margin kotor).
| Profit     = disamakan dengan Margin, karena sistem ini belum mencatat
|              biaya operasional terpisah (gaji, sewa, dll).
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        t.id,
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
    ORDER BY t.transaction_date DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalRevenue = 0.0;
$totalCost    = 0.0;

foreach ($rows as $r) {
    $totalRevenue += (float)$r['revenue'];
    $totalCost    += (float)$r['cost'];
}

$totalMargin = $totalRevenue - $totalCost;
$totalProfit = $totalMargin;

include '../includes/header.php';
?>

<div class="wrapper">

    <?php include '../includes/sidebar.php'; ?>

    <div class="main">

        <?php include '../includes/navbar.php'; ?>

        <div class="content">

            <div class="container-fluid">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>
                        <h3 class="fw-bold">
                            <i class="bi bi-cash-coin me-2 text-success"></i>
                            Financial Report
                        </h3>
                        <p class="text-muted mb-0">
                            Pendapatan, Modal, Margin, dan Profit dari seluruh transaksi.
                        </p>
                    </div>

                    <a href="index.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i>
                        Back
                    </a>

                </div>

                <form method="GET" class="row g-3 mb-4">

                    <div class="col-md-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start" class="form-control"
                               value="<?= htmlspecialchars($start) ?>">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end" class="form-control"
                               value="<?= htmlspecialchars($end) ?>">
                    </div>

                    <div class="col-md-6 d-flex align-items-end gap-2">
                        <button class="btn btn-primary">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="financial_report.php" class="btn btn-secondary">Reset</a>
                        <a href="export_excel.php?start=<?= urlencode($start) ?>&end=<?= urlencode($end) ?>"
                           class="btn btn-outline-success ms-auto">
                            <i class="bi bi-file-earmark-spreadsheet"></i>
                            Export Excel
                        </a>
                    </div>

                </form>

                <div class="row mb-4">

                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow">
                            <div class="card-body">
                                <h6 class="text-muted">Pendapatan</h6>
                                <h4 class="fw-bold text-primary">
                                    Rp <?= number_format($totalRevenue, 0, ',', '.') ?>
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow">
                            <div class="card-body">
                                <h6 class="text-muted">Modal</h6>
                                <h4 class="fw-bold text-danger">
                                    Rp <?= number_format($totalCost, 0, ',', '.') ?>
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow">
                            <div class="card-body">
                                <h6 class="text-muted">Margin</h6>
                                <h4 class="fw-bold text-info">
                                    Rp <?= number_format($totalMargin, 0, ',', '.') ?>
                                </h4>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-3">
                        <div class="card border-0 shadow">
                            <div class="card-body">
                                <h6 class="text-muted">Profit</h6>
                                <h4 class="fw-bold text-success">
                                    Rp <?= number_format($totalProfit, 0, ',', '.') ?>
                                </h4>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card shadow border-0">

                    <div class="card-body">

                        <div class="table-responsive">

                            <table class="table table-bordered table-hover align-middle">

                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>Invoice</th>
                                        <th>Tanggal</th>
                                        <th>Customer</th>
                                        <th>Metode Bayar</th>
                                        <th class="text-end">Pendapatan</th>
                                        <th class="text-end">Modal</th>
                                        <th class="text-end">Margin</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php if (count($rows) > 0): ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($rows as $r): ?>
                                            <?php $margin = (float)$r['revenue'] - (float)$r['cost']; ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= htmlspecialchars($r['invoice_number']) ?></td>
                                                <td><?= date('d M Y', strtotime($r['transaction_date'])) ?></td>
                                                <td><?= htmlspecialchars($r['customer_name'] ?? '-') ?></td>
                                                <td><?= htmlspecialchars($r['payment_method'] ?? '-') ?></td>
                                                <td class="text-end">Rp <?= number_format((float)$r['revenue'], 0, ',', '.') ?></td>
                                                <td class="text-end">Rp <?= number_format((float)$r['cost'], 0, ',', '.') ?></td>
                                                <td class="text-end fw-bold">Rp <?= number_format($margin, 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="text-center">Belum ada data transaksi.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <?php include '../includes/footer.php'; ?>

        </div>

    </div>

</div>

<?php include '../includes/scripts.php'; ?>
