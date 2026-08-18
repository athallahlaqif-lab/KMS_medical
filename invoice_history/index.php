<?php
declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

/*
|--------------------------------------------------------------------------
| Filter: tipe dokumen, pencarian, rentang tanggal
|--------------------------------------------------------------------------
*/

$type   = $_GET['type'] ?? 'all';
$search = trim($_GET['search'] ?? '');
$start  = $_GET['start_date'] ?? '';
$end    = $_GET['end_date'] ?? '';

$allowedTypes = ['all', 'invoice', 'do', 'sj', 'tax_internal', 'tax_external'];
if (!in_array($type, $allowedTypes, true)) {
    $type = 'all';
}

$where  = [];
$params = [];

if ($search !== '') {
    $where[] = '(t.invoice_number LIKE :search OR c.customer_name LIKE :search)';
    $params[':search'] = '%' . $search . '%';
}

if ($start !== '' && $end !== '') {
    $where[] = 't.transaction_date BETWEEN :start AND :end';
    $params[':start'] = $start . ' 00:00:00';
    $params[':end']   = $end . ' 23:59:59';
}

$whereSql = count($where) > 0 ? ' WHERE ' . implode(' AND ', $where) : '';

$sql = "
    SELECT
        t.id,
        t.invoice_number,
        t.transaction_date,
        t.payment_method,
        t.grand_total,
        c.customer_name
    FROM transactions t
    LEFT JOIN customers c
        ON c.id = t.customer_id
    $whereSql
    ORDER BY t.transaction_date DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*
|--------------------------------------------------------------------------
| Konfigurasi tiap tab dokumen: label, folder print/pdf-nya
|--------------------------------------------------------------------------
*/

$docConfig = [
    'invoice'      => ['label' => 'Invoice',            'folder' => 'modules/invoices',              'print' => 'invoice_print.php', 'pdf' => 'invoice_pdf.php'],
    'do'           => ['label' => 'Delivery Order',      'folder' => 'modules/delivery_order',         'print' => 'do_print.php',       'pdf' => 'do_pdf.php'],
    'sj'           => ['label' => 'Surat Jalan',         'folder' => 'modules/surat_jalan',            'print' => 'sj_print.php',       'pdf' => 'sj_pdf.php'],
    'tax_internal' => ['label' => 'Tax Invoice Internal', 'folder' => 'modules/tax_invoice_internal',   'print' => 'tax_internal_print.php', 'pdf' => 'tax_internal_pdf.php'],
    'tax_external' => ['label' => 'Tax Invoice External', 'folder' => 'modules/tax_invoice_external',   'print' => 'external_print.php', 'pdf' => 'external_pdf.php'],
];

$pageTitle = 'Invoice History';
include '../includes/header.php';
?>

<div class="container-fluid px-4 py-3">

    <!-- Header Section -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-1 text-danger"><i class="bi bi-receipt me-2"></i>Invoice History</h4>
            <p class="text-muted small mb-0">Riwayat seluruh transaksi & dokumen (Invoice, DO, Surat Jalan, Tax Invoice).</p>
        </div>
        <div class="d-flex gap-2">
            <a href="../dashboard/index.php" class="btn btn-outline-secondary rounded-pill px-3 shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <!-- Tab Jenis Dokumen -->
    <ul class="nav nav-pills mb-3 flex-wrap">
        <?php
        $tabs = ['all' => 'Semua'] + array_map(fn($d) => $d['label'], $docConfig);
        foreach ($tabs as $key => $label):
            $activeClass = ($type === $key) ? 'active' : '';
            $qs = http_build_query([
                'type'       => $key,
                'search'     => $search,
                'start_date' => $start,
                'end_date'   => $end,
            ]);
        ?>
            <li class="nav-item">
                <a class="nav-link <?= $activeClass ?>" href="?<?= $qs ?>">
                    <?= htmlspecialchars($label) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form method="GET" class="row g-2 align-items-center">
                <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">

                <div class="col-md-4">
                    <label class="form-label small text-muted mb-1">Cari Invoice/Customer</label>
                    <input type="text" name="search" class="form-control form-control-sm rounded-pill"
                           placeholder="Nomor invoice atau nama customer..."
                           value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control form-control-sm rounded-pill"
                           value="<?= htmlspecialchars($start) ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control form-control-sm rounded-pill"
                           value="<?= htmlspecialchars($end) ?>">
                </div>
                <div class="col-md-2 d-flex align-items-end mt-auto">
                    <button type="submit" class="btn btn-danger btn-sm rounded-pill w-100 me-1">
                        <i class="bi bi-search me-1"></i> Filter
                    </button>
                    <a href="?type=<?= htmlspecialchars($type) ?>" class="btn btn-outline-secondary btn-sm rounded-pill">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel History -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Invoice</th>
                            <th>Customer</th>
                            <th>Tanggal</th>
                            <th>Metode Bayar</th>
                            <th class="text-end">Grand Total</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($rows) === 0): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Belum ada transaksi yang cocok dengan filter ini.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; ?>
                            <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td class="ps-4"><?= $no++ ?></td>
                                    <td class="fw-semibold"><?= htmlspecialchars($row['invoice_number']) ?></td>
                                    <td><?= htmlspecialchars($row['customer_name'] ?? '-') ?></td>
                                    <td><?= date('d M Y', strtotime($row['transaction_date'])) ?></td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?= htmlspecialchars($row['payment_method'] ?? '-') ?>
                                        </span>
                                    </td>
                                    <td class="text-end fw-bold">
                                        Rp <?= number_format((float)$row['grand_total'], 0, ',', '.') ?>
                                    </td>
                                    <td class="text-center pe-4">

                                        <a href="../modules/transaction_success/index.php?id=<?= $row['id'] ?>"
                                           class="btn btn-outline-secondary btn-sm rounded-3" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <?php if ($type !== 'all' && isset($docConfig[$type])): ?>
                                            <?php $doc = $docConfig[$type]; ?>

                                            <a href="../<?= $doc['folder'] ?>/<?= $doc['print'] ?>?id=<?= $row['id'] ?>"
                                               target="_blank"
                                               class="btn btn-outline-primary btn-sm rounded-3" title="Print <?= htmlspecialchars($doc['label']) ?>">
                                                <i class="bi bi-printer"></i>
                                            </a>

                                            <a href="../<?= $doc['folder'] ?>/<?= $doc['pdf'] ?>?id=<?= $row['id'] ?>"
                                               target="_blank"
                                               class="btn btn-outline-success btn-sm rounded-3" title="Export PDF <?= htmlspecialchars($doc['label']) ?>">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>

                                        <?php endif; ?>

                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<?php include '../includes/footer.php'; ?>
