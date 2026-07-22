<?php

declare(strict_types=1);

?>
<nav class="navbar navbar-expand-lg bg-white shadow-sm border-bottom px-4 py-3 sticky-top">
    <div class="container-fluid">
        <div>
            <h4 class="mb-1 fw-bold text-dark">
                <i class="bi <?= htmlspecialchars($pageIcon ?? 'bi-speedometer2') ?> me-2 text-primary"></i>
                <?= htmlspecialchars($pageTitle ?? 'Dashboard') ?>
            </h4>
            <div class="text-muted small">
                👋 Selamat Datang,
                <strong>
                    <?= htmlspecialchars($_SESSION['fullname'] ?? 'User') ?>
                </strong>
            </div>
            <div class="small text-secondary mt-1" id="datetime"></div>
        </div>

        <div class="ms-auto">
            <div class="dropdown">
                <button class="btn profile-btn dropdown-toggle d-flex align-items-center bg-transparent border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle fs-3 me-2 text-primary"></i>
                    <div class="text-start me-2">
                        <div class="fw-semibold lh-1">
                            <?= htmlspecialchars($_SESSION['fullname'] ?? 'User') ?>
                        </div>
                        <small class="text-muted">
                            <?= htmlspecialchars($_SESSION['role'] ?? 'Guest') ?>
                        </small>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2">
                    <li>
                        <h6 class="dropdown-header">
                            <?= htmlspecialchars($_SESSION['fullname'] ?? 'User') ?>
                        </h6>
                    </li>
                    <li>
                        <span class="dropdown-item-text text-muted small">
                            Role: <?= htmlspecialchars($_SESSION['role'] ?? 'Guest') ?>
                        </span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <a class="dropdown-item text-danger rounded-2" href="<?= BASE_URL ?>auth/logout.php">
                            <i class="bi bi-box-arrow-right me-2"></i>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateDateTime() {
        const datetimeElem = document.getElementById('datetime');
        if (!datetimeElem) return;

        const now = new Date();
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };

        const date = now.toLocaleDateString('id-ID', options);
        const time = now.toLocaleTimeString('id-ID');

        datetimeElem.innerHTML = "📅 " + date + " | 🕒 " + time + " WIB";
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
});
</script>