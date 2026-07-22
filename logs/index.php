<?php
declare(strict_types=1);

$pageTitle = 'Activity Logs';
$pageIcon  = 'bi-clock-history';

require_once '../config/session.php';
require_once '../config/database.php';

requireLogin();

// Ambil 50 catatan aktivitas terbaru
$stmt = $pdo->query("SELECT * FROM activity_logs ORDER BY created_at DESC LIMIT 50");
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../includes/header.php';
?>

<div class="wrapper">
    <?php include '../includes/sidebar.php'; ?>

    <div class="main">
        <?php include '../includes/navbar.php'; ?>

        <div class="content p-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h3 class="fw-bold mb-1">
                                <i class="bi bi-clock-history text-primary me-2"></i>Activity Logs
                            </h3>
                            <p class="text-muted mb-0">
                                Catatan riwayat aktivitas pengguna di dalam sistem.
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="60" class="text-center">No</th>
                                    <th>Pengguna</th>
                                    <th>Aksi</th>
                                    <th>Keterangan</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($logs) > 0): ?>
                                    <?php $no = 1; foreach ($logs as $log): ?>
                                    <tr>
                                        <td class="text-center fw-semibold"><?= $no++; ?></td>
                                        <td>
                                            <strong class="text-dark"><?= htmlspecialchars($log['user_name']); ?></strong>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = 'bg-secondary';
                                            if ($log['action'] === 'CREATE') $badgeClass = 'bg-success';
                                            elseif ($log['action'] === 'UPDATE') $badgeClass = 'bg-warning text-dark';
                                            elseif ($log['action'] === 'DELETE') $badgeClass = 'bg-danger';
                                            elseif ($log['action'] === 'LOGIN') $badgeClass = 'bg-info text-dark';
                                            ?>
                                            <span class="badge <?= $badgeClass ?> rounded-pill px-3 py-2">
                                                <?= htmlspecialchars($log['action']); ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($log['description']); ?></td>
                                        <td class="text-muted small">
                                            <?= date('d M Y H:i:s', strtotime($log['created_at'])); ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                            Belum ada catatan aktivitas.
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
</body>
</html>