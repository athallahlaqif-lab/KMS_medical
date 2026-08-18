<?php

declare(strict_types=1);

require_once '../config/session.php';

requireLogin();

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

include '../includes/header.php';
?>

<div class="container py-5">

    <div class="card shadow border-0 rounded-4">

        <div class="card-body text-center p-5">

            <i class="bi bi-check-circle-fill text-success" style="font-size:70px;"></i>

            <h2 class="mt-3">Stock Out Berhasil Disimpan</h2>

            <p class="text-muted">
                Pilih dokumen yang ingin dicetak.
            </p>

            <div class="d-grid gap-3 col-md-5 mx-auto mt-4">

                <a href="../modules/invoices/invoice_print.php?id=<?= $id ?>"
                   class="btn btn-primary">
                    🧾 Print Invoice
                </a>

                <a href="../modules/delivery_order/do_print.php?id=<?= $id ?>"
                   class="btn btn-success">
                    📦 Print Delivery Order
                </a>

                <a href="../modules/surat_jalan/sj_print.php?id=<?= $id ?>"
                   class="btn btn-warning">
                    🚚 Print Surat Jalan
                </a>

                <a href="index.php"
                   class="btn btn-secondary">
                    ⬅ Kembali ke Stock Out
                </a>

            </div>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>