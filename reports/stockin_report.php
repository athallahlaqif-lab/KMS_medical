<?php

declare(strict_types=1);

$pageTitle = 'Stock In Report';
$pageIcon  = 'bi bi-box-arrow-in-down';

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

$search = trim($_GET['search'] ?? '');

$sql = "
SELECT
    si.id,
    si.qty,
    si.transaction_date,
    si.note,
    p.product_code,
    p.product_name
FROM stock_in si
INNER JOIN products p
    ON si.product_id = p.id
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

$sql .= " ORDER BY si.transaction_date DESC";

$stmt = $pdo->prepare($sql);

$stmt->execute($params);

$stockIn = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

<i class="bi bi-box-arrow-in-down text-success me-2"></i>

Stock In Report

</h3>

<p class="text-muted mb-0">

Laporan seluruh transaksi barang masuk.

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

<a href="stockin_report.php" class="btn btn-secondary">

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

<th>Qty</th>

<th>Date</th>

<th>Note</th>

</tr>

</thead>

<tbody>

<?php if(count($stockIn)>0): ?>

<?php $no=1; ?>

<?php foreach($stockIn as $row): ?>

<tr>

<td><?= $no++; ?></td>

<td><?= htmlspecialchars($row['product_code']); ?></td>

<td><?= htmlspecialchars($row['product_name']); ?></td>

<td>

<span class="badge bg-success">

<?= $row['qty']; ?>

</span>

</td>

<td><?= date('d M Y',strtotime($row['transaction_date'])); ?></td>

<td><?= htmlspecialchars($row['note'] ?: '-'); ?></td>

</tr>

<?php endforeach; ?>

<?php else: ?>

<tr>

<td colspan="6" class="text-center">

Belum ada data.

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

            badge.classList.remove('bg-success');

            badge.classList.add('bg-primary');

        }

    });

});

</script>

</body>

</html>