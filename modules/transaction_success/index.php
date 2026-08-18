<?php

declare(strict_types=1);

require_once '../../config/session.php';
require_once '../../config/database.php';

requireLogin();

$transactionId = (int)($_GET['id'] ?? 0);

if ($transactionId <= 0) {
    header('Location: ../../stock_out/index.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT
        t.id,
        t.invoice_number,
        t.grand_total,
        t.transaction_date,
        t.signature_path,
        c.customer_name
    FROM transactions t
    LEFT JOIN customers c
        ON t.customer_id = c.id
    WHERE t.id = ?
");

$stmt->execute([$transactionId]);

$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaction) {
    die('Transaction not found.');
}

$pageTitle = 'Transaction Success';

include '../../includes/header.php';
?>

<div class="container py-5">

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card shadow border-0 rounded-4">

        <div class="card-body text-center p-5">

            <i class="bi bi-check-circle-fill text-success"
               style="font-size:70px;"></i>

            <h2 class="mt-3 fw-bold">
                Transaction Completed
            </h2>

            <p class="text-muted">
                Semua dokumen siap dicetak.
            </p>

            <hr>

            <div class="mb-4">

                <h5>
                    <?= htmlspecialchars($transaction['invoice_number']) ?>
                </h5>

                <p class="mb-1">
                    Customer :
                    <strong>
                        <?= htmlspecialchars($transaction['customer_name'] ?? '-') ?>
                    </strong>
                </p>

                <p>
                    Total :
                    <strong>
                        Rp <?= number_format((float)$transaction['grand_total'], 0, ',', '.') ?>
                    </strong>
                </p>

            </div>

            <!-- TANDA TANGAN DIGITAL -->
            <div class="mb-4 text-start">

                <h6 class="fw-bold">
                    Tanda Tangan Digital (Admin)
                </h6>

                <p class="text-muted small mb-2">
                    Upload gambar tanda tangan untuk transaksi ini. Akan otomatis muncul
                    di sisi "Admin KMS Medical" pada semua dokumen (Invoice, DO, Surat
                    Jalan, Tax Invoice).
                </p>

                <?php if (!empty($transaction['signature_path'])): ?>
                    <div class="mb-2">
                        <img src="../../<?= htmlspecialchars($transaction['signature_path']) ?>"
                             alt="Tanda tangan"
                             style="max-height:80px; border:1px solid #dee2e6; border-radius:6px; padding:4px;">
                    </div>
                <?php endif; ?>

                <form action="upload_signature.php"
                      method="POST"
                      enctype="multipart/form-data"
                      class="d-flex gap-2 align-items-center">

                    <input type="hidden" name="transaction_id" value="<?= $transactionId ?>">

                    <input type="file"
                           name="signature"
                           accept="image/png, image/jpeg"
                           class="form-control form-control-sm"
                           style="max-width:280px;"
                           required>

                    <button type="submit" class="btn btn-sm btn-outline-success">
                        <?= !empty($transaction['signature_path']) ? 'Ganti' : 'Upload' ?>
                    </button>

                </form>

            </div>
           <div class="row g-3 mt-3">

    <!-- Invoice -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5>📄 Invoice</h5>
                <p class="text-muted mb-3">
                    Invoice penjualan customer
                </p>

                <a href="../invoices/invoice_print.php?id=<?= $transactionId ?>"
                   target="_blank"
                   class="btn btn-primary w-100 mb-2">
                    🖨 Print Invoice
                </a>

                <a href="../invoices/invoice_pdf.php?id=<?= $transactionId ?>"
                   class="btn btn-outline-danger w-100">
                    📥 Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Delivery Order -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5>📦 Delivery Order</h5>
                <p class="text-muted mb-3">
                    Dokumen pengiriman barang
                </p>

                <a href="../delivery_order/do_print.php?id=<?= $transactionId ?>"
                   target="_blank"
                   class="btn btn-success w-100 mb-2">
                    🖨 Print DO
                </a>

                <a href="../delivery_order/do_pdf.php?id=<?= $transactionId ?>"
                   class="btn btn-outline-danger w-100">
                    📥 Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Surat Jalan -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5>🚚 Surat Jalan</h5>
                <p class="text-muted mb-3">
                    Dokumen serah terima barang
                </p>

                <a href="../surat_jalan/sj_print.php?id=<?= $transactionId ?>"
                   target="_blank"
                   class="btn btn-warning w-100 mb-2">
                    🖨 Print Surat Jalan
                </a>

                <a href="../surat_jalan/sj_pdf.php?id=<?= $transactionId ?>"
                   class="btn btn-outline-danger w-100">
                    📥 Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Tax Internal -->
    <div class="col-md-6">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5>🧾 Tax Invoice Internal</h5>
                <p class="text-muted mb-3">
                    Faktur pajak internal perusahaan
                </p>

                <a href="../tax_invoice_internal/tax_internal_print.php?id=<?= $transactionId ?>"
                   target="_blank"
                   class="btn btn-dark w-100 mb-2">
                    🖨 Print Tax Internal
                </a>

                <a href="../tax_invoice_internal/tax_internal_pdf.php?id=<?= $transactionId ?>"
                   class="btn btn-outline-danger w-100">
                    📥 Export PDF
                </a>
            </div>
        </div>
    </div>

    <!-- Tax External -->
    <div class="col-md-6 offset-md-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5>🧾 Tax Invoice External</h5>
                <p class="text-muted mb-3">
                    Faktur pajak customer
                </p>

                <a href="../tax_invoice_external/external_print.php?id=<?= $transactionId ?>"
                   target="_blank"
                   class="btn btn-info w-100 mb-2">
                    🖨 Print Tax External
                </a>

                <a href="../tax_invoice_external/external_pdf.php?id=<?= $transactionId ?>"
                   class="btn btn-outline-danger w-100">
                    📥 Export PDF
                </a>
            </div>
        </div>
    </div>

</div>

<div class="text-center mt-5">

    <a href="../../stock_out/index.php"
       class="btn btn-secondary btn-lg px-5">

        ← Back to Stock Out

    </a>

</div>

        </div>
        <!-- /.card-body -->

    </div>
    <!-- /.card -->

</div>
<!-- /.container -->
