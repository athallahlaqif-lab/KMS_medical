<?php
declare(strict_types=1);

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

$error = '';

// Ambil daftar produk untuk dropdown (menggunakan product_name)
$stmt = $pdo->query("SELECT id, product_code, product_name, stock FROM products ORDER BY product_name ASC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId       = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $qty             = isset($_POST['qty']) ? (int)$_POST['qty'] : 0;
    $transactionDate = $_POST['transaction_date'] ?? date('Y-m-d');
    $note            = trim($_POST['note'] ?? '');

    if ($productId <= 0 || $qty <= 0) {
        $error = 'Pilih produk dan masukkan jumlah qty yang valid!';
    } else {
        try {
            $pdo->beginTransaction();

            // Ambil stok saat ini
            $stmtProd = $pdo->prepare("SELECT stock FROM products WHERE id = ? FOR UPDATE");
            $stmtProd->execute([$productId]);
            $prod = $stmtProd->fetch(PDO::FETCH_ASSOC);

            if (!$prod) {
                throw new Exception("Produk tidak ditemukan.");
            }

            $stockBefore = (int)$prod['stock'];

            if ($qty > $stockBefore) {
                throw new Exception("Stok tidak mencukupi! Stok saat ini: {$stockBefore}");
            }

            $stockAfter = $stockBefore - $qty;

            // Insert ke stock_out
            $stmtOut = $pdo->prepare("
                INSERT INTO stock_out (product_id, qty, stock_before, stock_after, transaction_date, note)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmtOut->execute([$productId, $qty, $stockBefore, $stockAfter, $transactionDate, $note]);

            // Update stok produk
            $stmtUp = $pdo->prepare("UPDATE products SET stock = ? WHERE id = ?");
            $stmtUp->execute([$stockAfter, $productId]);

            $pdo->commit();
            header("Location: index.php?success=1");
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = $e->getMessage();
        }
    }
}

$pageTitle = 'Add Stock Out';
include '../includes/header.php';
?>

<div class="container-fluid px-4 py-3">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-1 text-danger"><i class="bi bi-box-arrow-up me-2"></i>Add Stock Out</h4>
            <p class="text-muted small mb-0">Tambah transaksi barang keluar KMS Medical.</p>
        </div>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger rounded-3 shadow-sm"><?= htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Product</label>
                        <select name="product_id" id="product_id" class="form-select rounded-pill" required>
                            <option value="">-- Select Product --</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p['id']; ?>" data-stock="<?= $p['stock']; ?>">
                                    <?= htmlspecialchars($p['product_code']); ?> - <?= htmlspecialchars($p['product_name']); ?> (Stok: <?= $p['stock']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Quantity Out</label>
                        <input type="number" name="qty" class="form-control rounded-pill" min="1" placeholder="Masukkan Qty Keluar" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Transaction Date</label>
                        <input type="date" name="transaction_date" class="form-control rounded-pill" value="<?= date('Y-m-d'); ?>" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label font-weight-bold">Note</label>
                        <input type="text" name="note" class="form-control rounded-pill" placeholder="Masukkan keterangan (opsional)">
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="index.php" class="btn btn-secondary rounded-pill px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </a>
                    <button type="submit" class="btn btn-danger rounded-pill px-4">
                        <i class="bi bi-check-circle me-1"></i> Save Stock Out
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>