<?php
declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

$error = '';

// Ambil daftar produk
$stmt = $pdo->query("
    SELECT id, product_code, product_name, stock, selling_price, purchase_price
    FROM products
    ORDER BY product_name ASC
");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
/*
|--------------------------------------------------------------------------
| Ambil daftar customer
|--------------------------------------------------------------------------
*/

$stmt = $pdo->query("
    SELECT id, customer_code, customer_name
    FROM customers
    ORDER BY customer_name ASC
");

$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);



$pageTitle = 'Add Stock Out';
include '../includes/header.php';
?>

<div class="container-fluid px-4 py-3">

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 text-danger">
                <i class="bi bi-box-arrow-up me-2"></i>
                Add Stock Out
            </h4>
            <p class="text-muted small mb-0">
                Tambah transaksi barang keluar. Bisa lebih dari 1 produk dalam satu transaksi.
            </p>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card shadow-sm border-0 rounded-4">

        <div class="card-body">

            <form action="store.php" method="POST" id="stockOutForm">

                <div class="row g-3">
                    <div class="col-md-5">

                        <label class="form-label">Customer</label>

                        <select
                            name="customer_id"
                            class="form-select"
                            required>

                            <option value="">
                                -- Select Customer --
                            </option>

                            <?php foreach ($customers as $c): ?>

                                <option value="<?= $c['id']; ?>">

                                    <?= htmlspecialchars($c['customer_code']); ?>

                                    -

                                    <?= htmlspecialchars($c['customer_name']); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label class="form-label">Metode Pembayaran</label>

                        <select
                            name="payment_method"
                            class="form-select"
                            required>

                            <option value="Transfer">Transfer</option>
                            <option value="COD">COD</option>
                            <option value="CBD">CBD</option>

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label class="form-label">Transaction Date</label>

                        <input
                            type="date"
                            name="transaction_date"
                            value="<?= date('Y-m-d') ?>"
                            class="form-control">

                    </div>

                    <div class="col-md-2">

                        <label class="form-label">Note</label>

                        <input
                            type="text"
                            name="note"
                            class="form-control">

                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex align-items-center justify-content-between mb-2">
                    <label class="form-label mb-0 fw-semibold">Daftar Produk</label>
                    <button type="button" id="btnAddRow" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Produk
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle" id="productTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 45%">Product</th>
                                <th style="width: 20%">Qty</th>
                                <th style="width: 25%">Stock Tersedia</th>
                                <th style="width: 10%"></th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody">
                            <!-- baris produk akan ditambahkan di sini oleh JavaScript -->
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-between">

                    <a href="index.php"
                       class="btn btn-secondary">
                        Back
                    </a>

                    <button class="btn btn-danger" type="submit">
                        Save Stock Out
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
// Data produk dari PHP, dipakai JavaScript untuk isi dropdown & cek stock
const PRODUCTS = <?= json_encode(array_map(function ($p) {
    return [
        'id' => (int)$p['id'],
        'code' => $p['product_code'],
        'name' => $p['product_name'],
        'stock' => (int)$p['stock'],
    ];
}, $products)) ?>;

let rowIndex = 0;

function buildProductOptions(selectedId = '') {
    let html = '<option value="">-- Select Product --</option>';
    PRODUCTS.forEach(function (p) {
        const sel = (String(p.id) === String(selectedId)) ? 'selected' : '';
        html += `<option value="${p.id}" data-stock="${p.stock}" ${sel}>${p.code} - ${p.name} (Stock: ${p.stock})</option>`;
    });
    return html;
}

function addProductRow() {
    rowIndex++;
    const tbody = document.getElementById('productTableBody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td>
            <select name="product_id[]" class="form-select product-select" required>
                ${buildProductOptions()}
            </select>
        </td>
        <td>
            <input type="number" name="qty[]" class="form-control qty-input" min="1" required>
        </td>
        <td>
            <span class="stock-info text-muted small">-</span>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-row">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}

document.getElementById('btnAddRow').addEventListener('click', addProductRow);

document.getElementById('productTableBody').addEventListener('change', function (e) {
    if (e.target.classList.contains('product-select')) {
        const tr = e.target.closest('tr');
        const selectedOption = e.target.options[e.target.selectedIndex];
        const stock = selectedOption ? (selectedOption.getAttribute('data-stock') || '-') : '-';
        tr.querySelector('.stock-info').textContent = stock !== '-' ? stock + ' unit' : '-';
        const qtyInput = tr.querySelector('.qty-input');
        if (stock !== '-') {
            qtyInput.setAttribute('max', stock);
        }
    }
});

document.getElementById('productTableBody').addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-remove-row');
    if (btn) {
        const tbody = document.getElementById('productTableBody');
        // Minimal harus ada 1 baris produk
        if (tbody.querySelectorAll('tr').length > 1) {
            btn.closest('tr').remove();
        }
    }
});

// Mulai dengan 1 baris produk
addProductRow();

document.getElementById('stockOutForm').addEventListener('submit', function (e) {
    const tbody = document.getElementById('productTableBody');
    if (tbody.querySelectorAll('tr').length === 0) {
        e.preventDefault();
        alert('Tambahkan minimal 1 produk.');
    }
});
</script>

<?php include '../includes/footer.php'; ?>
