<?php

declare(strict_types=1);

/*
|--------------------------------------------------------------------------
| Shared Back Button Component
|--------------------------------------------------------------------------
| Dipakai oleh semua halaman print (invoice, delivery order, surat jalan,
| tax invoice internal, tax invoice external).
|
| Default: kembali ke modules/transaction_success/index.php dengan
| membawa $transactionId (kalau tersedia di scope pemanggil).
|
| Bisa di-override per halaman dengan set $backUrl sebelum include file ini.
|--------------------------------------------------------------------------
*/

if (!isset($backUrl)) {
    $backUrl = '../transaction_success/index.php';

    if (isset($transactionId) && (int)$transactionId > 0) {
        $backUrl .= '?id=' . (int)$transactionId;
    }
}
?>
<a href="<?= htmlspecialchars($backUrl, ENT_QUOTES) ?>"
   class="btn btn-secondary px-4 shadow-sm no-print">
    ← Kembali
</a>
