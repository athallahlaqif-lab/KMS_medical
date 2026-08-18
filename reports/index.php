<?php

declare(strict_types=1);

$pageTitle = 'Reports';
$pageIcon  = 'bi bi-file-earmark-bar-graph';

require_once '../config/session.php';
require_once '../config/database.php';
require_once 'report_data.php';

requireLogin();

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

                            <i class="bi bi-file-earmark-bar-graph me-2 text-primary"></i>

                            Reports Dashboard

                        </h3>

                        <p class="text-muted mb-0">

                            Ringkasan laporan sistem inventory.

                        </p>

                    </div>

                </div>

                <form method="GET" class="row g-3 mb-4">

                    <div class="col-md-4">

                        <label class="form-label">

                            Start Date

                        </label>

                        <input
                            type="date"
                            name="start"
                            class="form-control"
                            value="<?= htmlspecialchars($start) ?>">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">

                            End Date

                        </label>

                        <input
                            type="date"
                            name="end"
                            class="form-control"
                            value="<?= htmlspecialchars($end) ?>">

                    </div>

                    <div class="col-md-4 d-flex align-items-end">

                        <button class="btn btn-primary me-2">

                            <i class="bi bi-search"></i>

                            Filter

                        </button>

                        <a href="index.php" class="btn btn-secondary">

                            Reset

                        </a>

                    </div>

                </form>

                <div class="row">

                    <div class="col-md-4 mb-4">

                        <div class="card border-0 shadow">

                            <div class="card-body">

                                <h6 class="text-muted">

                                    Total Products

                                </h6>

                                <h2 class="fw-bold text-primary">

                                    <?= $totalProducts ?>

                                </h2>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 mb-4">

                        <div class="card border-0 shadow">

                            <div class="card-body">

                                <h6 class="text-muted">

                                    Total Stock In

                                </h6>

                                <h2 class="fw-bold text-success">

                                    <?= $totalStockIn ?>

                                </h2>

                            </div>

                        </div>

                    </div>

                    <div class="col-md-4 mb-4">

                        <div class="card border-0 shadow">

                            <div class="card-body">

                                <h6 class="text-muted">

                                    Total Stock Out

                                </h6>

                                <h2 class="fw-bold text-danger">

                                    <?= $totalStockOut ?>

                                </h2>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="card shadow border-0">

                    <div class="card-body">

                        <h5 class="fw-bold mb-4">

                            Report Menu

                        </h5>

                        <div class="row">

                            <div class="col-md-4 mb-3">

                                <a
                                    href="products_report.php"
                                    class="btn btn-outline-primary w-100">

                                    <i class="bi bi-box-seam me-2"></i>

                                    Products Report

                                </a>

                            </div>

                            <div class="col-md-4 mb-3">

                                <a
                                    href="stockin_report.php"
                                    class="btn btn-outline-success w-100">

                                    <i class="bi bi-box-arrow-in-down me-2"></i>

                                    Stock In Report

                                </a>

                            </div>

                            <div class="col-md-4 mb-3">

                                <a
                                    href="stockout_report.php"
                                    class="btn btn-outline-danger w-100">

                                    <i class="bi bi-box-arrow-up me-2"></i>

                                    Stock Out Report

                                </a>

                            </div>

                            <div class="col-md-4 mb-3">

                                <a
                                    href="financial_report.php"
                                    class="btn btn-outline-success w-100">

                                    <i class="bi bi-cash-coin me-2"></i>

                                    Financial Report

                                </a>

                            </div>
                                                    </div>

                    </div>

                </div>

            </div>

            <?php include '../includes/footer.php'; ?>

        </div>

    </div>

</div>

<?php include '../includes/scripts.php'; ?>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const start = document.querySelector('[name="start"]');
    const end = document.querySelector('[name="end"]');

    if(start && end){

        end.addEventListener('change', function(){

            if(start.value !== '' && end.value !== ''){

                if(end.value < start.value){

                    Swal.fire({

                        icon: 'warning',

                        title: 'Tanggal Tidak Valid',

                        text: 'End Date tidak boleh lebih kecil dari Start Date.'

                    });

                    end.value = '';

                }

            }

        });

    }

});

</script>

</body>

</html>