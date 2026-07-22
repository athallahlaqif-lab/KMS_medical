<?php

declare(strict_types=1);

$pageTitle = 'Stock Out Report';
$pageIcon  = 'bi bi-box-arrow-up';

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

$search = trim($_GET['search'] ?? '');

$sql = "
SELECT
    so.id,
    so.qty,
    so.stock_before,
    so.stock_after,
    so.transaction_date,
    so.note,
    p.product_code,
    p.product_name
FROM stock_out so
INNER JOIN products p
    ON so.product_id = p.id
";

$params = [];

if ($search !== '') {

    $sql .= "
    WHERE
        p.product_name LIKE :search
        OR p.product_code LIKE :search
    ";

    $params['search'] = "%{$search}%";
}

$sql .= " ORDER BY so.transaction_date DESC";

$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$stockOut = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="wrapper">

<?php include '../includes/sidebar.php'; ?>

<div class="main">

<?php include '../includes/navbar.php'; ?>

<div class="content">

<div class="card shadow border-0">

<div class="card-body">

<div class="d-flex justify-content-between align-items-center mb-4">

<div>

<h3 class="fw-bold">

<i class="bi bi-box-arrow-up text-danger me-2"></i>

Stock Out Report

</h3>

<p class="text-muted mb-0">

Laporan seluruh transaksi barang keluar.

</p>

</div>

<a href="index.php" class="btn btn-secondary">

<i class="bi bi-arrow-left"></i>

Back

</a>

</div>

<form method="GET" class="row mb-4">

<div class="col-md-5">

<input
type="text"
name="search"
class="form-control"
placeholder="Cari produk..."
value="<?= htmlspecialchars($search) ?>">

</div>

<div class="col-md-3">

<button class="btn btn-primary">

<i class="bi bi-search"></i>

Search

</button>

<a href="stockout_report.php" class="btn btn-secondary">

Reset

</a>

</div>

</form>

<div class="table-responsive">

<table class="table table-bordered table-hover align-middle">

<thead class="table-dark">

<tr>

<th width="60">No</th>

<th>Product Code</th>

<th>Product Name</th>

<th>Qty Out</th>

<th>Stock Before</th>

<th>Stock After</th>

<th>Date</th>

<th>Note</th>

</tr>

</thead>

<tbody>

<?php if(count($stockOut) > 0): ?>

<?php $no = 1; ?>

<?php foreach($stockOut as $row): ?>

<tr>

<td><?= $no++; ?></td>

<td><?= htmlspecialchars($row['product_code']); ?></td>

<td><?= htmlspecialchars($row['product_name']); ?></td>

<td>

<span class="badge bg-danger">

<?= $row['qty']; ?>

</span>

</td>

<td><?= $row['stock_before']; ?></td>

<td><?= $row['stock_after']; ?></td>

<td><?= date('d M Y', strtotime($row['transaction_date'])); ?></td>

<td><?= htmlspecialchars($row['note'] ?: '-'); ?></td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="8" class="text-center">

Belum ada data transaksi.

</td>

</tr>

<?php endif; ?>
                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

            <?php include '../includes/footer.php'; ?>

        </div>

    </div>

</div>

<?php include '../includes/scripts.php'; ?>

<script>

document.addEventListener('DOMContentLoaded', function(){

    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(function(row){

        const badge = row.querySelector('.badge');

        if(!badge) return;

        const qty = parseInt(badge.textContent);

        if(qty >= 100){

            badge.classList.remove('bg-danger');

            badge.classList.add('bg-dark');

        }

    });

});

</script>

</body>

</html>