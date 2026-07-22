<?php

declare(strict_types=1);

require_once '../config/database.php';
require_once '../config/session.php';

if (isLoggedIn()) {
    header('Location: ../dashboard/index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - KMS Medical</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/login.css">

</head>

<body>

<div class="login-bg">

    <div class="overlay"></div>

    <div class="container">

        <div class="row justify-content-center align-items-center vh-100">

            <div class="col-lg-10">

                <div class="login-card">

                    <div class="row g-0">

                        <!-- Left Side -->

                        <div class="col-lg-6 login-left">

                            <div>

                                <i class="bi bi-hospital-fill display-1 text-white mb-4"></i>

                                <h1 class="fw-bold text-white">

                                    KMS Medical

                                </h1>

                                <p class="text-light fs-5 mt-3">

                                    Medical Product & Price Management System

                                </p>

                                <hr class="border-light opacity-50 my-4">

                                <p class="text-light">

                                    Sistem manajemen inventaris produk kesehatan
                                    yang cepat, aman, dan mudah digunakan.

                                </p>

                            </div>

                        </div>

                        <!-- Right Side -->

                        <div class="col-lg-6 login-right">

                            <div class="p-5">

                                <h2 class="fw-bold mb-2">

                                    Welcome Back 👋

                                </h2>

                                <p class="text-muted mb-4">

                                    Silakan login untuk melanjutkan.

                                </p>

                                <form action="process_login.php" method="POST">

                    <form action="process_login.php" method="POST">

                                                     <div class="mb-3">

                                    <label class="form-label fw-semibold">

                                        <i class="bi bi-person-fill me-2"></i>

                                        Username

                                    </label>

                                    <input
                                        type="text"
                                        name="username"
                                        class="form-control form-control-lg"
                                        placeholder="Masukkan username"
                                        required>

                                </div>

                                <div class="mb-4">

                                    <label class="form-label fw-semibold">

                                        <i class="bi bi-lock-fill me-2"></i>

                                        Password

                                    </label>

                                    <div class="input-group">

                                        <input
                                            type="password"
                                            name="password"
                                            id="password"
                                            class="form-control form-control-lg"
                                            placeholder="Masukkan password"
                                            required>

                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary"
                                            onclick="togglePassword()">

                                            <i
                                                class="bi bi-eye"
                                                id="eyeIcon">
                                            </i>

                                        </button>

                                    </div>

                                </div>

                                <button
                                    type="submit"
                                    class="btn btn-primary btn-lg w-100">

                                    <i class="bi bi-box-arrow-in-right me-2"></i>

                                    Login

                                </button>

                            </form>

                            <div class="text-center mt-4">

                                <small class="text-muted">

                                    © <?= date('Y'); ?>

                                    KMS Medical System

                                </small>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

function togglePassword(){

    const password = document.getElementById('password');

    const eye = document.getElementById('eyeIcon');

    if(password.type === 'password'){

        password.type = 'text';

        eye.classList.remove('bi-eye');

        eye.classList.add('bi-eye-slash');

    }else{

        password.type = 'password';

        eye.classList.remove('bi-eye-slash');

        eye.classList.add('bi-eye');

    }

}

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>