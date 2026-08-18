<!DOCTYPE html>

<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - <?= htmlspecialchars($invoice_number) ?></title>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
    }

    .invoice-card {
        width: 210mm;
        padding: 20mm;
        margin: 20px auto;
    box-sizing: border-box;
        background: #ffffff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* Batasan ukuran logo agar presisi */
    .company-logo {
        max-width: 90px;
        max-height: 90px;
        object-fit: contain;
        margin-bottom: 8px;
    }

    .table-invoice th {
        background-color: #f1f3f5 !important;
        color: #333;
        font-weight: 600;
    }

    .signature-space {
        height: 60px;
    }

    /* ------------------------------------------------------------------
       DOMPDF COMPATIBILITY FIX
       ------------------------------------------------------------------
       Dompdf (untuk Export PDF) tidak mendukung flexbox dari grid
       Bootstrap 5 (.row/.col-*) dengan baik, sehingga konten melebar
       keluar halaman dan terpotong saat di-export ke PDF. Override di
       bawah ini mengganti grid jadi berbasis "table", yang didukung
       penuh baik di browser maupun Dompdf, tanpa perlu ubah HTML.
       ------------------------------------------------------------------ */
    .row {
        display: block !important;
        width: 100%;
        overflow: hidden; /* clearfix cara lama, tanpa ::after -
                              Dompdf tidak reliable dengan pseudo-element,
                              overflow:hidden lebih kompatibel untuk
                              membungkus float dengan tinggi yang benar */
    }
    .col-4, .col-6, .col-8, .col-md-5, .col-md-6, .col-md-7, .offset-md-3 {
        display: block !important;
        float: left !important;
        box-sizing: border-box !important;
        padding-left: 6px !important;
        padding-right: 6px !important;
    }
    .col-4  { width: 33.3333% !important; }
    .col-6  { width: 50% !important; }
    .col-8  { width: 66.6667% !important; }
    .col-md-5 { width: 41.6667% !important; }
    .col-md-6 { width: 50% !important; }
    .col-md-7 { width: 58.3333% !important; }

    /* Format Cetak / Print A4 */
    /* Warna Brand KMS */
.text-success{
    color:#2E7D32 !important;
}

.text-warning{
    color:#F57C00 !important;
}

.btn-success{
    background:#2E7D32;
    border-color:#2E7D32;
}

.btn-success:hover{
    background:#256428;
    border-color:#256428;
}

/* Card */
.card{
    border-radius:12px;
}

/* Table */
.table-invoice th{
    background:#2E7D32 !important;
    color:#fff !important;
}

.table-invoice td{
    vertical-align:middle;
}

/* Garis footer */
hr{
    border-top:2px solid #2E7D32;
}
.watermark{
    position:absolute;
    top:45%;
    left:50%;
    transform:translate(-50%,-50%) rotate(-30deg);

    font-size:90px;
    font-weight:bold;

    color:#198754;

    opacity:.08;

    pointer-events:none;

    z-index:0;
}

.invoice-card{
    position:relative;
}
        @media print {
            body {
                background: none !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .invoice-card {
                margin: 0 auto !important;
                box-shadow: none !important;
                width: 180mm !important;
                min-height: auto !important;
                padding: 8mm !important;
            }

            /* Mode ringkas khusus print/PDF - perkecil jarak antar bagian
               supaya 1 transaksi (walau isinya beberapa produk) tetap
               muat di 1 halaman A4 */
            .invoice-card h1,
            .invoice-card h2 {
                font-size: 20px !important;
            }
            .invoice-card .company-logo {
                max-width: 55px !important;
                max-height: 55px !important;
                margin-bottom: 4px !important;
            }
            .invoice-card p,
            .invoice-card small,
            .invoice-card td,
            .invoice-card th {
                font-size: 11px !important;
            }
            .invoice-card .row.border-bottom {
                padding-bottom: 8px !important;
                margin-bottom: 10px !important;
            }
            .invoice-card .card-body {
                padding: 8px 12px !important;
            }
            .invoice-card .mb-4 { margin-bottom: 8px !important; }
            .invoice-card .mb-3 { margin-bottom: 6px !important; }
            .invoice-card .mb-2 { margin-bottom: 4px !important; }
            .invoice-card .mt-4 { margin-top: 8px !important; }
            .invoice-card .mt-5 { margin-top: 16px !important; }
            .invoice-card .mb-5 { margin-bottom: 8px !important; }
            .invoice-card .my-5 { margin-top: 10px !important; margin-bottom: 10px !important; }
            .invoice-card .my-4 { margin-top: 8px !important; margin-bottom: 8px !important; }
            .invoice-card table td,
            .invoice-card table th {
                padding: 4px 6px !important;
            }
            .invoice-card .signature-space {
                height: 35px !important;
            }

                    }

        /* @page HARUS di top-level, tidak boleh nested di dalam @media, supaya benar-benar diterapkan oleh Dompdf */
        @page {
            size: A4 portrait;
            margin: 0;
        }
</style>

</head>

<body>

<div class="container text-center my-4 no-print">

    <?php include __DIR__ . '/../shared/back_button.php'; ?>

    <button onclick="window.print()"
        class="btn btn-success px-4 shadow-sm">
    🖨️ Print Invoice
</button>

</div>

<div class="invoice-card">

      <!-- HEADER -->
<table style="width:100%; border-bottom:1px solid #dee2e6; margin-bottom:16px;">
<tr>
    <td style="width:66.6667%; vertical-align:top; padding-bottom:12px;">

    <!-- Logo -->
    <img src="../../assets/images/logo-kms.png"
         class="company-logo mb-2"
         alt="Logo"
         onerror="this.style.display='none'">

    <h2 class="fw-bold text-success mb-0">
        Pt Karunia Medika Sejahtera
    </h2>


    <div class="text-warning fw-semibold mb-2">
        Ada dan Bermakna
    </div>

    <div class="small text-muted">

        Supplier Alat kesehatan dan Laboratorium

        <br>

        Jl.Sakura Blok F no 64 Perumnas Mulyasari Majenang

        <br>

        Cilacap - Indonesia

        <br>

        Telp : 0818282576

        <br>

        Email : karuniamedikasejahtera@gmail.com

    </div>
    </td>

    <td style="width:33.3333%; vertical-align:top; text-align:right; padding-bottom:12px;">

    <h1 class="fw-bold text-success">
        INVOICE
    </h1>

    <table class="table table-sm table-borderless">

        <tr>

            <td class="text-muted">
                Invoice
            </td>

            <td class="fw-bold">
                <?= htmlspecialchars($invoice_number) ?>
            </td>

        </tr>

        <tr>

            <td class="text-muted">
                Date
            </td>

            <td>

                <?= htmlspecialchars($invoice_date) ?>

            </td>

        </tr>

        <tr>

            <td class="text-muted">
                Status
            </td>

            <td>

                <span class="badge bg-success">

                    PAID

                </span>

            </td>

        </tr>

        <tr>

            <td class="text-muted">
                Metode Bayar
            </td>

            <td>
                <?= htmlspecialchars($payment_method) ?>
            </td>

        </tr>

    </table>

    </td>

</tr>
</table>


<!-- CUSTOMER SECTION -->

<div style="width:60%; margin-top:2px; margin-bottom:16px;">
        <div class="p-3 bg-light rounded-3 border shadow-sm">

        <h6 class="fw-bold border-bottom pb-2 mb-3 text-success">
            Bill To
        </h6>

        <h5 class="fw-bold mb-2">
            <?= htmlspecialchars($customer_name) ?>
        </h5>

        <p class="mb-2 text-muted">
            <?= nl2br(htmlspecialchars($address)) ?>
        </p>

        <p class="mb-1">
            <strong>Phone :</strong>
            <?= htmlspecialchars($phone) ?>
        </p>

        <p class="mb-0">
            <strong>Email :</strong>
            <?= htmlspecialchars($email) ?>
        </p>

    </div>
</div>
        <!-- DETAIL BARANG -->
<table class="table table-bordered align-middle table-invoice" style="margin: 8px 0 16px 0;">
                <thead>
    <tr>
        <th style="width:5%;" class="text-center">No</th>
        <th style="width:15%;" class="text-center">Kode Produk</th>
        <th style="width:35%;">Nama Produk</th>
        <th style="width:10%;" class="text-center">Qty</th>
        <th style="width:15%;" class="text-end">Harga</th>
        <th style="width:20%;" class="text-end">Subtotal</th>
    </tr>
</thead>
              <tbody>
    <?php foreach ($items as $i => $item): ?>
    <tr>
        <td class="text-center"><?= $i + 1 ?></td>

    <td class="text-center text-muted">
        <?= htmlspecialchars($item['product_code']) ?>
    </td>

    <td class="fw-semibold">
        <?= htmlspecialchars($item['product_name']) ?>
    </td>

    <td class="text-center">
        <?= htmlspecialchars((string)$item['qty']) ?>
    </td>

    <td class="text-end">
        Rp <?= number_format((float)$item['selling_price'], 0, ',', '.') ?>
    </td>

    <td class="text-end fw-bold">
        Rp <?= number_format((float)$item['subtotal'], 0, ',', '.') ?>
    </td>
</tr>
    <?php endforeach; ?>
</tbody>
            </table>

      <!-- TOTAL SECTION -->

<table style="width:100%; margin-top:16px;">
<tr>

<!-- Catatan -->
<td style="width:58.3333%; vertical-align:top; padding-right:8px;">
    <div style="border:1px solid #dee2e6; border-radius:6px; padding:12px;">
            <h6 class="fw-bold text-success mb-3">
                Catatan
            </h6>

            <p class="text-muted mb-0">
                Barang yang sudah dibeli tidak dapat dikembalikan tanpa
                perjanjian terlebih dahulu.
            </p>
    </div>
</td>

<!-- Payment Summary -->
<td style="width:41.6667%; vertical-align:top; padding-left:8px;">

    <div style="border:1px solid #dee2e6; border-radius:6px; padding:12px;">

            <h6 class="fw-bold text-success mb-3">
                Payment Summary
            </h6>

            <div style="padding:4px 0;">
                <span>Subtotal</span>
                <span style="float:right;">
                    Rp <?= number_format($subtotal,0,',','.') ?>
                </span>
            </div>

            <div style="padding:4px 0;">
                <span>PPN 11%</span>
                <span style="float:right;">
                    Rp <?= number_format($ppn,0,',','.') ?>
                </span>
            </div>

            <div style="padding:6px 0 0 0; border-top:1px solid #dee2e6; font-weight:bold;">
                <span>Grand Total</span>
                <span style="float:right; color:#F57C00;">
                    Rp <?= number_format($grand_total,0,',','.') ?>
                </span>
            </div>

    </div>

</td>

</tr>
</table>
        <!-- SIGNATURE -->
<table style="width:100%; margin-top:16px;">
<tr>

<td style="width:50%; text-align:center; vertical-align:top;">

    <p class="fw-semibold mb-5">
        Penerima
    </p>

    <br>

    <div class="border-top pt-2 mx-auto" style="width:200px;">
        ( __________________ )
    </div>

</td>

<td style="width:50%; text-align:center; vertical-align:top;">

    <p class="fw-semibold mb-5">
        Admin KMS Medical
    </p>

    <?php if (!empty($signature_path)): ?>
        <img src="../../<?= htmlspecialchars($signature_path) ?>"
             alt="Tanda Tangan"
             style="max-height:60px; max-width:180px;">
    <?php else: ?>
        <br>
    <?php endif; ?>

    <div class="border-top pt-2 mx-auto" style="width:200px;">
        ( __________________ )
    </div>

</td>

</tr>
</table>

<hr class="my-5">

<!-- INFO PEMBAYARAN -->
<div style="border:1px solid #dee2e6; border-radius:6px; padding:10px 14px; margin-bottom:16px;">
    <span class="fw-bold text-success">Info Pembayaran:</span>
    <?= htmlspecialchars(COMPANY_BANK_NAME) ?> -
    <?= htmlspecialchars(COMPANY_BANK_ACCOUNT_NUMBER) ?>
    a.n. <?= htmlspecialchars(COMPANY_BANK_ACCOUNT_NAME) ?>
</div>

<!-- FOOTER -->

<div class="text-center">

<h6 class="fw-bold text-success mb-1">
    KMS Medical
</h6>

<p class="text-warning fw-semibold mb-2">
    Ada dan Bermakna
</p>

<p class="text-muted small mb-0">
    Terima kasih atas kepercayaan Anda.
</p>

<p class="text-muted small">
    Semoga KMS Medical dapat terus menjadi partner terbaik Anda.
</p>

</div>

<!-- Footer Sistem -->
<div class="text-end small text-muted mt-3 no-print">
    Generated by KMS Medical System
</div>

<div class="watermark">
    PAID
</div>

</div>

<script>
    window.onload = function () {
        document.title = "Invoice - <?= $invoice_number ?>";
    };
</script>

</body>
</html>